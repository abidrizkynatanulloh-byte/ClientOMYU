<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\LogAktivitas;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    private const SATUAN_INPUT = ['kg', 'g', 'pcs', 'pack', 'ml', 'liter', 'gr'];

    public function index(Request $request)
    {
        $statusFilter = in_array($request->get('tab'), ['semua', 'kritis', 'habis'], true) ? $request->get('tab') : 'semua';
        $cari = $request->get('cari');

        $queryBahan = Bahan::query();

        if ($cari) {
            $queryBahan->where('nama', 'like', "%{$cari}%");
        }

        $semuaBahanList = Bahan::all();
        $totalItem = $semuaBahanList->count();
        $stokKritisCount = $semuaBahanList->filter(function ($bahan) {
            return $bahan->stok_tersedia > 0 && $bahan->stok_tersedia <= (float) $bahan->batas_minimum;
        })->count();
        $habisCount = $semuaBahanList->filter(function ($bahan) {
            return $bahan->stok_tersedia <= 0;
        })->count();

        if ($statusFilter === 'kritis') {
            $queryBahan->whereRaw('(stok - stok_dicadangkan) > 0 AND (stok - stok_dicadangkan) <= batas_minimum');
        } elseif ($statusFilter === 'habis') {
            $queryBahan->whereRaw('(stok - stok_dicadangkan) <= 0');
        }

        $daftarBahan = $queryBahan->orderByRaw("FIELD(jenis, 'buah', 'bahan_lain', 'kemasan') ASC")
            ->orderBy('stok', 'asc')
            ->get();

        $queryProduk = Produk::with('kategori')->where('aktif', 1);
        if ($cari) {
            $queryProduk->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                    ->orWhere('barcode', 'like', "%{$cari}%");
            });
        }
        $katalogProduk = $queryProduk->take(12)->get();

        $riwayatStok = RiwayatStok::with(['produk', 'pengguna'])
            ->latest()
            ->take(15)
            ->get();

        $pergerakanBahan = DB::table('pergerakan_stok')
            ->join('bahan', 'bahan.id', '=', 'pergerakan_stok.bahan_id')
            ->select('pergerakan_stok.*', 'bahan.nama as nama_bahan', 'bahan.satuan_dasar')
            ->latest('pergerakan_stok.created_at')
            ->take(15)
            ->get();

        $logAktivitasStok = LogAktivitas::with('pengguna')
            ->whereIn('aksi', [
                'pasokan_bahan_masuk', 'pembuangan_bahan_rusak', 'audit_stok_bahan',
                'penyesuaian_stok', 'catat_waste_gagal_racik',
            ])
            ->latest()
            ->take(15)
            ->get();

        return view('panel.staf.stok.index', compact(
            'daftarBahan',
            'semuaBahanList',
            'totalItem',
            'stokKritisCount',
            'habisCount',
            'statusFilter',
            'katalogProduk',
            'riwayatStok',
            'pergerakanBahan',
            'logAktivitasStok',
            'cari'
        ));
    }

    public function pasokanMasuk(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahan,id',
            'jumlah_masuk' => 'required|numeric|min:0.001',
            'satuan_input' => 'required|in:'.implode(',', self::SATUAN_INPUT),
            'catatan' => 'nullable|string|max:255',
        ]);

        $bahan = Bahan::findOrFail($request->bahan_id);
        $jumlahInput = (float) $request->jumlah_masuk;
        $jumlahDasar = $bahan->konversiKeDasar($request->satuan_input, $jumlahInput);
        $nilai = $jumlahDasar * (float) $bahan->biaya_rata_rata;

        DB::transaction(function () use ($bahan, $jumlahDasar, $jumlahInput, $request, $nilai) {
            $bahanLock = Bahan::whereKey($bahan->id)->lockForUpdate()->firstOrFail();
            $stokSesudah = (float) $bahanLock->stok + $jumlahDasar;
            $bahanLock->update(['stok' => $stokSesudah, 'aktif' => 1]);
            $bahanLock->catatPergerakan(
                'pembelian',
                $jumlahDasar,
                $stokSesudah,
                'pasokan',
                null,
                auth()->id(),
                $request->catatan ?: 'Pasokan bahan masuk',
                $nilai
            );

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi' => 'pasokan_bahan_masuk',
                'tipe_objek' => 'bahan',
                'id_objek' => $bahan->id,
                'deskripsi' => 'Staf '.auth()->user()->nama.' menginput pasokan masuk '.$bahan->nama.' sebanyak '.$jumlahInput.' '.$request->satuan_input.' ('.($request->catatan ?: 'Restock Supplier').')',
                'alasan' => $request->catatan,
                'ip_address' => $request->ip(),
            ]);
        });

        return back()->with('sukses', "Pasokan {$bahan->nama} sebanyak {$jumlahInput} {$request->satuan_input} berhasil ditambahkan ke stok.");
    }

    public function catatPembuangan(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahan,id',
            'jumlah_buang' => 'required|numeric|min:0.001',
            'satuan_input' => 'required|in:'.implode(',', self::SATUAN_INPUT),
            'alasan' => 'required|string|min:3|max:255',
        ], [
            'alasan.required' => 'Wajib mengisi alasan pembuangan bahan rusak/busuk.',
        ]);

        $bahan = Bahan::findOrFail($request->bahan_id);
        $jumlahInput = (float) $request->jumlah_buang;
        $jumlahDasar = $bahan->konversiKeDasar($request->satuan_input, $jumlahInput);

        if ($bahan->stok_tersedia < $jumlahDasar) {
            return back()->with('error', 'Jumlah yang dibuang melebihi stok tersedia yang tercatat.');
        }

        DB::transaction(function () use ($bahan, $jumlahDasar, $jumlahInput, $request) {
            $bahanLock = Bahan::whereKey($bahan->id)->lockForUpdate()->firstOrFail();
            $stokSesudah = (float) $bahanLock->stok - $jumlahDasar;
            $bahanLock->update(['stok' => $stokSesudah]);
            $bahanLock->catatPergerakan('rusak', -$jumlahDasar, $stokSesudah, 'pembuangan', null, auth()->id(), $request->alasan);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi' => 'pembuangan_bahan_rusak',
                'tipe_objek' => 'bahan',
                'id_objek' => $bahan->id,
                'deskripsi' => 'Pembuangan '.$bahan->nama.' sebanyak '.$jumlahInput.' '.$request->satuan_input.'. Alasan: '.$request->alasan,
                'alasan' => $request->alasan,
                'ip_address' => $request->ip(),
            ]);
        });

        return back()->with('sukses', "Pencatatan pembuangan {$bahan->nama} ({$jumlahInput} {$request->satuan_input}) berhasil disimpan.");
    }

    public function toggleAktif(Request $request, $id)
    {
        $bahan = Bahan::findOrFail($id);
        $statusBaru = ! (bool) $bahan->aktif;

        DB::transaction(function () use ($bahan, $statusBaru, $request) {
            $bahan->update(['aktif' => $statusBaru]);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi' => 'toggle_status_bahan',
                'tipe_objek' => 'bahan',
                'id_objek' => $bahan->id,
                'deskripsi' => 'Mengubah status bahan '.$bahan->nama.' menjadi '.($statusBaru ? 'Tersedia (Aktif)' : 'Tutup (Nonaktif)'),
                'ip_address' => $request->ip(),
            ]);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'sukses' => true,
                'aktif' => $statusBaru,
                'pesan' => 'Status '.$bahan->nama.' berhasil diubah.',
            ]);
        }

        return back()->with('sukses', "Status ketersediaan {$bahan->nama} diubah menjadi ".($statusBaru ? 'Tersedia' : 'Tutup/Nonaktif').'.');
    }

    public function auditBahan(Request $request, $id)
    {
        $request->validate([
            'stok_fisik' => 'required|numeric|min:0',
            'satuan_input' => 'required|in:'.implode(',', self::SATUAN_INPUT),
            'catatan' => 'required|string|min:3|max:255',
        ]);

        $bahan = Bahan::findOrFail($id);
        $stokFisikDasar = $bahan->konversiKeDasar($request->satuan_input, (float) $request->stok_fisik);
        $stokSistem = (float) $bahan->stok;
        $selisih = $stokFisikDasar - $stokSistem;

        DB::transaction(function () use ($bahan, $stokSistem, $stokFisikDasar, $selisih, $request) {
            $bahanLock = Bahan::whereKey($bahan->id)->lockForUpdate()->firstOrFail();
            $bahanLock->update([
                'stok' => $stokFisikDasar,
                'aktif' => $stokFisikDasar > 0 ? 1 : 0,
            ]);
            $bahanLock->catatPergerakan('opname', $selisih, $stokFisikDasar, 'audit', null, auth()->id(), $request->catatan);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi' => 'audit_stok_bahan',
                'tipe_objek' => 'bahan',
                'id_objek' => $bahan->id,
                'deskripsi' => 'Audit stok '.$bahan->nama.' disesuaikan dari '.$stokSistem.' menjadi '.$stokFisikDasar.' (Selisih: '.$selisih.').',
                'alasan' => $request->catatan,
                'ip_address' => $request->ip(),
                'nilai_sebelum' => ['stok' => $stokSistem],
                'nilai_sesudah' => ['stok' => $stokFisikDasar, 'selisih' => $selisih],
            ]);
        });

        return back()->with('sukses', "Audit opname {$bahan->nama} berhasil. Stok diperbarui ke {$request->stok_fisik} {$request->satuan_input}.");
    }

    public function penyesuaian(Request $request, $id)
    {
        $request->validate([
            'tipe_aksi' => 'required|in:tambah,kurang,atur',
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string|min:3|max:255',
        ]);

        $produk = Produk::findOrFail($id);
        $stokLama = $produk->stok;
        $jumlahInput = (int) $request->jumlah;
        $alasan = $request->alasan;

        $selisih = 0;
        $stokBaru = $stokLama;
        $jenisRiwayat = 'penyesuaian';

        if ($request->tipe_aksi === 'tambah') {
            $selisih = $jumlahInput;
            $stokBaru = $stokLama + $jumlahInput;
            $jenisRiwayat = 'masuk';
        } elseif ($request->tipe_aksi === 'kurang') {
            if ($stokLama < $jumlahInput) {
                return back()->with('error', 'Jumlah pengurangan melebihi stok yang tersedia.');
            }
            $selisih = -$jumlahInput;
            $stokBaru = $stokLama - $jumlahInput;
        } else {
            $stokBaru = $jumlahInput;
            $selisih = $stokBaru - $stokLama;
        }

        if ($stokBaru < 0) {
            return back()->with('error', 'Stok akhir tidak boleh kurang dari nol.');
        }

        DB::transaction(function () use ($produk, $stokLama, $stokBaru, $selisih, $jenisRiwayat, $alasan, $request) {
            $produk->update(['stok' => $stokBaru]);

            RiwayatStok::create([
                'produk_id' => $produk->id,
                'pengguna_id' => auth()->id(),
                'jenis' => $jenisRiwayat,
                'jumlah' => $selisih,
                'stok_setelah' => $stokBaru,
                'catatan' => $alasan,
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi' => 'penyesuaian_stok',
                'tipe_objek' => 'produk',
                'id_objek' => $produk->id,
                'deskripsi' => 'Penyesuaian stok '.$produk->nama.' dari '.$stokLama.' ke '.$stokBaru.' ('.$alasan.')',
                'alasan' => $alasan,
                'ip_address' => $request->ip(),
                'nilai_sebelum' => ['stok' => $stokLama],
                'nilai_sesudah' => ['stok' => $stokBaru],
            ]);
        });

        return back()->with('sukses', "Stok {$produk->nama} berhasil disesuaikan menjadi {$stokBaru}.");
    }
}
