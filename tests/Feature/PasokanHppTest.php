<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\VarianProduk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class PasokanHppTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    #[DataProvider('dataHargaPasokan')]
    public function pasokan_menyesuaikan_hpp_per_cup(
        ?float $totalHarga,
        float $expectedBiayaRataRata,
        string $expectedBiayaTampil,
        string $expectedHpp,
        float $expectedNilaiKasKeluar
    ): void {
        $staf = $this->staf();
        $admin = $this->admin();
        $bahan = $this->bahan();
        $varian = $this->varianDenganResep($bahan, $admin);

        $payload = [
            'bahan_id' => $bahan->id,
            'jumlah_masuk' => 1,
            'satuan_input' => 'kg',
            'catatan' => 'Supplier Buah Segar',
        ];

        if ($totalHarga !== null) {
            $payload['total_harga'] = $totalHarga;
        }

        $this->actingAs($staf)
            ->post(route('panel.staf.stok.pasokan'), $payload)
            ->assertRedirect();

        $this->assertSame($expectedNilaiKasKeluar, (float) DB::table('pergerakan_stok')
            ->where('bahan_id', $bahan->id)
            ->value('nilai'));
        $this->assertSame($expectedBiayaRataRata, (float) $bahan->fresh()->biaya_rata_rata);
        $this->assertSame($expectedBiayaRataRata, (float) DB::table('pergerakan_stok')
            ->where('bahan_id', $bahan->id)
            ->value('biaya_rata_rata_setelah'));

        $this->actingAs($admin)
            ->get(route('panel.admin.resep.detail', $varian->id))
            ->assertOk()
            ->assertSee('Rp '.$expectedBiayaTampil.' / g')
            ->assertSee('Rp '.$expectedHpp);
    }

    public static function dataHargaPasokan(): array
    {
        return [
            'harga naik' => [10000.0, 7.5, '7,50', '750', 10000.0],
            'tanpa total harga' => [null, 5.0, '5,00', '500', 5000.0],
        ];
    }

    private function staf(): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Staf HPP '.Str::upper(Str::random(5)),
            'email' => 'staf-hpp-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'karyawan',
            'aktif' => true,
            'bisa_lihat_hpp' => false,
        ]);
    }

    private function admin(): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Admin HPP',
            'email' => 'admin-hpp-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'admin',
            'aktif' => true,
            'bisa_lihat_hpp' => true,
        ]);
    }

    private function bahan(): Bahan
    {
        Bahan::query()->update(['aktif' => 0]);

        return Bahan::create([
            'nama' => 'Bahan HPP '.Str::upper(Str::random(6)),
            'jenis' => 'buah',
            'satuan_dasar' => 'g',
            'satuan_beli' => 'kg',
            'konversi' => 1000,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'stok' => 1000,
            'biaya_rata_rata' => 5,
            'aktif' => true,
        ]);
    }

    private function varianDenganResep(Bahan $bahan, Pengguna $admin): VarianProduk
    {
        $produk = Produk::create([
            'nama' => 'Jus HPP '.Str::upper(Str::random(6)),
            'slug' => 'jus-hpp-'.Str::lower(Str::random(10)),
            'harga' => 15000,
            'stok' => 0,
            'aktif' => true,
            'dibuat_oleh' => $admin->id,
        ]);

        $varian = VarianProduk::create([
            'produk_id' => $produk->id,
            'nama' => 'Regular',
            'harga' => 15000,
            'aktif' => true,
        ]);

        $resep = Resep::create([
            'varian_id' => $varian->id,
            'versi' => 1,
            'berlaku_mulai' => now(),
            'aktif' => true,
            'dibuat_oleh' => $admin->id,
        ]);

        $resep->bahanList()->sync([$bahan->id => ['takaran' => 100]]);

        return $varian;
    }
}
