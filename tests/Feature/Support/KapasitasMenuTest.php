<?php

namespace Tests\Feature\Support;

use App\Models\Bahan;
use App\Models\CadanganBahan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\VarianProduk;
use App\Support\KapasitasMenu;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class KapasitasMenuTest extends TestCase
{
    use DatabaseTransactions;

    public function test_menghitung_kapasitas_dengan_bahan_paling_sedikit_sebagai_pembatas(): void
    {
        $buah = $this->buatBahan('Buah Kapasitas Uji', 'buah', 'g', 500);
        $gula = $this->buatBahan('Gula Kapasitas Uji', 'bahan_lain', 'g', 100);
        $cup = $this->buatBahan('Cup Kapasitas Uji', 'kemasan', 'pcs', 30);
        $produk = $this->buatProdukDenganResep('Jus Kapasitas Uji', [
            $buah->id => 20,
            $gula->id => 10,
            $cup->id => 1,
        ]);

        $hasil = (new KapasitasMenu)->hitung();

        $this->assertSame(10, $hasil['kapasitas'][$produk->id]);
        $this->assertSame('Gula Kapasitas Uji', $hasil['pembatas'][$produk->id]);
    }

    public function test_membagi_bahan_yang_dipakai_bersama_secara_proporsional(): void
    {
        $gula = $this->buatBahan('Gula Bersama Uji', 'bahan_lain', 'g', 100);
        $cup = $this->buatBahan('Cup Bersama Uji', 'kemasan', 'pcs', 100);
        $menuPertama = $this->buatProdukDenganResep('Jus Bersama Satu', [$gula->id => 10, $cup->id => 1]);
        $menuKedua = $this->buatProdukDenganResep('Jus Bersama Dua', [$gula->id => 10, $cup->id => 1]);

        $hasil = (new KapasitasMenu)->hitung();

        $this->assertSame(5, $hasil['kapasitas'][$menuPertama->id]);
        $this->assertSame(5, $hasil['kapasitas'][$menuKedua->id]);
    }

    public function test_sinkronisasi_menimpa_stok_produk_dengan_hasil_hitungan(): void
    {
        $gula = $this->buatBahan('Gula Sinkron Uji', 'bahan_lain', 'g', 100);
        $cup = $this->buatBahan('Cup Sinkron Uji', 'kemasan', 'pcs', 50);
        $produk = $this->buatProdukDenganResep('Jus Sinkron Uji', [$gula->id => 10, $cup->id => 1]);
        $produk->update(['stok' => 99]);

        (new KapasitasMenu)->sinkronkan();

        $this->assertSame(10, $produk->fresh()->stok);
    }

    public function test_pembatalan_pesanan_melepaskan_cadangan_bahan(): void
    {
        $gula = $this->buatBahan('Gula Cadangan Uji', 'bahan_lain', 'g', 100);
        $pesanan = $this->buatPesanan();

        CadanganBahan::catatPesanan($pesanan->id, [$gula->id => 20]);

        $this->assertSame(20.0, (float) $gula->fresh()->stok_dicadangkan);

        $pesanan->kembalikanStokPesanan(0);

        $this->assertSame(0.0, (float) $gula->fresh()->stok_dicadangkan);
        $this->assertSame('dilepas', CadanganBahan::firstOrFail()->status);
    }

    private function buatBahan(string $nama, string $jenis, string $satuanDasar, float $stok): Bahan
    {
        return Bahan::create([
            'nama' => $nama,
            'jenis' => $jenis,
            'satuan_dasar' => $satuanDasar,
            'satuan_beli' => $satuanDasar,
            'konversi' => 1,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'stok' => $stok,
            'stok_dicadangkan' => 0,
            'biaya_rata_rata' => 1000,
            'aktif' => 1,
        ]);
    }

    /**
     * @param  array<int, float>  $takaran  jumlah per bahan_id untuk 1 cup
     */
    private function buatProdukDenganResep(string $nama, array $takaran): Produk
    {
        $produk = Produk::create([
            'nama' => $nama,
            'slug' => Str::slug($nama).'-'.Str::lower(Str::random(6)),
            'harga' => 12000,
            'stok' => 0,
            'aktif' => 1,
        ]);

        $varian = VarianProduk::create([
            'produk_id' => $produk->id,
            'nama' => 'Reguler',
            'harga' => $produk->harga,
            'aktif' => 1,
        ]);

        $resep = Resep::create([
            'varian_id' => $varian->id,
            'versi' => 1,
            'berlaku_mulai' => now(),
            'aktif' => 1,
        ]);

        foreach ($takaran as $bahanId => $jumlah) {
            $resep->bahanList()->attach($bahanId, ['takaran' => $jumlah]);
        }

        return $produk;
    }

    private function buatPesanan(): Pesanan
    {
        return Pesanan::create([
            'kode_pesanan' => 'OMYU-UJI-'.Str::upper(Str::random(6)),
            'token_akses' => Str::random(64),
            'jenis' => 'online',
            'nama_pembeli' => 'Pembeli Uji',
            'subtotal' => 12000,
            'total' => 12000,
            'status_pembayaran' => 'menunggu',
            'status_pesanan' => 'menunggu_pembayaran',
        ]);
    }
}
