<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = Kategori::where('aktif', 1)->get();

        $query = Produk::where('aktif', 1);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->cari.'%')
                    ->orWhere('barcode', 'like', '%'.$request->cari.'%');
            });
        }

        $daftarProduk = $query->orderBy('nama', 'asc')->get();

        return view('panel.staf.kasir.index', compact('daftarProduk', 'kategoriList'));
    }

    public function cariBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:64',
        ]);

        $barcode = trim($request->barcode);
        $produk = Produk::where('barcode', $barcode)->first();

        if (! $produk) {
            return response()->json([
                'found' => false,
                'pesan' => 'Produk dengan barcode '.$barcode.' tidak ditemukan.',
            ], 404);
        }

        if (! $produk->aktif) {
            return response()->json([
                'found' => false,
                'pesan' => 'Produk '.$produk->nama.' sedang tidak aktif.',
            ], 422);
        }

        if ($produk->stok < 1) {
            return response()->json([
                'found' => false,
                'pesan' => 'Stok '.$produk->nama.' tidak mencukupi.',
            ], 422);
        }

        return response()->json([
            'found' => true,
            'produk' => [
                'id' => $produk->id,
                'nama' => $produk->nama,
                'harga' => $produk->harga,
                'stok' => $produk->stok,
                'barcode' => $produk->barcode,
                'gambar' => $produk->gambar ? asset('storage/'.$produk->gambar) : null,
            ],
        ]);
    }

    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'metode_bayar' => 'required|in:tunai,qris,transfer',
            'uang_diterima' => 'nullable|numeric|min:0',
            'nama_pembeli' => 'nullable|string|max:100',
            'telepon_pembeli' => 'nullable|string|max:20',
            'catatan' => 'nullable|string|max:255',
            'referensi_bayar' => 'required_if:metode_bayar,qris,transfer|nullable|string|max:100',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|integer|min:1',
        ], [
            'items.required' => 'Pilih minimal satu menu untuk transaksi kasir.',
            'items.min' => 'Pilih minimal satu menu untuk transaksi kasir.',
            'referensi_bayar.required_if' => 'Referensi pembayaran wajib diisi untuk QRIS/transfer.',
        ]);

        $itemsDiminta = collect($request->items)
            ->groupBy('id')
            ->map(function ($group, $id) {
                return ['id' => (int) $id, 'qty' => (int) $group->sum('qty')];
            })
            ->values()
            ->all();

        $buktiPath = null;

        if ($request->hasFile('bukti_bayar')) {
            $buktiPath = $request->file('bukti_bayar')->store('bukti-pembayaran', 'public');
        }

        $pesananId = null;
        $totalAkhir = 0;
        $kembalianAkhir = 0;

        try {
            DB::transaction(function () use (
                $request,
                $itemsDiminta,
                $buktiPath,
                &$pesananId,
                &$totalAkhir,
                &$kembalianAkhir
            ) {
                $produkList = Produk::whereIn('id', collect($itemsDiminta)->pluck('id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $itemsData = [];
                $subtotal = 0;

                foreach ($itemsDiminta as $item) {
                    $produk = $produkList->get($item['id']);
                    $qty = $item['qty'];

                    if (! $produk || ! $produk->aktif) {
                        throw new RuntimeException('Produk tidak ditemukan atau sedang tidak aktif.');
                    }

                    if ($produk->stok < $qty) {
                        throw new RuntimeException("Stok untuk {$produk->nama} tidak mencukupi (Tersedia: {$produk->stok}, Diminta: {$qty}).");
                    }

                    $lineTotal = $produk->harga * $qty;
                    $subtotal += $lineTotal;
                    $itemsData[] = [
                        'produk' => $produk,
                        'jumlah' => $qty,
                        'harga' => $produk->harga,
                        'subtotal' => $lineTotal,
                    ];
                }

                $total = $subtotal;
                $metodeBayar = $request->metode_bayar;
                $uangDiterima = $metodeBayar === 'tunai'
                    ? (float) $request->uang_diterima
                    : (float) $total;
                $kembalian = max(0, $uangDiterima - $total);

                if ($metodeBayar === 'tunai' && $uangDiterima < $total) {
                    throw new RuntimeException('Uang tunai yang diterima kurang dari total pembayaran.');
                }

                do {
                    $kodePesanan = 'OMYU-POS-'.date('Ymd').'-'.strtoupper(Str::random(6));
                } while (Pesanan::withTrashed()->where('kode_pesanan', $kodePesanan)->exists());

                $pesanan = Pesanan::create([
                    'kode_pesanan' => $kodePesanan,
                    'token_akses' => hash('sha256', Str::random(64)),
                    'jenis' => 'langsung',
                    'nama_pembeli' => $request->filled('nama_pembeli') ? $request->nama_pembeli : 'Pelanggan Kasir',
                    'telepon_pembeli' => $request->filled('telepon_pembeli') ? $request->telepon_pembeli : '-',
                    'email_pembeli' => null,
                    'alamat_pengiriman' => 'Penjualan Langsung Kasir (Takeaway/Dine-In)',
                    'catatan' => $request->catatan,
                    'subtotal' => $subtotal,
                    'ongkir' => 0,
                    'total' => $total,
                    'metode_bayar' => $metodeBayar,
                    'kode_unik' => 0,
                    'status_pembayaran' => 'lunas',
                    'status_pesanan' => 'selesai',
                    'kedaluwarsa_pada' => now()->addDay(),
                    'dibayar_pada' => now(),
                    'ditangani_oleh' => auth()->id(),
                    'uang_diterima' => $uangDiterima,
                    'kembalian' => $kembalian,
                ]);

                $pesananId = $pesanan->id;
                $totalAkhir = $total;
                $kembalianAkhir = $kembalian;

                foreach ($itemsData as $data) {
                    $produk = $data['produk'];
                    $qty = $data['jumlah'];

                    $varian = $produk->varian()->where('aktif', 1)->first();
                    $resep = $varian
                        ? Resep::with('bahanList')->where('varian_id', $varian->id)->where('aktif', 1)->first()
                        : null;

                    $hppBahanPerCup = 0;
                    $hppKemasanPerCup = 0;

                    if ($resep && $resep->bahanList->isNotEmpty()) {
                        foreach ($resep->bahanList as $bahanResep) {
                            $bahan = Bahan::whereKey($bahanResep->bahan_id)->lockForUpdate()->first();

                            if (! $bahan) {
                                continue;
                            }

                            $takaran = (float) $bahanResep->pivot->takaran;
                            $kebutuhan = $takaran * $qty;
                            $biayaSatuan = (float) $bahan->biaya_rata_rata;
                            $biaya = $kebutuhan * $biayaSatuan;

                            if ($bahan->jenis === 'kemasan') {
                                $hppKemasanPerCup += $takaran * $biayaSatuan;
                            } else {
                                $hppBahanPerCup += $takaran * $biayaSatuan;
                            }

                            if ($kebutuhan <= 0) {
                                continue;
                            }

                            if ($bahan->stok_tersedia < $kebutuhan) {
                                throw new RuntimeException("Stok bahan {$bahan->nama} tidak mencukupi untuk transaksi ini.");
                            }

                            $stokSesudah = (float) $bahan->stok - $kebutuhan;
                            $bahan->update(['stok' => $stokSesudah]);
                            $bahan->catatPergerakan(
                                'pemakaian_resep',
                                -$kebutuhan,
                                $stokSesudah,
                                'pesanan',
                                $pesanan->id,
                                auth()->id(),
                                'Penjualan POS '.$kodePesanan,
                                $biaya
                            );
                        }
                    }

                    ItemPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $produk->id,
                        'varian_id' => $varian?->id,
                        'nama_produk' => $produk->nama,
                        'nama_varian' => $varian?->nama ?? 'Reguler',
                        'harga' => $data['harga'],
                        'jumlah' => $qty,
                        'subtotal' => $data['subtotal'],
                        'resep_id' => $resep?->id,
                        'hpp_bahan' => round($hppBahanPerCup, 2),
                        'hpp_kemasan' => round($hppKemasanPerCup, 2),
                    ]);

                    $stokSebelum = $produk->stok;
                    $stokSesudah = $stokSebelum - $qty;
                    $produk->update(['stok' => $stokSesudah]);

                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'pengguna_id' => auth()->id(),
                        'pesanan_id' => $pesanan->id,
                        'jenis' => 'keluar_pesanan',
                        'jumlah' => -$qty,
                        'stok_setelah' => $stokSesudah,
                        'catatan' => 'Penjualan kasir POS: '.$kodePesanan,
                    ]);
                }

                Pembayaran::create([
                    'pesanan_id' => $pesanan->id,
                    'gerbang' => 'kasir',
                    'referensi_gerbang' => 'POS-'.strtoupper(uniqid()),
                    'metode' => $metodeBayar,
                    'bukti_path' => $buktiPath,
                    'jumlah' => $total,
                    'status' => 'lunas',
                    'dibayar_pada' => now(),
                    'diverifikasi_oleh' => auth()->id(),
                    'diverifikasi_pada' => now(),
                    'catatan_verifikasi' => 'Diverifikasi langsung oleh kasir POS',
                    'data_mentah' => [
                        'uang_diterima' => $uangDiterima,
                        'kembalian' => $kembalian,
                        'kasir' => auth()->user()->nama,
                        'referensi_bayar' => $request->referensi_bayar,
                    ],
                ]);

                LogAktivitas::create([
                    'pengguna_id' => auth()->id(),
                    'aksi' => 'penjualan_langsung',
                    'tipe_objek' => 'pesanan',
                    'id_objek' => $pesanan->id,
                    'deskripsi' => 'Kasir '.auth()->user()->nama.' melayani penjualan langsung '.$kodePesanan.' total Rp '.number_format($total, 0, ',', '.'),
                    'ip_address' => $request->ip(),
                    'nilai_sesudah' => ['total' => $total, 'metode_bayar' => $metodeBayar],
                ]);
            });
        } catch (RuntimeException $exception) {
            if ($buktiPath) {
                Storage::disk('public')->delete($buktiPath);
            }

            return back()->with('error', $exception->getMessage())->withInput();
        }

        return redirect()->route('panel.staf.kasir.struk', $pesananId)
            ->with('sukses', 'Transaksi kasir berhasil disimpan!')
            ->with('kembalian', $kembalianAkhir);
    }

    public function struk($id)
    {
        $pesanan = Pesanan::with(['itemPesanan.produk', 'pembayaran', 'penangan'])->findOrFail($id);

        return view('panel.staf.kasir.struk', compact('pesanan'));
    }
}
