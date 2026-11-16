<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    /**
     * K-03: Menu Pesanan Staf
     * Menampilkan daftar pesanan dengan metrik Antrean, Verifikasi, dan Proses (sesuai desain).
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['itemPesanan.produk', 'penangan', 'pembayaran'])->latest();

        // 1. Filter Tab
        if ($request->filled('tab')) {
            $tab = $request->tab;
            if ($tab === 'menunggu_cek') {
                $query->whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi']);
            } elseif ($tab === 'sudah_bayar') {
                $query->where('status_pembayaran', 'lunas');
            } elseif ($tab === 'diproses') {
                $query->where('status_pesanan', 'diproses');
            } elseif ($tab === 'siap') {
                $query->where('status_pesanan', 'siap_diambil');
            } elseif ($tab === 'selesai') {
                $query->where('status_pesanan', 'selesai');
            }
        } elseif ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'baru') {
                $query->whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi']);
            } elseif ($status === 'dikirim' || $status === 'siap') {
                $query->where('status_pesanan', 'siap_diambil');
            } else {
                $query->where('status_pesanan', $status);
            }
        }

        // Filter status pembayaran spesifik
        if ($request->filled('pembayaran')) {
            $query->where('status_pembayaran', $request->pembayaran);
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Pencarian kode pesanan #OMY atau nama pembeli
        if ($request->filled('cari')) {
            $cari = trim(str_replace('#', '', $request->cari));
            $query->where(function ($q) use ($cari) {
                $q->where('kode_pesanan', 'like', "%{$cari}%")
                  ->orWhere('nama_pembeli', 'like', "%{$cari}%")
                  ->orWhere('telepon_pembeli', 'like', "%{$cari}%");
            });
        }

        // 2. Tiga Metrik Utama di Bagian Atas
        $antreanOrderMasuk = Pesanan::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi', 'dikonfirmasi'])->count();
        $perluVerifikasiCount = Pesanan::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi'])->count();
        $prosesDiBlenderCount = Pesanan::where('status_pesanan', 'diproses')->count();

        // 3. Counter untuk Pill Tabs
        $semuaCount = Pesanan::count();
        $menungguCekCount = $perluVerifikasiCount;
        $sudahBayarCount = Pesanan::where('status_pembayaran', 'lunas')->count();
        $siapAmbilCount = Pesanan::where('status_pesanan', 'siap_diambil')->count();
        $selesaiCount = Pesanan::where('status_pesanan', 'selesai')->count();

        $daftarPesanan = $query->paginate(15)->withQueryString();

        return view('panel.staf.pesanan.index', compact(
            'daftarPesanan',
            'antreanOrderMasuk',
            'perluVerifikasiCount',
            'prosesDiBlenderCount',
            'semuaCount',
            'menungguCekCount',
            'sudahBayarCount',
            'siapAmbilCount',
            'selesaiCount'
        ));
    }

    /**
     * Detail Pesanan
     */
    public function detail($id)
    {
        $pesanan = Pesanan::with(['itemPesanan.produk', 'pembayaran', 'penangan'])->findOrFail($id);

        return view('panel.staf.pesanan.detail', compact('pesanan'));
    }

    /**
     * K-05: Verifikasi Pembayaran (Tombol Konfirmasi)
     * Memverifikasi pembayaran transfer / QRIS secara manual oleh kasir.
     */
    public function verifikasiPembayaran(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        DB::transaction(function () use ($pesanan) {
            $pesanan->status_pembayaran = 'lunas';
            $pesanan->dibayar_pada = now();
            $pesanan->ditangani_oleh = auth()->id();
            // Otomatis ubah status pesanan ke 'dikonfirmasi' atau 'diproses'
            if (in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi'])) {
                $pesanan->status_pesanan = 'dikonfirmasi';
            }
            $pesanan->save();

            // Update catatan pembayaran terkait jika ada
            $pesanan->pembayaran()->update([
                'status'       => 'lunas',
                'dibayar_pada' => now(),
            ]);

            // Catat log aktivitas (K-14)
            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'verifikasi_pembayaran',
                'tipe_objek'  => 'pesanan',
                'id_objek'    => $pesanan->id,
                'deskripsi'   => 'Kasir ' . auth()->user()->nama . ' memverifikasi pembayaran pesanan ' . $pesanan->kode_pesanan,
            ]);
        });

        return back()->with('sukses', 'Pembayaran pesanan ' . $pesanan->kode_pesanan . ' berhasil dikonfirmasi lunas.');
    }

    /**
     * K-04, K-06, K-12: Update Status Pesanan
     * Memperbarui tahapan alur pesanan (menunggu -> dikonfirmasi -> diproses -> siap_diambil -> selesai).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'required|in:menunggu_pembayaran,menunggu_verifikasi,dikonfirmasi,diproses,siap_diambil,selesai,dibatalkan,kedaluwarsa,baru,dikirim',
        ]);

        $pesanan = Pesanan::with('itemPesanan.produk')->findOrFail($id);
        $statusLama = $pesanan->status_pesanan;
        $statusBaru = $request->status_pesanan;

        if ($statusBaru === 'baru') $statusBaru = 'menunggu_verifikasi';
        if ($statusBaru === 'dikirim') $statusBaru = 'siap_diambil';

        if ($statusLama === $statusBaru) {
            return back()->with('info', 'Status pesanan tidak berubah.');
        }

        // Aturan Bab 11: Pesanan yang sudah selesai tidak dapat diubah kembali ke status sebelumnya
        if ($statusLama === 'selesai') {
            return back()->with('error', 'Pesanan yang sudah selesai tidak dapat diubah kembali.');
        }

        DB::transaction(function () use ($pesanan, $statusLama, $statusBaru) {
            $pesanan->status_pesanan = $statusBaru;
            $pesanan->ditangani_oleh = auth()->id();

            // Sesuai Bab 2.9 & Bab 10: Saat status Selesai, stok berkurang dan pendapatan/HPP diakui
            if ($statusBaru === 'selesai' && $statusLama !== 'selesai') {
                foreach ($pesanan->itemPesanan as $item) {
                    if ($item->produk_id) {
                        $produk = Produk::find($item->produk_id);
                        if ($produk) {
                            $stokSebelum = $produk->stok;
                            $stokSesudah = max(0, $stokSebelum - $item->jumlah);

                            $produk->update(['stok' => $stokSesudah]);

                            RiwayatStok::create([
                                'produk_id'    => $produk->id,
                                'pengguna_id'  => auth()->id(),
                                'pesanan_id'   => $pesanan->id,
                                'jenis'        => 'keluar_pesanan',
                                'jumlah'       => -$item->jumlah,
                                'stok_setelah' => $stokSesudah,
                                'catatan'      => 'Pengurangan stok untuk pesanan ' . $pesanan->kode_pesanan,
                            ]);
                        }
                    }
                }
            }

            $pesanan->save();

            // Catat log aktivitas (K-14)
            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'update_status_pesanan',
                'tipe_objek'  => 'pesanan',
                'id_objek'    => $pesanan->id,
                'deskripsi'   => 'Mengubah status pesanan ' . $pesanan->kode_pesanan . ' dari ' . $statusLama . ' ke ' . $statusBaru,
            ]);
        });

        return back()->with('sukses', 'Status pesanan ' . $pesanan->kode_pesanan . ' berhasil diubah menjadi ' . $statusBaru . '.');
    }

    /**
     * Membatalkan Pesanan / Tolak / Ulang dengan Alasan
     */
    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'nullable|string|max:255',
        ]);

        $pesanan = Pesanan::with('itemPesanan.produk')->findOrFail($id);

        if ($pesanan->status_pesanan === 'selesai') {
            return back()->with('error', 'Pesanan yang sudah selesai tidak dapat dibatalkan.');
        }

        $alasan = $request->alasan_batal ?: 'Pembayaran / pesanan ditolak kasir untuk perbaikan/ulang';

        DB::transaction(function () use ($pesanan, $alasan) {
            $pesanan->status_pesanan = 'dibatalkan';
            $pesanan->catatan = ($pesanan->catatan ? $pesanan->catatan . ' | ' : '') . '[Ditolak: ' . $alasan . ']';
            $pesanan->ditangani_oleh = auth()->id();
            $pesanan->save();

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'batalkan_pesanan',
                'tipe_objek'  => 'pesanan',
                'id_objek'    => $pesanan->id,
                'deskripsi'   => 'Membatalkan / menolak pesanan ' . $pesanan->kode_pesanan . ': ' . $alasan,
            ]);
        });

        return back()->with('sukses', 'Pesanan ' . $pesanan->kode_pesanan . ' telah ditolak / dibatalkan.');
    }
}
