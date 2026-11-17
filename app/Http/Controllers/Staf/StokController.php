<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    /**
     * K-09: Manajemen Stok & Bahan Baku Segar
     * Menampilkan visual stok bahan baku (buah-buahan segar & bahan pelengkap),
     * metrik kritis/habis, audit opname rutin 14:00 WIB, serta sinkronisasi menu POS.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->get('tab', 'semua'); // 'semua', 'kritis', 'habis'
        $cari = $request->get('cari');

        // Query Bahan Baku
        $queryBahan = Bahan::query();

        if ($cari) {
            $queryBahan->where('nama', 'like', "%{$cari}%");
        }

        // Hitung Metrik Utama (Semua Bahan)
        $semuaBahanList = Bahan::all();
        $totalItem = $semuaBahanList->count();
        $stokKritisCount = $semuaBahanList->filter(function ($b) {
            return (float) $b->stok > 0 && (float) $b->stok <= (float) $b->batas_minimum;
        })->count();
        $habisCount = $semuaBahanList->filter(function ($b) {
            return (float) $b->stok <= 0;
        })->count();

        // Terapkan Filter Tab
        if ($statusFilter === 'kritis') {
            $queryBahan->whereRaw('stok > 0 AND stok <= batas_minimum');
        } elseif ($statusFilter === 'habis') {
            $queryBahan->where('stok', '<=', 0);
        }

        $daftarBahan = $queryBahan->orderByRaw("FIELD(jenis, 'buah', 'bahan_lain', 'kemasan') ASC")
            ->orderBy('stok', 'asc')
            ->get();

        // Katalog Menu Produk & Resep POS
        $queryProduk = Produk::with('kategori')->where('aktif', 1);
        if ($cari) {
            $queryProduk->where('nama', 'like', "%{$cari}%");
        }
        $katalogProduk = $queryProduk->take(12)->get();

        // Riwayat Penggunaan & Kerusakan Terakhir (untuk Drawer/Modal)
        $riwayatStok = RiwayatStok::with(['produk', 'pengguna'])
            ->latest()
            ->take(15)
            ->get();

        $logAktivitasStok = LogAktivitas::with('pengguna')
            ->whereIn('aksi', ['pasokan_bahan_masuk', 'pembuangan_bahan_rusak', 'audit_stok_bahan', 'penyesuaian_stok', 'catat_waste_gagal_racik'])
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
            'logAktivitasStok',
            'cari'
        ));
    }

    /**
     * Input Pasokan / Bahan Masuk dari Supplier atau Petani
     */
    public function pasokanMasuk(Request $request)
    {
        $request->validate([
            'bahan_id'     => 'required|exists:bahan,id',
            'jumlah_masuk' => 'required|numeric|min:0.1',
            'satuan_input' => 'required|in:kg,g,pcs,pack,ml',
            'catatan'      => 'nullable|string|max:255',
        ]);

        $bahan = Bahan::findOrFail($request->bahan_id);
        $jumlahInput = (float) $request->jumlah_masuk;
        $satuanInput = $request->satuan_input;

        // Konversi ke satuan dasar bahan (misal g jika bahan satuannya g dan inputnya kg)
        $jumlahDasar = $jumlahInput;
        if ($bahan->satuan_dasar === 'g' && $satuanInput === 'kg') {
            $jumlahDasar = $jumlahInput * 1000;
        } elseif ($bahan->satuan_dasar === 'pcs' && $satuanInput === 'pack') {
            $jumlahDasar = $jumlahInput * (float) ($bahan->konversi ?: 50);
        }

        DB::transaction(function () use ($bahan, $jumlahDasar, $jumlahInput, $satuanInput, $request) {
            $stokSebelum = (float) $bahan->stok;
            $stokSesudah = $stokSebelum + $jumlahDasar;

            $bahan->update([
                'stok'  => $stokSesudah,
                'aktif' => 1, // Otomatis aktif kembali jika sebelumnya habis
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'pasokan_bahan_masuk',
                'tipe_objek'  => 'bahan',
                'id_objek'    => $bahan->id,
                'deskripsi'   => 'Staf ' . auth()->user()->nama . ' menginput pasokan masuk ' . $bahan->nama . ' sebanyak ' . $jumlahInput . ' ' . $satuanInput . ' (' . ($request->catatan ?: 'Restock Supplier') . ')',
            ]);
        });

        return back()->with('sukses', "Pasokan {$bahan->nama} sebanyak {$jumlahInput} {$satuanInput} berhasil ditambahkan ke stok.");
    }

    /**
     * Catat Pembuangan (Buah Rusak / Busuk / Kadaluwarsa) - SOP Bab 11
     */
    public function catatPembuangan(Request $request)
    {
        $request->validate([
            'bahan_id'       => 'required|exists:bahan,id',
            'jumlah_buang'   => 'required|numeric|min:0.01',
            'satuan_input'   => 'required|in:kg,g,pcs,ml',
            'alasan'         => 'required|string|min:3|max:255',
        ], [
            'alasan.required' => 'Wajib mengisi alasan pembuangan bahan rusak/busuk.',
        ]);

        $bahan = Bahan::findOrFail($request->bahan_id);
        $jumlahInput = (float) $request->jumlah_buang;
        $satuanInput = $request->satuan_input;

        $jumlahDasar = $jumlahInput;
        if ($bahan->satuan_dasar === 'g' && $satuanInput === 'kg') {
            $jumlahDasar = $jumlahInput * 1000;
        }

        if ((float) $bahan->stok < $jumlahDasar) {
            return back()->with('error', 'Jumlah yang dibuang melebihi stok fisik yang tercatat.');
        }

        DB::transaction(function () use ($bahan, $jumlahDasar, $jumlahInput, $satuanInput, $request) {
            $stokSebelum = (float) $bahan->stok;
            $stokSesudah = max(0, $stokSebelum - $jumlahDasar);

            $bahan->update(['stok' => $stokSesudah]);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'pembuangan_bahan_rusak',
                'tipe_objek'  => 'bahan',
                'id_objek'    => $bahan->id,
                'deskripsi'   => 'Pembuangan ' . $bahan->nama . ' sebanyak ' . $jumlahInput . ' ' . $satuanInput . '. Alasan: ' . $request->alasan,
            ]);
        });

        return back()->with('sukses', "Pencatatan pembuangan {$bahan->nama} ({$jumlahInput} {$satuanInput}) berhasil disimpan.");
    }

    /**
     * Toggle status aktif bahan di POS Menu
     */
    public function toggleAktif(Request $request, $id)
    {
        $bahan = Bahan::findOrFail($id);
        $statusSekarang = (bool) $bahan->aktif;
        $statusBaru = !$statusSekarang;

        $bahan->update(['aktif' => $statusBaru]);

        LogAktivitas::create([
            'pengguna_id' => auth()->id(),
            'aksi'        => 'toggle_status_bahan',
            'tipe_objek'  => 'bahan',
            'id_objek'    => $bahan->id,
            'deskripsi'   => 'Mengubah status bahan ' . $bahan->nama . ' menjadi ' . ($statusBaru ? 'Tersedia (Aktif)' : 'Tutup (Nonaktif)'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'sukses' => true,
                'aktif'  => $statusBaru,
                'pesan'  => 'Status ' . $bahan->nama . ' berhasil diubah.',
            ]);
        }

        return back()->with('sukses', "Status ketersediaan {$bahan->nama} diubah menjadi " . ($statusBaru ? 'Tersedia' : 'Tutup/Nonaktif') . ".");
    }

    /**
     * Audit Rutin 14:00 WIB (Stok Opname Bahan Fisik)
     */
    public function auditBahan(Request $request, $id)
    {
        $request->validate([
            'stok_fisik'   => 'required|numeric|min:0',
            'satuan_input' => 'required|in:kg,g,pcs,ml',
            'catatan'      => 'nullable|string|max:255',
        ]);

        $bahan = Bahan::findOrFail($id);
        $stokFisikInput = (float) $request->stok_fisik;
        $satuanInput = $request->satuan_input;

        $stokFisikDasar = $stokFisikInput;
        if ($bahan->satuan_dasar === 'g' && $satuanInput === 'kg') {
            $stokFisikDasar = $stokFisikInput * 1000;
        }

        $stokSistem = (float) $bahan->stok;
        $selisih = $stokFisikDasar - $stokSistem;

        DB::transaction(function () use ($bahan, $stokSistem, $stokFisikDasar, $selisih, $request) {
            $bahan->update([
                'stok'  => $stokFisikDasar,
                'aktif' => $stokFisikDasar > 0 ? 1 : 0,
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'audit_stok_bahan',
                'tipe_objek'  => 'bahan',
                'id_objek'    => $bahan->id,
                'deskripsi'   => 'Audit Opname 14:00 WIB: ' . $bahan->nama . ' disesuaikan dari ' . $stokSistem . ' menjadi ' . $stokFisikDasar . ' (Selisih: ' . $selisih . '). ' . ($request->catatan ?: ''),
            ]);
        });

        return back()->with('sukses', "Audit opname {$bahan->nama} berhasil. Stok diperbarui ke {$stokFisikInput} {$satuanInput}.");
    }

    /**
     * K-10 & K-11: Penyesuaian Stok Produk Jadi (Backward Compatibility)
     */
    public function penyesuaian(Request $request, $id)
    {
        $request->validate([
            'tipe_aksi' => 'required|in:tambah,kurang,atur',
            'jumlah'    => 'required|integer|min:1',
            'alasan'    => 'required|string|min:3|max:255',
        ]);

        $produk = Produk::findOrFail($id);
        $stokLama = $produk->stok;
        $tipeAksi = $request->tipe_aksi;
        $jumlahInput = (int) $request->jumlah;
        $alasan = $request->alasan;

        $selisih = 0;
        $stokBaru = $stokLama;
        $jenisRiwayat = 'penyesuaian';

        if ($tipeAksi === 'tambah') {
            $selisih = $jumlahInput;
            $stokBaru = $stokLama + $jumlahInput;
            $jenisRiwayat = 'masuk';
        } elseif ($tipeAksi === 'kurang') {
            if ($stokLama < $jumlahInput) {
                return back()->with('error', 'Jumlah pengurangan melebihi stok yang tersedia.');
            }
            $selisih = -$jumlahInput;
            $stokBaru = $stokLama - $jumlahInput;
            $jenisRiwayat = 'penyesuaian';
        } elseif ($tipeAksi === 'atur') {
            $stokBaru = $jumlahInput;
            $selisih = $stokBaru - $stokLama;
            $jenisRiwayat = 'penyesuaian';
        }

        if ($stokBaru < 0) {
            return back()->with('error', 'Stok akhir tidak boleh kurang dari nol.');
        }

        DB::transaction(function () use ($produk, $stokLama, $stokBaru, $selisih, $jenisRiwayat, $alasan) {
            $produk->update(['stok' => $stokBaru]);

            RiwayatStok::create([
                'produk_id'    => $produk->id,
                'pengguna_id'  => auth()->id(),
                'jenis'        => $jenisRiwayat,
                'jumlah'       => $selisih,
                'stok_setelah' => $stokBaru,
                'catatan'      => $alasan,
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'penyesuaian_stok',
                'tipe_objek'  => 'produk',
                'id_objek'    => $produk->id,
                'deskripsi'   => 'Penyesuaian stok ' . $produk->nama . ' dari ' . $stokLama . ' ke ' . $stokBaru . ' (' . $alasan . ')',
            ]);
        });

        return back()->with('sukses', "Stok {$produk->nama} berhasil disesuaikan menjadi {$stokBaru}.");
    }
}
