<?php

namespace App\Http\Controllers;

use App\Models\CadanganBahan;
use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\RiwayatStok;
use App\Models\VarianProduk;
use App\Support\KapasitasMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PelangganPesananController extends Controller
{
    public function __construct(private KapasitasMenu $kapasitasMenu) {}

    private function pelangganAktif(): ?Pelanggan
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        return $pelanggan instanceof Pelanggan ? $pelanggan : null;
    }

    private function deviceToken(Request $request): string
    {
        $token = (string) $request->cookie('omyu_device_token', '');
        if (! preg_match('/\A[a-f0-9]{40,80}\z/', $token)) {
            $token = hash('sha256', Str::random(64));
            Cookie::queue(Cookie::make(
                'omyu_device_token',
                $token,
                60 * 24 * 365,
                '/',
                null,
                $request->isSecure(),
                true,
                false,
                'Lax'
            ));
        }

        return $token;
    }

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
        $itemBaru = [
            'produk_id' => $produk->id,
            'varian_id' => $varian?->id,
            'nama_produk' => $produk->nama,
            'nama_varian' => $varian?->nama ?? 'Reguler',
            'harga' => $harga,
            'qty' => (int) $request->qty,
            'catatan' => $request->catatan ?? '-',
        ];

        if ($request->boolean('langsung')) {
            // "Pesan Langsung" hanya checkout item ini, tanpa menggabungkan keranjang lama.
            session()->put('checkout_langsung', [$cartKey => $itemBaru]);

            return redirect()->route('pelanggan.checkout', ['mode' => 'langsung'])
                ->with('sukses', "{$produk->nama} siap dipesan langsung!");
        }

        if (isset($keranjang[$cartKey])) {
            $keranjang[$cartKey]['qty'] += $request->qty;
            if (! empty($request->catatan)) {
                $keranjang[$cartKey]['catatan'] = $request->catatan;
            }
        } else {
            $keranjang[$cartKey] = $itemBaru;
        }

        session()->put('keranjang', $keranjang);

        return back()->with('sukses', "{$produk->nama} berhasil ditambahkan ke keranjang!");
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
    public function checkout(Request $request)
    {
        $modeLangsung = $request->query('mode') === 'langsung';
        $keranjang = $modeLangsung
            ? session()->get('checkout_langsung', [])
            : session()->get('keranjang', []);

        if (empty($keranjang)) {
            if ($modeLangsung) {
                return redirect()->route('pelanggan.katalog')
                    ->with('galat', 'Item pesan langsung tidak ditemukan. Silakan pilih menu lagi.');
            }

            return redirect()->route('pelanggan.katalog')
                ->with('galat', 'Keranjang Anda masih kosong. Silakan pilih menu jus terlebih dahulu.');
        }

        $totalHarga = 0;
        foreach ($keranjang as $item) {
            $totalHarga += ($item['harga'] * $item['qty']);
        }

        $pelanggan = $this->pelangganAktif();

        return view('pelanggan.checkout', [
            'keranjang' => $keranjang,
            'totalHarga' => $totalHarga,
            'modeCheckout' => $modeLangsung ? 'langsung' : 'keranjang',
            'pelanggan' => $pelanggan,
        ]);
    }

    /**
     * P-05: Proses Simpan Pesanan Online & Cadangkan Bahan Baku (Bab 2.9)
     */
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'nullable|string|max:100',
            'telepon_pembeli' => 'nullable|string|max:20',
            'tanggal_ambil' => 'required|date|after_or_equal:today',
            'jam_ambil' => 'required|string|max:30',
            'metode_bayar' => 'required|in:qris,transfer,bayar_ditempat',
            'catatan' => 'nullable|string|max:255',
            'mode_checkout' => 'nullable|in:keranjang,langsung',
        ]);

        $modeCheckout = $request->input('mode_checkout', 'keranjang');
        $keranjang = $modeCheckout === 'langsung'
            ? session()->get('checkout_langsung', [])
            : session()->get('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('pelanggan.katalog')->with('galat',
                $modeCheckout === 'langsung'
                    ? 'Item pesan langsung tidak ditemukan. Silakan pilih menu lagi.'
                    : 'Keranjang belanja kosong.'
            );
        }

        $subtotal = 0;
        foreach ($keranjang as $item) {
            $subtotal += ($item['harga'] * $item['qty']);
        }

        $pesananBaru = null;

        $pelanggan = $this->pelangganAktif();
        $deviceToken = $this->deviceToken($request);

        $namaPembeli = $pelanggan?->nama ?? trim((string) $request->input('nama_pembeli', ''));
        $teleponPembeli = preg_replace('/\D+/', '', (string) ($pelanggan?->telepon ?? $request->input('telepon_pembeli', '')));

        if ($namaPembeli === '' || $teleponPembeli === '') {
            return back()->withErrors(['nama_pembeli' => 'Nama dan nomor WhatsApp wajib diisi.'])->withInput();
        }

        DB::transaction(function () use ($request, $keranjang, $subtotal, &$pesananBaru, $pelanggan, $deviceToken, $namaPembeli, $teleponPembeli) {
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
                'device_token' => $pelanggan ? null : $deviceToken,
                'pelanggan_id' => $pelanggan?->id,
                'jenis' => 'online',
                'nama_pembeli' => $namaPembeli,
                'telepon_pembeli' => $teleponPembeli,
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
                $kebutuhanCadangan = [];

                if ($resep && $resep->bahanList->isNotEmpty()) {
                    foreach ($resep->bahanList as $bahan) {
                        $takaran = (float) $bahan->pivot->takaran;
                        $biaya = $takaran * (float) $bahan->biaya_rata_rata;

                        if ($bahan->jenis === 'kemasan') {
                            $hppKemasan += $biaya;
                        } else {
                            $hppBahan += $biaya;
                        }

                        $bahanId = (int) $bahan->id;
                        $kebutuhanCadangan[$bahanId] = ($kebutuhanCadangan[$bahanId] ?? 0) + $takaran * $qty;
                    }
                }

                CadanganBahan::catatPesanan($pesananBaru->id, $kebutuhanCadangan);

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

                // Kurangi stok display produk dan catat agar tidak dipotong dua kali saat produksi
                if ($produk) {
                    $stokSesudah = max(0, (int) $produk->stok - $qty);
                    Produk::whereKey($produk->id)->update(['stok' => $stokSesudah]);
                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'pesanan_id' => $pesananBaru->id,
                        'jenis' => 'keluar_pesanan',
                        'jumlah' => -$qty,
                        'stok_setelah' => $stokSesudah,
                        'catatan' => 'Pesanan online '.$kodePesanan,
                    ]);
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
                'deskripsi' => "Pesanan online baru dibuat: {$kodePesanan} oleh {$namaPembeli} total Rp ".number_format($subtotal, 0, ',', '.'),
            ]);
        });

        $this->kapasitasMenu->sinkronkan();

        // Bersihkan session sesuai mode checkout
        if ($modeCheckout === 'langsung') {
            session()->forget('checkout_langsung');
        } else {
            session()->forget('keranjang');
        }

        session()->put('kode_pesanan_terakhir', $pesananBaru->kode_pesanan);

        return redirect()->route('pelanggan.pembayaran', $pesananBaru->kode_pesanan)
            ->with('sukses', 'Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * P-06 & P-07: Halaman Instruksi Pembayaran & Konfirmasi
     */
    private function temukanPesananUntukAkses(Request $request, string $kode): Pesanan
    {
        $query = Pesanan::where('kode_pesanan', $kode);
        $pelanggan = $this->pelangganAktif();
        $deviceToken = $this->deviceToken($request);

        $query->where(function ($q) use ($pelanggan, $deviceToken) {
            if ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id)
                    ->orWhere(function ($dq) use ($deviceToken) {
                        $dq->whereNull('pelanggan_id')->where('device_token', $deviceToken);
                    });
            } else {
                $q->whereNull('pelanggan_id')->where('device_token', $deviceToken);
            }
        });

        return $query->firstOrFail();
    }

    public function halamanPembayaran(Request $request, $kode)
    {
        $pesanan = $this->temukanPesananUntukAkses($request, $kode);
        $pesanan->load(['itemPesanan', 'pembayaran']);

        return view('pelanggan.pembayaran', compact('pesanan'));
    }

    /**
     * P-06: Pembeli Menekan Tombol "Saya Sudah Bayar" / Upload Bukti
     */
    public function konfirmasiBayar(Request $request, $kode)
    {
        $pesanan = $this->temukanPesananUntukAkses($request, $kode);

        $request->validate([
            'bukti_transfer' => 'required|image|max:3072', // wajib upload bukti pembayaran
        ], [
            'bukti_transfer.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_transfer.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_transfer.max' => 'Ukuran bukti pembayaran maksimal 3MB.',
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
        $kode = trim((string) $request->query('kode', ''));
        $pelanggan = $this->pelangganAktif();
        $deviceToken = $this->deviceToken($request);
        $daftarPesanan = collect();

        $query = Pesanan::with(['itemPesanan.produk', 'pembayaran']);

        if ($pelanggan) {
            $query->where(function ($q) use ($pelanggan, $deviceToken) {
                $q->where('pelanggan_id', $pelanggan->id)
                    ->orWhere(function ($dq) use ($deviceToken) {
                        $dq->whereNull('pelanggan_id')->where('device_token', $deviceToken);
                    });
            });
        } else {
            $query->whereNull('pelanggan_id')->where('device_token', $deviceToken);
        }

        if ($kode !== '') {
            $query->where(function ($q) use ($kode) {
                $q->where('kode_pesanan', 'like', "%{$kode}%")
                    ->orWhere('nama_pembeli', 'like', "%{$kode}%")
                    ->orWhere('telepon_pembeli', 'like', "%{$kode}%");
            });
        }

        $daftarPesanan = $query->latest()->limit(20)->get();

        if ($request->boolean('partial')) {
            return view('pelanggan._lacak-list', compact('daftarPesanan', 'kode'));
        }

        return view('pelanggan.lacak', compact('daftarPesanan', 'kode'));
    }
}
