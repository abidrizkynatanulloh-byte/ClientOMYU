<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\LogAktivitas;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    /**
     * K-06: Antrean Racik Jus & KDS Mode (Kitchen Display System)
     * Mengatur urutan pembuatan jus di stasiun blender, status antrean, dan mode kartu/KDS.
     */
    public function index(Request $request)
    {
        $mode = $request->get('mode', 'kartu'); // 'kartu' atau 'kds'

        // Ambil semua pesanan aktif yang membutuhkan atau sedang dalam proses produksi
        // Urutan: 'diproses' (sedang dibuat) lebih dulu, kemudian 'dikonfirmasi' (siap racik), lalu 'menunggu_verifikasi'
        $pesananAktif = Pesanan::with(['itemPesanan.produk', 'pembayaran'])
            ->whereIn('status_pesanan', ['diproses', 'dikonfirmasi', 'menunggu_verifikasi', 'menunggu_pembayaran'])
            ->orderByRaw("FIELD(status_pesanan, 'diproses', 'dikonfirmasi', 'menunggu_verifikasi', 'menunggu_pembayaran') ASC")
            ->orderBy('created_at', 'asc')
            ->get();

        $totalAktifCount = $pesananAktif->count();

        // 1. Pesanan yang sedang dibuat di Blender Station (urutan #1)
        $sedangDibuat = $pesananAktif->first(function ($p) {
            return $p->status_pesanan === 'diproses';
        }) ?? $pesananAktif->first();

        // 2. Pesanan Berikutnya (urutan #2)
        $sisaAntrean = $pesananAktif->reject(function ($p) use ($sedangDibuat) {
            return $sedangDibuat && $p->id === $sedangDibuat->id;
        })->values();

        $berikutnya = $sisaAntrean->first();

        // 3. Pesanan Menunggu dalam antrean (#3 dst)
        $antreanMenunggu = $sisaAntrean->slice(1)->values();

        // Pesanan yang baru selesai diracik (siap diambil)
        $siapDiambil = Pesanan::with(['itemPesanan.produk'])
            ->where('status_pesanan', 'siap_diambil')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Status station blender berdasarkan beban antrean
        if ($totalAktifCount >= 5) {
            $statusBeban = 'Pagi Ramai';
            $bebanWarna = 'merah';
        } elseif ($totalAktifCount >= 2) {
            $statusBeban = 'Sedang';
            $bebanWarna = 'oranye';
        } else {
            $statusBeban = 'Santai';
            $bebanWarna = 'hijau';
        }

        // Daftar produk & bahan untuk modal pencatatan tumpah / gagal racik
        $daftarProduk = Produk::where('aktif', 1)->orderBy('nama')->get();
        $daftarBahan = class_exists(Bahan::class) ? Bahan::where('aktif', 1)->orderBy('nama')->get() : collect();

        return view('panel.staf.produksi.index', compact(
            'mode',
            'pesananAktif',
            'totalAktifCount',
            'sedangDibuat',
            'berikutnya',
            'antreanMenunggu',
            'siapDiambil',
            'statusBeban',
            'bebanWarna',
            'daftarProduk',
            'daftarBahan'
        ));
    }

    /**
     * Memulai proses racik pesanan (status -> diproses)
     */
    public function mulaiRacik(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_pesanan === 'selesai' || $pesanan->status_pesanan === 'dibatalkan') {
            return back()->with('error', 'Pesanan tidak dapat diproses karena sudah selesai atau dibatalkan.');
        }

        DB::transaction(function () use ($pesanan) {
            $pesanan->status_pesanan = 'diproses';
            $pesanan->ditangani_oleh = auth()->id();
            $pesanan->save();

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'mulai_racik_produksi',
                'tipe_objek'  => 'pesanan',
                'id_objek'    => $pesanan->id,
                'deskripsi'   => 'Barista ' . auth()->user()->nama . ' mulai meracik pesanan #' . $pesanan->kode_pesanan,
            ]);
        });

        return back()->with('sukses', 'Pesanan #' . $pesanan->kode_pesanan . ' sekarang sedang diracik di blender.');
    }

    /**
     * Menyelesaikan proses racik pesanan (status -> siap_diambil)
     */
    public function selesaiRacik(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        DB::transaction(function () use ($pesanan) {
            $pesanan->status_pesanan = 'siap_diambil';
            $pesanan->ditangani_oleh = auth()->id();
            $pesanan->save();

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'selesai_racik_produksi',
                'tipe_objek'  => 'pesanan',
                'id_objek'    => $pesanan->id,
                'deskripsi'   => 'Barista ' . auth()->user()->nama . ' menyelesaikan racikan pesanan #' . $pesanan->kode_pesanan . ' (Siap Diambil)',
            ]);
        });

        return back()->with('sukses', 'Pesanan #' . $pesanan->kode_pesanan . ' selesai diracik dan dipindahkan ke meja Pick-Up!');
    }

    /**
     * K-11: Catat Bahan Tumpah / Gagal Racik / Kerusakan Bahan Operasional
     */
    public function catatGagal(Request $request)
    {
        $request->validate([
            'tipe_item'   => 'required|in:produk,bahan',
            'item_id'     => 'required|integer',
            'jumlah'      => 'required|numeric|min:1',
            'alasan'      => 'required|string|min:3|max:255',
        ], [
            'alasan.required' => 'Wajib mengisi alasan bahan tumpah atau gagal racik.',
        ]);

        $alasan = $request->alasan;
        $jumlah = (float) $request->jumlah;

        DB::transaction(function () use ($request, $alasan, $jumlah) {
            if ($request->tipe_item === 'produk') {
                $produk = Produk::findOrFail($request->item_id);
                $stokSebelum = $produk->stok;
                $stokSesudah = max(0, $stokSebelum - (int)$jumlah);

                $produk->update(['stok' => $stokSesudah]);

                RiwayatStok::create([
                    'produk_id'    => $produk->id,
                    'pengguna_id'  => auth()->id(),
                    'jenis'        => 'penyesuaian',
                    'jumlah'       => -$jumlah,
                    'stok_setelah' => $stokSesudah,
                    'catatan'      => '[Gagal Racik/Waste] ' . $alasan,
                ]);

                $namaItem = $produk->nama;
            } else {
                $bahan = Bahan::findOrFail($request->item_id);
                $stokSebelum = $bahan->stok;
                $stokSesudah = max(0, $stokSebelum - $jumlah);

                $bahan->update(['stok' => $stokSesudah]);
                $namaItem = $bahan->nama;
            }

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'catat_waste_gagal_racik',
                'tipe_objek'  => $request->tipe_item,
                'id_objek'    => $request->item_id,
                'deskripsi'   => 'Barista ' . auth()->user()->nama . ' mencatat gagal racik/tumpah ' . $namaItem . ' sebanyak ' . $jumlah . ': ' . $alasan,
            ]);
        });

        return back()->with('sukses', 'Laporan bahan tumpah/gagal racik berhasil disimpan dan stok otomatis disesuaikan.');
    }
}

