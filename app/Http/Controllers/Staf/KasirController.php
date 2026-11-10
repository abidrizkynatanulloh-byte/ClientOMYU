<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\ItemPesanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KasirController extends Controller
{
    /**
     * K-07: Penjualan Langsung (Kasir)
     * Menampilkan antarmuka POS kasir untuk melayani pembeli langsung.
     */
    public function index(Request $request)
    {
        $kategoriList = Kategori::where('aktif', 1)->get();

        $query = Produk::where('aktif', 1);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        $daftarProduk = $query->orderBy('nama', 'asc')->get();

        return view('panel.staf.kasir.index', compact('daftarProduk', 'kategoriList'));
    }

    /**
     * Menyimpan transaksi penjualan langsung dari kasir.
     */
    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'metode_bayar'  => 'required|in:tunai,qris,transfer',
            'uang_diterima' => 'nullable|numeric|min:0',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:produk,id',
            'items.*.qty'   => 'required|integer|min:1',
        ], [
            'items.required' => 'Pilih minimal satu menu untuk transaksi kasir.',
            'items.min'      => 'Pilih minimal satu menu untuk transaksi kasir.',
        ]);

        // Cek ketersediaan stok produk sebelum memproses (Bab 11 Aturan Bisnis)
        $produkIds = collect($request->items)->pluck('id');
        $produkList = Produk::whereIn('id', $produkIds)->get()->keyBy('id');

        $subtotal = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $produk = $produkList->get($item['id']);
            $qty = (int) $item['qty'];

            if (!$produk || !$produk->aktif) {
                return back()->with('error', 'Produk tidak ditemukan atau sedang tidak aktif.');
            }

            if ($produk->stok < $qty) {
                return back()->with('error', "Stok untuk {$produk->nama} tidak mencukupi (Tersedia: {$produk->stok}, Diminta: {$qty}).");
            }

            $lineTotal = $produk->harga * $qty;
            $subtotal += $lineTotal;

            $itemsData[] = [
                'produk'    => $produk,
                'jumlah'    => $qty,
                'harga'     => $produk->harga,
                'subtotal'  => $lineTotal,
            ];
        }

        $total = $subtotal;
        $metodeBayar = $request->metode_bayar;
        $uangDiterima = (float) ($request->uang_diterima ?? $total);
        $kembalian = max(0, $uangDiterima - $total);

        if ($metodeBayar === 'tunai' && $uangDiterima < $total) {
            return back()->with('error', 'Uang tunai yang diterima kurang dari total pembayaran.');
        }

        $pesananId = null;

        DB::transaction(function () use (
            $request,
            $itemsData,
            $subtotal,
            $total,
            $metodeBayar,
            $uangDiterima,
            $kembalian,
            &$pesananId
        ) {
            $kodePesanan = 'OMYU-POS-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $tokenAkses = hash('sha256', Str::random(40) . uniqid());

            // 1. Simpan Pesanan (Langsung berstatus Selesai & Lunas)
            $pesanan = Pesanan::create([
                'kode_pesanan'      => $kodePesanan,
                'token_akses'       => $tokenAkses,
                'nama_pembeli'      => $request->filled('nama_pembeli') ? $request->nama_pembeli : 'Pelanggan Kasir',
                'telepon_pembeli'   => $request->filled('telepon_pembeli') ? $request->telepon_pembeli : '-',
                'email_pembeli'     => null,
                'alamat_pengiriman' => 'Penjualan Langsung Kasir (Takeaway/Dine-In)',
                'catatan'           => $request->catatan,
                'subtotal'          => $subtotal,
                'ongkir'            => 0,
                'total'             => $total,
                'status_pembayaran' => 'lunas',
                'status_pesanan'    => 'selesai',
                'kedaluwarsa_pada'  => now()->addDays(1),
                'dibayar_pada'      => now(),
                'ditangani_oleh'    => auth()->id(),
            ]);

            $pesananId = $pesanan->id;

            // 2. Simpan Item Pesanan, Potong Stok, & Buat Riwayat Stok
            foreach ($itemsData as $data) {
                $produk = $data['produk'];
                $qty = $data['jumlah'];

                ItemPesanan::create([
                    'pesanan_id'  => $pesanan->id,
                    'produk_id'   => $produk->id,
                    'nama_produk' => $produk->nama,
                    'harga'       => $data['harga'],
                    'jumlah'      => $qty,
                    'subtotal'    => $data['subtotal'],
                ]);

                // Kurangi stok di produk
                $stokSebelum = $produk->stok;
                $stokSesudah = max(0, $stokSebelum - $qty);
                $produk->update(['stok' => $stokSesudah]);

                // Catat riwayat stok
                RiwayatStok::create([
                    'produk_id'    => $produk->id,
                    'pengguna_id'  => auth()->id(),
                    'pesanan_id'   => $pesanan->id,
                    'jenis'        => 'keluar_pesanan',
                    'jumlah'       => -$qty,
                    'stok_setelah' => $stokSesudah,
                    'catatan'      => 'Penjualan kasir POS: ' . $pesanan->kode_pesanan,
                ]);
            }

            // 3. Simpan Catatan Pembayaran
            Pembayaran::create([
                'pesanan_id'        => $pesanan->id,
                'gerbang'           => 'kasir',
                'referensi_gerbang' => 'POS-' . uniqid(),
                'metode'            => $metodeBayar,
                'jumlah'            => $total,
                'status'            => 'lunas',
                'dibayar_pada'      => now(),
                'data_mentah'       => [
                    'uang_diterima' => $uangDiterima,
                    'kembalian'     => $kembalian,
                    'kasir'         => auth()->user()->nama,
                ],
            ]);

            // 4. Catat Log Aktivitas (K-14)
            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi'        => 'penjualan_langsung',
                'tipe_objek'  => 'pesanan',
                'id_objek'    => $pesanan->id,
                'deskripsi'   => 'Kasir ' . auth()->user()->nama . ' melayani penjualan langsung ' . $pesanan->kode_pesanan . ' total Rp ' . number_format($total, 0, ',', '.'),
            ]);
        });

        return redirect()->route('panel.staf.kasir.struk', $pesananId)
            ->with('sukses', 'Transaksi kasir berhasil disimpan!')
            ->with('kembalian', $kembalian);
    }

    /**
     * Menampilkan struk transaksi kasir yang dapat dicetak.
     */
    public function struk($id)
    {
        $pesanan = Pesanan::with(['itemPesanan.produk', 'pembayaran', 'penangan'])->findOrFail($id);

        return view('panel.staf.kasir.struk', compact('pesanan'));
    }
}

