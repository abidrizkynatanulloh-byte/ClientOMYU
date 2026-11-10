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
     * K-03: Pesanan Masuk
     * Menampilkan seluruh pesanan online dan langsung dengan filter status, tanggal, dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['itemPesanan', 'penangan', 'pembayaran'])->latest();

        // Filter status pesanan
        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        // Filter status pembayaran
        if ($request->filled('pembayaran')) {
            $query->where('status_pembayaran', $request->pembayaran);
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Pencarian kode pesanan, nama pembeli, atau nomor telepon
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('kode_pesanan', 'like', "%{$cari}%")
                  ->orWhere('nama_pembeli', 'like', "%{$cari}%")
                  ->orWhere('telepon_pembeli', 'like', "%{$cari}%");
            });
        }

        // Counter untuk tab badge
        $semuaCount = Pesanan::count();
        $baruCount = Pesanan::where('status_pesanan', 'baru')->count();
        $prosesCount = Pesanan::where('status_pesanan', 'diproses')->count();
        $siapCount = Pesanan::where('status_pesanan', 'dikirim')->count();
        $selesaiCount = Pesanan::where('status_pesanan', 'selesai')->count();
        $batalCount = Pesanan::where('status_pesanan', 'dibatalkan')->count();

        $daftarPesanan = $query->paginate(15)->withQueryString();

        return view('panel.staf.pesanan.index', compact(
            'daftarPesanan',
            'semuaCount',
            'baruCount',
            'prosesCount',
            'siapCount',
            'selesaiCount',
            'batalCount'
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
     * K-05: Verifikasi Pembayaran
     * Memverifikasi pembayaran transfer / QRIS secara manual oleh kasir.
     */
    public function verifikasiPembayaran(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_pembayaran === 'lunas') {
            return back()->with('info', 'Pembayaran pesanan ini sudah berstatus lunas.');
        }

        DB::transaction(function () use ($pesanan) {
            $pesanan->status_pembayaran = 'lunas';
            $pesanan->dibayar_pada = now();
            $pesanan->ditangani_oleh = auth()->id();
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

        return back()->with('sukses', 'Pembayaran pesanan ' . $pesanan->kode_pesanan . ' berhasil diverifikasi.');
    }

    /**
     * K-04, K-06, K-12: Update Status Pesanan
     * Memperbarui tahapan alur pesanan (baru -> diproses -> siap diambil -> selesai).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'required|in:baru,diproses,dikirim,selesai,dibatalkan',
        ]);

        $pesanan = Pesanan::with('itemPesanan.produk')->findOrFail($id);
        $statusLama = $pesanan->status_pesanan;
        $statusBaru = $request->status_pesanan;

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
     * Membatalkan Pesanan dengan Alasan Wajib (Bab 11 Aturan Bisnis)
     */
    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string|min:3|max:255',
        ]);

        $pesanan = Pesanan::with('itemPesanan.produk')->findOrFail($id);

        if ($pesanan->status_pesanan === 'selesai') {
            return back()->with('error', 'Pesanan yang sudah selesai tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($pesanan, $request) {
            $statusSebelum = $pesanan->status_pesanan;
            $pesanan->status_pesanan = 'dibatalkan';
            $pesanan->catatan = ($pesanan->catatan ? $pesanan->catatan . ' | ' : '') . '[Dibatalkan: ' . $request->alasan_batal . ']';
            $pesanan->ditangani_oleh = auth()->id();
            $pesanan->save();

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'batalkan_pesanan',
                'tipe_objek'  => 'pesanan',
                'id_objek'    => $pesanan->id,
                'deskripsi'   => 'Membatalkan pesanan ' . $pesanan->kode_pesanan . ' dengan alasan: ' . $request->alasan_batal,
            ]);
        });

        return back()->with('sukses', 'Pesanan ' . $pesanan->kode_pesanan . ' telah dibatalkan.');
    }
}

