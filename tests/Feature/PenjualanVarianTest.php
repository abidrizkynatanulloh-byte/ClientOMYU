<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\ItemPesanan;
use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\VarianProduk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class PenjualanVarianTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function kasir_menyimpan_harga_dan_hpp_dari_varian_terpilih(): void
    {
        $staf = $this->staf();
        $bahan = $this->bahan();
        [$produk, $reguler, $hot, $ice] = $this->menuDenganVarian($bahan);

        $this->actingAs($staf)
            ->post(route('panel.staf.kasir.simpan'), [
                'metode_bayar' => 'tunai',
                'uang_diterima' => 30000,
                'nama_pembeli' => 'Pembeli Varian',
                'items' => [
                    ['id' => $produk->id, 'varian_id' => $hot->id, 'qty' => 1],
                    ['id' => $produk->id, 'varian_id' => $ice->id, 'qty' => 1],
                ],
            ])
            ->assertRedirect();

        $pesanan = Pesanan::where('nama_pembeli', 'Pembeli Varian')->firstOrFail();
        $items = ItemPesanan::where('pesanan_id', $pesanan->id)->orderBy('id')->get();

        $this->assertSame(27000, (int) $pesanan->total);
        $this->assertCount(2, $items);
        $this->assertSame($hot->id, (int) $items[0]->varian_id);
        $this->assertSame('Hot', $items[0]->nama_varian);
        $this->assertSame(14000, (int) $items[0]->harga);
        $this->assertSame($ice->id, (int) $items[1]->varian_id);
        $this->assertSame('Ice', $items[1]->nama_varian);
        $this->assertSame(13000, (int) $items[1]->harga);

        $this->assertSame(1000, (int) $items[0]->hpp_bahan);
        $this->assertSame(1000, (int) $items[1]->hpp_bahan);
    }

    #[Test]
    public function pelanggan_menambahkan_varian_terpilih_ke_keranjang_dan_checkout(): void
    {
        $pelanggan = $this->pelanggan();
        $bahan = $this->bahan();
        [$produk, $reguler, $hot, $ice] = $this->menuDenganVarian($bahan);

        $this->actingAs($pelanggan, 'pelanggan')
            ->post(route('pelanggan.keranjang.tambah'), [
                'produk_id' => $produk->id,
                'varian_id' => $ice->id,
                'qty' => 2,
            ])
            ->assertRedirect();

        $keranjang = session('keranjang');
        $kunci = $produk->id.'_'.$ice->id;

        $this->assertArrayHasKey($kunci, $keranjang);
        $this->assertSame($ice->id, (int) $keranjang[$kunci]['varian_id']);
        $this->assertSame('Ice', $keranjang[$kunci]['nama_varian']);
        $this->assertSame(13000, (int) $keranjang[$kunci]['harga']);

        $this->get(route('pelanggan.checkout'))
            ->assertOk()
            ->assertSee('Rp 26.000');

        $this->post(route('pelanggan.checkout.proses'), [
            'tanggal_ambil' => now()->toDateString(),
            'jam_ambil' => '12:00',
            'metode_bayar' => 'bayar_ditempat',
            'catatan' => '-',
        ])->assertRedirect();

        $pesanan = Pesanan::where('pelanggan_id', $pelanggan->id)->firstOrFail();
        $item = $pesanan->itemPesanan->first();

        $this->assertSame(26000, (int) $pesanan->total);
        $this->assertSame($ice->id, (int) $item->varian_id);
        $this->assertSame('Ice', $item->nama_varian);
        $this->assertSame(13000, (int) $item->harga);
    }

    /**
     * @return array{Produk, VarianProduk, VarianProduk, VarianProduk}
     */
    private function menuDenganVarian(Bahan $bahan): array
    {
        $produk = Produk::create([
            'nama' => 'Jus Varian '.Str::upper(Str::random(6)),
            'slug' => 'jus-varian-'.Str::lower(Str::random(6)),
            'kategori_id' => null,
            'deskripsi' => 'Menu dengan tiga varian.',
            'harga' => 12000,
            'stok' => 20,
            'berat_gram' => 250,
            'gambar' => null,
            'aktif' => true,
            'dibuat_oleh' => $this->staf()->id,
            'diubah_oleh' => $this->staf()->id,
        ]);

        $reguler = VarianProduk::create([
            'produk_id' => $produk->id,
            'nama' => 'Reguler',
            'harga' => 12000,
            'aktif' => true,
        ]);
        $hot = VarianProduk::create([
            'produk_id' => $produk->id,
            'nama' => 'Hot',
            'harga' => 14000,
            'aktif' => true,
        ]);
        $ice = VarianProduk::create([
            'produk_id' => $produk->id,
            'nama' => 'Ice',
            'harga' => 13000,
            'aktif' => true,
        ]);

        foreach ([$reguler, $hot, $ice] as $varian) {
            $resep = Resep::create([
                'varian_id' => $varian->id,
                'versi' => 1,
                'berlaku_mulai' => now(),
                'aktif' => true,
                'alasan_perubahan' => 'Resep varian penjualan',
                'dibuat_oleh' => $this->staf()->id,
            ]);

            $resep->bahanList()->sync([
                $bahan->id => ['takaran' => 100],
            ]);
        }

        return [$produk, $reguler, $hot, $ice];
    }

    private function bahan(): Bahan
    {
        Bahan::query()->update(['aktif' => 0]);

        $bahan = Bahan::create([
            'nama' => 'Bahan Penjualan '.Str::upper(Str::random(6)),
            'jenis' => 'buah',
            'satuan_dasar' => 'g',
            'satuan_beli' => 'kg',
            'konversi' => 1000,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'stok' => 5000,
            'biaya_rata_rata' => 10,
            'aktif' => true,
        ]);
        $bahan->update(['stok_dicadangkan' => 0]);

        return $bahan;
    }

    private function staf(): Pengguna
    {
        return Pengguna::where('peran', 'staf')->where('aktif', 1)->first()
            ?? Pengguna::create([
                'nama' => 'Staf Varian '.Str::upper(Str::random(5)),
                'email' => 'staf-varian-'.Str::lower(Str::random(8)).'@omyu.test',
                'password' => 'rahasia123',
                'peran' => 'karyawan',
                'aktif' => true,
            ]);
    }

    private function pelanggan(): Pelanggan
    {
        return Pelanggan::create([
            'nama' => 'Pelanggan Varian '.Str::upper(Str::random(5)),
            'email' => 'pelanggan-varian-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'telepon' => '081234567890',
        ]);
    }
}
