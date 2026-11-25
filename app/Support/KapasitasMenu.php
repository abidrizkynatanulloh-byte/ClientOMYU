<?php

namespace App\Support;

use App\Models\Bahan;
use App\Models\Produk;
use App\Models\Resep;
use Illuminate\Support\Facades\DB;

class KapasitasMenu
{
    /**
     * Hitung jumlah cup per menu dari resep aktif dan stok bahan yang tersedia.
     *
     * Bahan yang dipakai bersama dibagi proporsional: bila total pemakaian
     * melebihi stok, alokasi semua menu yang memakainya diskalakan bersama.
     *
     * @return array{kapasitas: array<int, int>, pembatas: array<int, string|null>}
     */
    public function hitung(): array
    {
        $bahanList = Bahan::query()->lockForUpdate()->get()->keyBy('id');
        $tersedia = $bahanList->mapWithKeys(fn (Bahan $bahan): array => [
            $bahan->id => $bahan->aktif
                ? max(0, (float) $bahan->stok - (float) $bahan->stok_dicadangkan)
                : 0.0,
        ]);

        $menu = [];
        $kapasitasAwal = [];

        $produkList = Produk::with('varian.resepAktif.bahanList')
            ->where('aktif', 1)
            ->orderBy('id')
            ->get();

        foreach ($produkList as $produk) {
            $resep = null;

            foreach ($produk->varian as $varian) {
                if ($varian->resepAktif && $varian->resepAktif->bahanList->isNotEmpty()) {
                    $resep = $varian->resepAktif;
                    break;
                }
            }

            if (! $resep instanceof Resep) {
                continue;
            }

            $kebutuhan = [];
            $kapasitasMenu = null;

            foreach ($resep->bahanList as $bahanResep) {
                $bahanId = (int) $bahanResep->id;
                $takaran = (float) $bahanResep->pivot->takaran;

                if ($takaran <= 0) {
                    continue;
                }

                $kebutuhan[$bahanId] = $takaran;
                $kapasitasBahan = (int) floor(($tersedia[$bahanId] ?? 0) / $takaran);
                $kapasitasMenu = min($kapasitasMenu ?? $kapasitasBahan, $kapasitasBahan);
            }

            if ($kebutuhan === []) {
                continue;
            }

            $menu[$produk->id] = $kebutuhan;
            $kapasitasAwal[$produk->id] = max(0, $kapasitasMenu ?? 0);
        }

        $alokasi = $kapasitasAwal;

        while (true) {
            $penggunaan = $this->penggunaanBahan($menu, $alokasi);
            $bahanPelanggar = null;

            foreach ($penggunaan as $bahanId => $total) {
                if ($total > ($tersedia[$bahanId] ?? 0) + 0.000001) {
                    $bahanPelanggar = (int) $bahanId;
                    break;
                }
            }

            if ($bahanPelanggar === null) {
                break;
            }

            $faktor = ($tersedia[$bahanPelanggar] ?? 0) / max($penggunaan[$bahanPelanggar], 0.000001);
            $berubah = false;

            foreach ($menu as $produkId => $kebutuhan) {
                if (! isset($kebutuhan[$bahanPelanggar]) || $alokasi[$produkId] <= 0) {
                    continue;
                }

                $alokasiBaru = (int) floor($alokasi[$produkId] * $faktor);

                if ($alokasiBaru >= $alokasi[$produkId]) {
                    $alokasiBaru = $alokasi[$produkId] - 1;
                }

                $alokasi[$produkId] = max(0, $alokasiBaru);
                $berubah = true;
            }

            if (! $berubah) {
                break;
            }
        }

        $penggunaanAkhir = $this->penggunaanBahan($menu, $alokasi);
        $pembatas = [];

        foreach ($menu as $produkId => $kebutuhan) {
            $pembatas[$produkId] = null;

            if ($alokasi[$produkId] <= 0) {
                continue;
            }

            foreach ($kebutuhan as $bahanId => $takaran) {
                if (($tersedia[$bahanId] ?? 0) - $penggunaanAkhir[$bahanId] <= $takaran + 0.000001) {
                    $pembatas[$produkId] = $bahanList->get($bahanId)?->nama;
                    break;
                }
            }
        }

        return [
            'kapasitas' => $alokasi,
            'pembatas' => $pembatas,
        ];
    }

    /**
     * Timpa stok semua menu yang punya resep dengan kapasitas hasil hitungan.
     *
     * @return array{kapasitas: array<int, int>, pembatas: array<int, string|null>}
     */
    public function sinkronkan(): array
    {
        return DB::transaction(function (): array {
            $hasil = $this->hitung();

            foreach ($hasil['kapasitas'] as $produkId => $jumlahCup) {
                Produk::whereKey($produkId)->update(['stok' => $jumlahCup]);
            }

            return $hasil;
        });
    }

    /**
     * @param  array<int, array<int, float>>  $menu
     * @param  array<int, int>  $alokasi
     * @return array<int, float>
     */
    private function penggunaanBahan(array $menu, array $alokasi): array
    {
        $penggunaan = [];

        foreach ($menu as $produkId => $kebutuhan) {
            foreach ($kebutuhan as $bahanId => $takaran) {
                $penggunaan[$bahanId] = ($penggunaan[$bahanId] ?? 0) + $takaran * $alokasi[$produkId];
            }
        }

        return $penggunaan;
    }
}
