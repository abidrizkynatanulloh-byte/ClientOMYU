<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\VarianProduk;
use Illuminate\Http\Request;

class ResepHppController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data produk beserta varian, resep aktif, dan daftar bahannya
        $query = Produk::with(['kategori', 'varian.resepAktif.bahanList'])->where('aktif', 1);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where('nama', 'like', "%{$cari}%");
        }

        $daftarProduk = $query->get();

        // 2. Hitung HPP dan Kapasitas Cup per Varian Menu
        $menuAnalisis = [];

        foreach ($daftarProduk as $produk) {
            foreach ($produk->varian as $varian) {
                $resep = $varian->resepAktif;
                $hppBahan = 0;
                $kapasitasCup = null;
                $adaResep = false;

                if ($resep && $resep->bahanList->isNotEmpty()) {
                    $adaResep = true;
                    $kapasitasPerBahan = [];

                    foreach ($resep->bahanList as $b) {
                        $takaran = (float) $b->pivot->takaran;
                        $biayaPerSatuan = (float) $b->biaya_rata_rata;

                        // Biaya bahan untuk 1 cup
                        $hppBahan += ($takaran * $biayaPerSatuan);

                        // Hitung kapasitas cup dari bahan ini
                        $stokTersedia = max(0, (float) $b->stok - (float) $b->stok_dicadangkan);
                        if ($takaran > 0) {
                            $kapasitasPerBahan[] = floor($stokTersedia / $takaran);
                        }
                    }

                    // Kapasitas menu adalah nilai terkecil dari semua bahan (Bab 2.5)
                    $kapasitasCup = !empty($kapasitasPerBahan) ? min($kapasitasPerBahan) : 0;
                }

                $hargaJual = $varian->harga > 0 ? $varian->harga : $produk->harga;
                $labaKotor = $hargaJual - $hppBahan;
                $margin = $hargaJual > 0 && $adaResep ? round(($labaKotor / $hargaJual) * 100, 1) : 0;

                $menuAnalisis[] = [
                    'produk_id'    => $produk->id,
                    'varian_id'    => $varian->id,
                    'nama_produk'  => $produk->nama,
                    'nama_varian'  => $varian->nama,
                    'kategori'     => $produk->kategori?->nama ?? '-',
                    'harga_jual'   => $hargaJual,
                    'hpp_bahan'    => round($hppBahan),
                    'laba_kotor'   => round($labaKotor),
                    'margin'       => $margin,
                    'kapasitas_cup'=> $kapasitasCup,
                    'ada_resep'    => $adaResep,
                ];
            }
        }

        return view('panel.admin.resep.index', compact('menuAnalisis'));
    }

    public function detail($varianId)
    {
        $varian = VarianProduk::with(['produk.kategori', 'resepAktif.bahanList'])->findOrFail($varianId);
        $resep = $varian->resepAktif;

        $rincianBahan = [];
        $totalHpp = 0;
        $bahanPembatas = null;
        $minKapasitas = PHP_INT_MAX;

        if ($resep && $resep->bahanList->isNotEmpty()) {
            foreach ($resep->bahanList as $b) {
                $takaran = (float) $b->pivot->takaran;
                $biayaPerSatuan = (float) $b->biaya_rata_rata;
                $biayaPerCup = $takaran * $biayaPerSatuan;
                $totalHpp += $biayaPerCup;

                $stokTersedia = max(0, (float) $b->stok - (float) $b->stok_dicadangkan);
                $kapasitas = $takaran > 0 ? floor($stokTersedia / $takaran) : 0;

                if ($kapasitas < $minKapasitas) {
                    $minKapasitas = $kapasitas;
                    $bahanPembatas = $b->nama;
                }

                $rincianBahan[] = [
                    'nama_bahan'      => $b->nama,
                    'jenis'           => $b->jenis,
                    'satuan'          => $b->satuan_dasar,
                    'takaran'         => $takaran,
                    'biaya_satuan'    => $biayaPerSatuan,
                    'biaya_per_cup'   => round($biayaPerCup),
                    'stok_tersedia'   => $stokTersedia,
                    'kapasitas_cup'   => $kapasitas,
                ];
            }
        }

        $kapasitasFinal = ($minKapasitas === PHP_INT_MAX) ? 0 : $minKapasitas;
        $hargaJual = $varian->harga > 0 ? $varian->harga : $varian->produk->harga;
        $labaKotor = $hargaJual - $totalHpp;
        $margin = $hargaJual > 0 ? round(($labaKotor / $hargaJual) * 100, 1) : 0;

        return view('panel.admin.resep.detail', compact(
            'varian',
            'resep',
            'rincianBahan',
            'totalHpp',
            'hargaJual',
            'labaKotor',
            'margin',
            'kapasitasFinal',
            'bahanPembatas'
        ));
    }
}