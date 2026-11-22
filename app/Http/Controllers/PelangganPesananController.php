<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PelangganPesananController extends Controller
{
    /**
     * P-02: Katalog Menu untuk Pembeli
     */
    public function katalog(Request $request)
    {
        $kategoriList = Kategori::where('aktif', 1)->get();
        $query = Produk::with(['kategori', 'varian'])->where('aktif', 1);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%'.$request->cari.'%');
        }

        $daftarProduk = $query->orderBy('nama', 'asc')->get();

        return view('pelanggan.katalog', compact('daftarProduk', 'kategoriList'));
    }

    /**
     * P-04: Tambah Menu ke Keranjang Belanja (Session)
     */
    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'qty' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:150',
        ]);

        $produk = Produk::with('varian')->findOrFail($request->produk_id);
        $varian = $produk->varian()->first(); // Ambil varian reguler/default

        if ($produk->stok < $request->qty) {
            return back()->with('galat', "Stok {$produk->nama} tidak mencukupi (sisa {$produk->stok} cup).");
        }

        $keranjang = session()->get('keranjang', []);
        $cartKey = $produk->id.'_'.($varian?->id ?? '0');

        $harga = $varian && $varian->harga > 0 ? $varian->harga : $produk->harga;

        if (isset($keranjang[$cartKey])) {
            $keranjang[$cartKey]['qty'] += $request->qty;
            if (! empty($request->catatan)) {
                $keranjang[$cartKey]['catatan'] = $request->catatan;
            }
        } else {
            $keranjang[$cartKey] = [
                'produk_id' => $produk->id,
                'varian_id' => $varian?->id,
                'nama_produk' => $produk->nama,
                'nama_varian' => $varian?->nama ?? 'Reguler',
                'harga' => $harga,
                'qty' => (int) $request->qty,
                'catatan' => $request->catatan ?? '-',
            ];
        }

        session()->put('keranjang', $keranjang);

        return redirect()->route('pelanggan.keranjang')
            ->with('sukses', "{$produk->nama} berhasil ditambahkan ke keranjang!");
    }

    /**
     * P-04: Halaman Keranjang Belanja
     */
    public function lihatKeranjang()
    {
        $keranjang = session()->get('keranjang', []);
        $totalHarga = 0;

        foreach ($keranjang as $item) {
            $totalHarga += ($item['harga'] * $item['qty']);
        }

        return view('pelanggan.keranjang', compact('keranjang', 'totalHarga'));
    }

    /**
     * P-04: Update Qty Keranjang Belanja (+ / -)
     */
    public function updateKeranjang(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'aksi' => 'required|in:tambah,kurang',
        ]);

        $keranjang = session()->get('keranjang', []);
        $key = $request->key;

        if (isset($keranjang[$key])) {
            if ($request->aksi === 'tambah') {
                $keranjang[$key]['qty'] += 1;
            } elseif ($request->aksi === 'kurang') {
                $keranjang[$key]['qty'] -= 1;
                if ($keranjang[$key]['qty'] <= 0) {
                    unset($keranjang[$key]);
                }
            }
            session()->put('keranjang', $keranjang);
        }

        return back();
    }

    /**
     * Hapus Item dari Keranjang Belanja
     */
    public function hapusKeranjang($key)
    {
        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$key])) {
            unset($keranjang[$key]);
            session()->put('keranjang', $keranjang);
        }

        return back()->with('sukses', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * P-05: Halaman Checkout & Jadwal Pengambilan
     */
    public function checkout()
    {
        $keranjang = session()->get('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('pelanggan.katalog')
                ->with('galat', 'Keranjang Anda masih kosong. Silakan pilih menu jus terlebih dahulu.');
        }

        $totalHarga = 0;
        foreach ($keranjang as $item) {
            $totalHarga += ($item['harga'] * $item['qty']);
        }

        return view('pelanggan.checkout', compact('keranjang', 'totalHarga'));
    }

    /**
     * P-05: Proses Simpan Pesanan Online & Cadangkan Bahan Baku (Bab 2.9)
     */
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:100',
            'telepon_pembeli' => 'required|string|max:20',
            'tanggal_ambil' => 'required|date|after_or_equal:today',
            'jam_ambil' => 'required|string|max:30',
            'metode_bayar' => 'required|in:qris,transfer,bayar_ditempat',
            'catatan' => 'nullable|string|max:255',
        ]);

        $keranjang = session()->get('keranjang', []);
        if (empty($keranjang)) {
            return redirect()->route('pelanggan.katalog')->with('galat', 'Keranjang belanja kosong.');
        }

        $subtotal = 0;
        foreach ($keranjang as $item) {
            $subtotal += ($item['harga'] * $item['qty']);
        }

        $pesananBaru = null;

        DB::transaction(function () use ($request, $keranjang, $subtotal, &$pesananBaru) {
            $kodePesanan = 'OMYU-ORD-'.date('Ymd').'-'.strtoupper(Str::random(4));
            $tokenAkses = hash('sha256', Str::random(40).uniqid());

            $catatanJadwal = "Jadwal Ambil: {$request->tanggal_ambil} pukul {$request->jam_ambil}";
            if ($request->filled('catatan')) {
                $catatanJadwal .= ' | Catatan: '.$request->catatan;
            }

            // 1. Simpan Pesanan
            $pesananBaru = Pesanan::create([
                'kode_pesanan' => $kodePesanan,
                'token_akses' => $tokenAkses,
                'jenis' => 'online',
                'nama_pembeli' => $request->nama_pembeli,
                'telepon_pembeli' => $request->telepon_pembeli,
                'email_pembeli' => null,
                'alamat_pengiriman' => 'Ambil Sendiri di Outlet (Self Pickup)',
                'catatan' => $catatanJadwal,
                'subtotal' => $subtotal,
                'ongkir' => 0,
                'total' => $subtotal,
                'metode_bayar' => $request->metode_bayar,
                'status_pembayaran' => 'menunggu',
                'status_pesanan' => 'menunggu_pembayaran',
                'kedaluwarsa_pada' => now()->addHours(2), // batas bayar 2 jam
            ]);

            // 2. Simpan Item Pesanan, Hitung HPP & Cadangkan Bahan Baku (Bab 2.9)
            foreach ($keranjang as $item) {
                $produk = Produk::find($item['produk_id']);
                $qty = (int) $item['qty'];

                $varian = $item['varian_id'] ? VarianProduk::find($item['varian_id']) : null;
                $resep = $varian
                    ? Resep::with('bahanList')->where('varian_id', $varian->id)->where('aktif', 1)->first()
                    : null;

                $hppBahan = 0;
                $hppKemasan = 0;

                if ($resep && $resep->bahanList->isNotEmpty()) {
                    foreach ($resep->bahanList as $bahan) {
                        $takaran = (float) $bahan->pivot->takaran;
                        $biaya = $takaran * (float) $bahan->biaya_rata_rata;

                        if ($bahan->jenis === 'kemasan') {
                            $hppKemasan += $biaya;
                        } else {
                            $hppBahan += $biaya;
                        }

                        // Cadangkan bahan agar tidak terjual ke orang lain (Bab 2.9)
                        $kebutuhanCadangan = $takaran * $qty;
                        Bahan::where('id', $bahan->id)->increment('stok_dicadangkan', $kebutuhanCadangan);
                    }
                }

                ItemPesanan::create([
                    'pesanan_id' => $pesananBaru->id,
                    'produk_id' => $produk?->id,
                    'varian_id' => $varian?->id,
                    'nama_produk' => $item['nama_produk'],
                    'nama_varian' => $item['nama_varian'],
                    'harga' => $item['harga'],
                    'jumlah' => $qty,
                    'subtotal' => $item['harga'] * $qty,
                    'resep_id' => $resep?->id,
                    'hpp_bahan' => round($hppBahan, 2),
                    'hpp_kemasan' => round($hppKemasan, 2),
                ]);

                // Kurangi stok display produk
                if ($produk) {
                    Produk::where('id', $produk->id)->decrement('stok', $qty);
                }
            }

            // 3. Buat Catatan Pembayaran Menunggu
            Pembayaran::create([
                'pesanan_id' => $pesananBaru->id,
                'gerbang' => 'manual',
                'metode' => $request->metode_bayar,
                'jumlah' => $subtotal,
                'status' => 'menunggu',
            ]);

            // 4. Catat Log Aktivitas Sistem (K-14)
            LogAktivitas::create([
                'pengguna_id' => null,
                'aksi' => 'pesanan_online_baru',
                'tipe_objek' => 'pesanan',
                'id_objek' => $pesananBaru->id,
                'deskripsi' => "Pesanan online baru dibuat: {$kodePesanan} oleh {$request->nama_pembeli} total Rp ".number_format($subtotal, 0, ',', '.'),
            ]);
        });

        // Kosongkan keranjang belanja setelah checkout berhasil
        session()->forget('keranjang');

        return redirect()->route('pelanggan.pembayaran', $pesananBaru->kode_pesanan)
            ->with('sukses', 'Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * P-06 & P-07: Halaman Instruksi Pembayaran & Konfirmasi
     */
    public function halamanPembayaran($kode)
    {
        $pesanan = Pesanan::with(['itemPesanan', 'pembayaran'])
            ->where('kode_pesanan', $kode)
            ->firstOrFail();

        return view('pelanggan.pembayaran', compact('pesanan'));
    }

    /**
     * P-06: Pembeli Menekan Tombol "Saya Sudah Bayar" / Upload Bukti
     */
    public function konfirmasiBayar(Request $request, $kode)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode)->firstOrFail();

        $request->validate([
            'bukti_transfer' => 'nullable|image|max:3072', // max 3MB jika ada upload foto
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('bukti_bayar', 'public');
        }

        DB::transaction(function () use ($pesanan, $buktiPath) {
            // Ubah status agar muncul di menu Verifikasi Pembayaran staf (K-05)
            $pesanan->update([
                'status_pesanan' => 'menunggu_verifikasi',
            ]);

            $updatePembayaran = ['status' => 'menunggu_verifikasi'];
            if ($buktiPath) {
                $updatePembayaran['bukti_path'] = $buktiPath;
            }

            Pembayaran::where('pesanan_id', $pesanan->id)->update($updatePembayaran);

            LogAktivitas::create([
                'pengguna_id' => null,
                'aksi' => 'konfirmasi_pembayaran_pembeli',
                'tipe_objek' => 'pesanan',
                'id_objek' => $pesanan->id,
                'deskripsi' => "Pembeli {$pesanan->nama_pembeli} mengonfirmasi pembayaran untuk {$pesanan->kode_pesanan}.",
            ]);
        });

        return redirect()->route('pelanggan.lacak', ['kode' => $pesanan->kode_pesanan])
            ->with('sukses', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diverifikasi oleh staf kami.');
    }

    /**
     * P-08: Lacak Status Pesanan Real-time
     */
    public function lacakStatus(Request $request)
    {
        $pesanan = null;

        if ($request->filled('kode')) {
            $pesanan = Pesanan::with(['itemPesanan'])
                ->where('kode_pesanan', trim($request->kode))
                ->first();
        }

        return view('pelanggan.lacak', compact('pesanan'));
    }
}
