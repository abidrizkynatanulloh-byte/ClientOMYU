<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\ItemPesanan;
use App\Models\KasTransaksi;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function labaRugi(Request $request)
    {
        // 1. Tentukan Bulan & Tahun Periode (default: bulan berjalan)
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // 2. Hitung Penjualan / Omzet (Pesanan selesai pada bulan tersebut)
        $penjualan = Pesanan::where('status_pesanan', 'selesai')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->sum('total');

        // 3. Hitung HPP Bahan Baku & Kemasan yang Terpakai
        $hpp = ItemPesanan::whereHas('pesanan', function ($q) use ($tanggalAwal, $tanggalAkhir) {
            $q->where('status_pesanan', 'selesai')
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
        })->sum(DB::raw('jumlah * (COALESCE(hpp_bahan, 0) + COALESCE(hpp_kemasan, 0))'));

        // 4. Laba Kotor & Food Cost Ratio (%)
        $labaKotor = $penjualan - $hpp;
        $foodCostRatio = $penjualan > 0 ? round(($hpp / $penjualan) * 100, 1) : 0;
        $marginLabaKotor = $penjualan > 0 ? round(($labaKotor / $penjualan) * 100, 1) : 0;

        // 5. Beban Operasional Kas Keluar (dari kas_transaksi jenis 'keluar')
        $daftarBeban = KasTransaksi::where('jenis', 'keluar')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $totalBebanOperasional = $daftarBeban->sum('jumlah');

        // 6. Laba Bersih Usaha
        $labaBersih = $labaKotor - $totalBebanOperasional;
        $marginLabaBersih = $penjualan > 0 ? round(($labaBersih / $penjualan) * 100, 1) : 0;

        return view('panel.admin.keuangan.laba_rugi', compact(
            'bulan',
            'tahun',
            'penjualan',
            'hpp',
            'labaKotor',
            'foodCostRatio',
            'marginLabaKotor',
            'daftarBeban',
            'totalBebanOperasional',
            'labaBersih',
            'marginLabaBersih'
        ));
    }

    public function bukuKas(Request $request)
    {
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Kas masuk bersumber dari pembayaran lunas (online maupun kasir),
        // lengkap dengan nama pembeli sebagai pihak yang menyetor uang.
        $kasMasuk = Pembayaran::with(['pesanan', 'verifikator'])
            ->where('status', 'lunas')
            ->whereBetween(DB::raw('COALESCE(dibayar_pada, created_at)'), [$tanggalAwal, $tanggalAkhir])
            ->get()
            ->map(function (Pembayaran $pembayaran) {
                $namaPembeli = $pembayaran->pesanan?->nama_pembeli ?: 'Pelanggan';

                $mutasi = new KasTransaksi;
                $mutasi->jenis = 'masuk';
                $mutasi->jumlah = $pembayaran->jumlah;
                $mutasi->keterangan = 'Pembayaran '.$pembayaran->pesanan?->kode_pesanan
                    .' — '.$namaPembeli.' ('.strtoupper((string) $pembayaran->metode).')';
                $mutasi->created_at = $pembayaran->dibayar_pada ?? $pembayaran->created_at;
                $mutasi->sumber = 'Dari: '.$namaPembeli;
                $mutasi->setRelation('pencatat', $pembayaran->verifikator);

                return $mutasi;
            });

        // Setiap pasokan bahan yang diinput staf merupakan uang keluar. Nilainya
        // diambil dari pergerakan stok (total harga aktual bila diisi staf, atau
        // jumlah stok × biaya rata-rata bila tidak diisi).
        $pasokanTercatat = DB::table('pergerakan_stok')
            ->join('bahan', 'bahan.id', '=', 'pergerakan_stok.bahan_id')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'pergerakan_stok.pengguna_id')
            ->select([
                'pergerakan_stok.id',
                'pergerakan_stok.bahan_id',
                'pergerakan_stok.nilai',
                'pergerakan_stok.jumlah',
                'pergerakan_stok.alasan',
                'pergerakan_stok.created_at',
                'pergerakan_stok.pengguna_id',
                'bahan.nama as nama_bahan',
                'bahan.satuan_dasar',
                'bahan.created_at as bahan_created_at',
                'pengguna.nama as nama_pengguna',
            ])
            ->where('pergerakan_stok.jenis', 'pembelian')
            ->where('pergerakan_stok.rujukan_tipe', 'pasokan')
            ->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('pergerakan_stok.created_at', [$tanggalAwal, $tanggalAkhir]);

                // Catatan lama belum memiliki waktu; tampilkan pada periode berjalan.
                if (now()->between($tanggalAwal, $tanggalAkhir)) {
                    $query->orWhereNull('pergerakan_stok.created_at');
                }
            })
            ->orderByDesc('pergerakan_stok.created_at')
            ->get();

        $petugasPasokan = Pengguna::whereIn('id', $pasokanTercatat->pluck('pengguna_id')->filter())
            ->get()
            ->keyBy('id');

        // Catatan pasokan lama belum punya created_at; pakai waktu log aktivitas
        // yang tersimpan agar jam mutasi tidak berubah setiap laporan dibuka.
        $waktuLogPasokan = DB::table('log_aktivitas')
            ->where('aksi', 'pasokan_bahan_masuk')
            ->whereIn('id_objek', $pasokanTercatat->pluck('bahan_id')->filter())
            ->orderBy('created_at')
            ->get(['id_objek', 'created_at'])
            ->groupBy('id_objek')
            ->map(fn ($logPerBahan) => $logPerBahan->pluck('created_at'));

        $kasPasokan = $pasokanTercatat->map(function ($pasokan) use ($petugasPasokan, $waktuLogPasokan) {
            $jumlahTeks = rtrim(
                rtrim(number_format((float) $pasokan->jumlah, 3, ',', '.'), '0'),
                ',.'
            );

            $mutasi = new KasTransaksi;
            $mutasi->jenis = 'keluar';
            $mutasi->jumlah = (int) round((float) $pasokan->nilai);
            $mutasi->keterangan = 'Pasokan '.$pasokan->nama_bahan.' — '.$jumlahTeks.' '.$pasokan->satuan_dasar
                .($pasokan->alasan ? ' · '.$pasokan->alasan : '');
            $waktuPasokan = $pasokan->created_at
                ?: $waktuLogPasokan->get($pasokan->bahan_id)?->shift()
                ?: $pasokan->bahan_created_at;
            $mutasi->created_at = Carbon::parse($waktuPasokan);
            $mutasi->sumber = 'Untuk: '.$pasokan->nama_bahan;
            $mutasi->setRelation('pencatat', $petugasPasokan->get($pasokan->pengguna_id));

            return $mutasi;
        });

        $kasTercatat = KasTransaksi::with('pencatat')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->get()
            ->each(function (KasTransaksi $mutasi) {
                $mutasi->sumber = $mutasi->jenis === 'masuk'
                    ? 'Dari: '.$mutasi->keterangan
                    : 'Untuk: '.$mutasi->keterangan;
            });

        // Sisa modal persediaan yang belum punya catatan pasokan per transaksi,
        // supaya total kas keluar tetap sama dengan Total Nilai Persediaan.
        $totalNilaiPersediaan = (int) round(Bahan::totalNilaiPersediaan());
        $totalPasokanTercatat = (int) round((float) DB::table('pergerakan_stok')
            ->where('jenis', 'pembelian')
            ->where('rujukan_tipe', 'pasokan')
            ->sum('nilai'));
        $sisaModalPersediaan = max(0, $totalNilaiPersediaan - $totalPasokanTercatat);

        $kasModalPersediaan = collect();
        if ($sisaModalPersediaan > 0 && now()->between($tanggalAwal, $tanggalAkhir)) {
            $mutasi = new KasTransaksi;
            $mutasi->jenis = 'keluar';
            $mutasi->jumlah = $sisaModalPersediaan;
            $mutasi->keterangan = 'Modal persediaan (stok awal / di luar catatan pasokan)';
            $mutasi->created_at = Carbon::parse(
                Bahan::where('aktif', 1)->min('created_at') ?? $tanggalAwal
            );
            $mutasi->sumber = 'Untuk: persediaan bahan baku & kemasan';

            $kasModalPersediaan = collect([$mutasi]);
        }

        $semuaMutasi = $kasMasuk->concat($kasTercatat)->concat($kasPasokan)->concat($kasModalPersediaan)
            ->sortByDesc(fn (KasTransaksi $mutasi) => $mutasi->created_at)
            ->values();

        $halaman = max(1, (int) $request->input('page', 1));
        $mutasiKas = new LengthAwarePaginator(
            $semuaMutasi->forPage($halaman, 20),
            $semuaMutasi->count(),
            20,
            $halaman,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $totalKasMasuk = $kasMasuk->sum('jumlah')
            + $kasTercatat->where('jenis', 'masuk')->sum('jumlah');

        $totalKasKeluar = $kasTercatat->where('jenis', 'keluar')->sum('jumlah')
            + $kasPasokan->sum('jumlah')
            + $kasModalPersediaan->sum('jumlah');

        $saldoKasBersih = $totalKasMasuk - $totalKasKeluar;

        return view('panel.admin.keuangan.buku_kas', compact(
            'bulan',
            'tahun',
            'mutasiKas',
            'totalKasMasuk',
            'totalKasKeluar',
            'saldoKasBersih'
        ));
    }
}
