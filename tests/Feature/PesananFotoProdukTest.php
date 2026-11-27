<?php

namespace Tests\Feature;

use App\Models\Produk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class PesananFotoProdukTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function checkout_menampilkan_foto_produk_yang_dipesan(): void
    {
        $gambar = 'menu/jus-belimbing-test-'.Str::lower(Str::random(6)).'.webp';
        $produk = $this->produkBelimbing($gambar);

        $this->post(route('pelanggan.keranjang.tambah'), [
            'produk_id' => $produk->id,
            'qty' => 1,
            'langsung' => 1,
        ])->assertRedirect(route('pelanggan.checkout', ['mode' => 'langsung']));

        $this->get(route('pelanggan.checkout', ['mode' => 'langsung']))
            ->assertOk()
            ->assertSee(asset('storage/'.$gambar), false)
            ->assertDontSee('photo-1622597467836-f3285f2131b8', false);
    }

    #[Test]
    public function checkout_menampilkan_foto_produk_untuk_sesi_yang_sudah_ada(): void
    {
        $gambar = 'menu/jus-belimbing-lama-'.Str::lower(Str::random(6)).'.webp';
        $produk = $this->produkBelimbing($gambar);

        $this->withSession([
            'checkout_langsung' => [
                $produk->id.'_0' => [
                    'produk_id' => $produk->id,
                    'nama_produk' => $produk->nama,
                    'harga' => $produk->harga,
                    'qty' => 1,
                    'catatan' => '-',
                ],
            ],
        ])->get(route('pelanggan.checkout', ['mode' => 'langsung']))
            ->assertOk()
            ->assertSee(asset('storage/'.$gambar), false)
            ->assertDontSee('photo-1622597467836-f3285f2131b8', false);
    }

    #[Test]
    public function keranjang_menampilkan_foto_produk_yang_dipesan(): void
    {
        $gambar = 'menu/jus-belimbing-keranjang-'.Str::lower(Str::random(6)).'.webp';
        $produk = $this->produkBelimbing($gambar);

        $this->post(route('pelanggan.keranjang.tambah'), [
            'produk_id' => $produk->id,
            'qty' => 1,
        ])->assertRedirect();

        $this->get(route('pelanggan.keranjang'))
            ->assertOk()
            ->assertSee(asset('storage/'.$gambar), false)
            ->assertDontSee('photo-1622597467836-f3285f2131b8', false);
    }

    #[Test]
    public function katalog_menampilkan_jumlah_stok_yang_tersedia_pada_card_produk(): void
    {
        $nama = 'Jus Belimbing Stok '.Str::upper(Str::random(6));

        $this->produkBelimbing('menu/jus-belimbing-stok.webp', [
            'nama' => $nama,
            'stok' => 7,
        ]);

        $this->get(route('pelanggan.katalog', ['cari' => $nama]))
            ->assertOk()
            ->assertSeeText('Stok: 7 cup');
    }

    /**
     * @param  array<string, mixed>  $atribut
     */
    private function produkBelimbing(string $gambar, array $atribut = []): Produk
    {
        return Produk::create(array_merge([
            'nama' => 'Jus Belimbing',
            'slug' => 'jus-belimbing-'.Str::lower(Str::random(8)),
            'harga' => 15000,
            'stok' => 10,
            'gambar' => $gambar,
            'aktif' => true,
        ], $atribut));
    }
}
