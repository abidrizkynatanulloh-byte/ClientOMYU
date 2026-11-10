<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    /**
     * K-09: Manajemen Stok Staf
     * Menampilkan daftar stok produk beserta status ketersediaan (Tersedia, Menipis, Habis).
     */
    public function index(Request $request)
    {
        $kategoriList = Kategori::where('aktif', 1)->get();

        $query = Produk::with('kategori')->where('aktif', 1);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status_stok')) {
            if ($request->status_stok === 'habis') {
                $query->where('stok', '<=', 0);
            } elseif ($request->status_stok === 'menipis') {
                $query->where('stok', '>', 0)->where('stok', '<=', 5);
            } elseif ($request->status_stok === 'tersedia') {
                $query->where('stok', '>', 5);
            }
        }

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        $daftarProduk = $query->orderBy('stok', 'asc')->paginate(15)->withQueryString();

        // Riwayat pergerakan stok terakhir
        $riwayatTerakhir = RiwayatStok::with(['produk', 'pengguna'])
            ->latest()
            ->take(8)
            ->get();

        return view('panel.staf.stok.index', compact('daftarProduk', 'kategoriList', 'riwayatTerakhir'));
    }

    /**
     * K-10 & K-11: Penyesuaian Stok Manual & Pencatatan Kerusakan Bahan/Produk
     * Wajib menyertakan alasan penyesuaian (Aturan Bisnis Bab 11).
     */
    public function penyesuaian(Request $request, $id)
    {
        $request->validate([
            'tipe_aksi' => 'required|in:tambah,kurang,atur',
            'jumlah'    => 'required|integer|min:1',
            'alasan'    => 'required|string|min:3|max:255',
        ], [
            'alasan.required' => 'Alasan penyesuaian stok wajib diisi untuk jejak audit.',
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
            // Stok opname: set langsung ke angka fisik tertentu
            $stokBaru = $jumlahInput;
            $selisih = $stokBaru - $stokLama;
            $jenisRiwayat = 'penyesuaian';
        }

        // Aturan Bab 11: Stok tidak boleh negatif
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
                'deskripsi'   => 'Penyesuaian stok ' . $produk->nama . ' dari ' . $stokLama . ' menjadi ' . $stokBaru . ' (Alasan: ' . $alasan . ')',
            ]);
        });

        return back()->with('sukses', "Stok {$produk->nama} berhasil diperbarui dari {$stokLama} menjadi {$stokBaru}.");
    }
}

