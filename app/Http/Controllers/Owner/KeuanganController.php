<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ItemPesanan;
use App\Models\KasTransaksi;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Ambil semua transaksi kas masuk & keluar
        $mutasiKas = KasTransaksi::with('pencatat')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totalKasMasuk = KasTransaksi::where('jenis', 'masuk')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->sum('jumlah');

        $totalKasKeluar = KasTransaksi::where('jenis', 'keluar')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->sum('jumlah');

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