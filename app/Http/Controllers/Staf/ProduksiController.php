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
use RuntimeException;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $mode = in_array($request->get('mode'), ['kartu', 'kds'], true) ? $request->get('mode') : 'kartu';

        $pesananAktif = Pesanan::with(['itemPesanan.produk', 'pembayaran'])
            ->whereIn('status_pesanan', ['diproses', 'dikonfirmasi', 'menunggu_verifikasi'])
            ->where('status_pembayaran', 'lunas')
            ->orderByRaw("FIELD(status_pesanan, 'diproses', 'dikonfirmasi', 'menunggu_verifikasi') ASC")
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($pesanan) {
                $pesanan->durasi_menit = (int) $pesanan->created_at->diffInMinutes(now());

                return $pesanan;
            });

        $totalAktifCount = $pesananAktif->count();

        $sedangDibuat = $pesananAktif->first(function ($p) {
            return $p->status_pesanan === 'diproses';
        }) ?? $pesananAktif->first();

        $sisaAntrean = $pesananAktif->reject(function ($p) use ($sedangDibuat) {
            return $sedangDibuat && $p->id === $sedangDibuat->id;
        })->values();

        $berikutnya = $sisaAntrean->first();
        $antreanMenunggu = $sisaAntrean->slice(1)->values();

        $siapDiambil = Pesanan::with(['itemPesanan.produk'])
            ->where('status_pesanan', 'siap_diambil')
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function ($pesanan) {
                $pesanan->durasi_menit = (int) $pesanan->created_at->diffInMinutes($pesanan->updated_at ?? now());

                return $pesanan;
            });

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

        $daftarProduk = Produk::where('aktif', 1)->orderBy('nama')->get();
        $daftarBahan = Bahan::where('aktif', 1)->orderBy('nama')->get();

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

    public function mulaiRacik(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if (in_array($pesanan->status_pesanan, ['selesai', 'dibatalkan', 'kedaluwarsa', 'siap_diambil'], true)) {
            return back()->with('error', 'Pesanan tidak dapat diproses karena sudah selesai, dibatalkan, atau siap diambil.');
        }

        if ($pesanan->status_pembayaran !== 'lunas') {
            return back()->with('error', 'Pembayaran pesanan belum diverifikasi.');
        }

        if ($pesanan->status_pesanan === 'diproses') {
            return back()->with('info', 'Pesanan ini sudah sedang diracik.');
        }

        DB::transaction(function () use ($pesanan, $request) {
            $pesanan->status_pesanan = 'diproses';
            $pesanan->ditangani_oleh = auth()->id();
            $pesanan->save();

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi' => 'mulai_racik_produksi',
                'tipe_objek' => 'pesanan',
                'id_objek' => $pesanan->id,
                'deskripsi' => 'Barista '.auth()->user()->nama.' mulai meracik pesanan #'.$pesanan->kode_pesanan,
                'ip_address' => $request->ip(),
                'nilai_sesudah' => ['status_pesanan' => 'diproses'],
            ]);
        });

        return back()->with('sukses', 'Pesanan #'.$pesanan->kode_pesanan.' sekarang sedang diracik di blender.');
    }

    public function selesaiRacik(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if (! in_array($pesanan->status_pesanan, ['diproses', 'dikonfirmasi'], true)) {
            return back()->with('error', 'Pesanan harus diproses terlebih dahulu sebelum ditandai selesai.');
        }

        if ($pesanan->status_pembayaran !== 'lunas') {
            return back()->with('error', 'Pembayaran pesanan belum diverifikasi.');
        }

        try {
            DB::transaction(function () use ($pesanan, $request) {
                $pesanan->catatPenguranganStokProduksi(auth()->id());
                $pesanan->status_pesanan = 'siap_diambil';
                $pesanan->ditangani_oleh = auth()->id();
                $pesanan->save();

                LogAktivitas::create([
                    'pengguna_id' => auth()->id(),
                    'aksi' => 'selesai_racik_produksi',
                    'tipe_objek' => 'pesanan',
                    'id_objek' => $pesanan->id,
                    'deskripsi' => 'Barista '.auth()->user()->nama.' menyelesaikan racikan pesanan #'.$pesanan->kode_pesanan.' (Siap Diambil)',
                    'ip_address' => $request->ip(),
                    'nilai_sesudah' => ['status_pesanan' => 'siap_diambil'],
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('sukses', 'Pesanan #'.$pesanan->kode_pesanan.' selesai diracik dan dipindahkan ke meja Pick-Up!');
    }

    public function catatGagal(Request $request)
    {
        $request->validate([
            'tipe_item' => 'required|in:produk,bahan',
            'item_id' => 'required|integer',
            'jumlah' => 'required|numeric|min:0.001',
            'satuan' => 'nullable|string|max:10',
            'alasan' => 'required|string|min:3|max:255',
        ], [
            'alasan.required' => 'Wajib mengisi alasan bahan tumpah atau gagal racik.',
        ]);

        $alasan = $request->alasan;

        try {
            DB::transaction(function () use ($request, $alasan) {
                if ($request->tipe_item === 'produk') {
                    $jumlah = (int) $request->jumlah;

                    if ($jumlah < 1) {
                        throw new RuntimeException('Jumlah waste produk minimal 1.');
                    }

                    $produk = Produk::whereKey($request->item_id)->lockForUpdate()->firstOrFail();

                    if ($produk->stok < $jumlah) {
                        throw new RuntimeException('Stok produk '.$produk->nama.' tidak mencukupi untuk dicatat sebagai waste.');
                    }

                    $stokSesudah = $produk->stok - $jumlah;
                    $produk->update(['stok' => $stokSesudah]);

                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'pengguna_id' => auth()->id(),
                        'jenis' => 'penyesuaian',
                        'jumlah' => -$jumlah,
                        'stok_setelah' => $stokSesudah,
                        'catatan' => '[Gagal Racik/Waste] '.$alasan,
                    ]);

                    $namaItem = $produk->nama;
                } else {
                    $bahan = Bahan::whereKey($request->item_id)->lockForUpdate()->firstOrFail();
                    $jumlah = $bahan->konversiKeDasar($request->satuan ?? $bahan->satuan_dasar, (float) $request->jumlah);

                    if ($bahan->stok_tersedia < $jumlah) {
                        throw new RuntimeException('Stok bahan '.$bahan->nama.' tidak mencukupi untuk dicatat sebagai waste.');
                    }

                    $stokSesudah = (float) $bahan->stok - $jumlah;
                    $bahan->update(['stok' => $stokSesudah]);
                    $bahan->catatPergerakan('rusak', -$jumlah, $stokSesudah, 'waste', null, auth()->id(), $alasan);
                    $namaItem = $bahan->nama;
                }

                LogAktivitas::create([
                    'pengguna_id' => auth()->id(),
                    'aksi' => 'catat_waste_gagal_racik',
                    'tipe_objek' => $request->tipe_item,
                    'id_objek' => $request->item_id,
                    'deskripsi' => 'Barista '.auth()->user()->nama.' mencatat gagal racik/tumpah '.$namaItem.' sebanyak '.$request->jumlah.': '.$alasan,
                    'alasan' => $alasan,
                    'ip_address' => $request->ip(),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('sukses', 'Laporan bahan tumpah/gagal racik berhasil disimpan dan stok otomatis disesuaikan.');
    }
}
