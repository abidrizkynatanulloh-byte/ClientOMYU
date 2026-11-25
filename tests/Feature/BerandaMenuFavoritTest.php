<?php

namespace Tests\Feature;

use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class BerandaMenuFavoritTest extends TestCase
{
    use DatabaseTransactions;

    public function test_menampilkan_menu_favorit_berdasarkan_jumlah_pesanan_selesai(): void
    {
        $palingLaris = $this->buatProduk('Jus Uji Paling Laris');
        $kedua = $this->buatProduk('Jus Uji Peringkat Dua');

        $this->buatItemTerjual($palingLaris, 5, 'selesai');
        $this->buatItemTerjual($kedua, 2, 'selesai');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Menu Favorit Pelanggan');
        $response->assertSeeInOrder([$palingLaris->nama, $kedua->nama]);
        $response->assertSee('5x dipesan');
    }

    public function test_tidak_memasukkan_pesanan_belum_selesai_ke_peringkat_favorit(): void
    {
        $selesai = $this->buatProduk('Jus Uji Selesai Dipesan');
        $belumSelesai = $this->buatProduk('Jus Uji Belum Dipesan');

        $this->buatItemTerjual($selesai, 2, 'selesai');
        $this->buatItemTerjual($belumSelesai, 99, 'menunggu_pembayaran');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeInOrder([$selesai->nama, $belumSelesai->nama]);
        $response->assertDontSee('99x dipesan');
    }

    private function buatProduk(string $nama): Produk
    {
        return Produk::create([
            'nama' => $nama,
            'slug' => Str::slug($nama).'-'.Str::lower(Str::random(8)),
            'harga' => 12000,
            'stok' => 20,
            'aktif' => 1,
        ]);
    }

    private function buatItemTerjual(Produk $produk, int $jumlah, string $statusPesanan): void
    {
        $kodePesanan = 'OMYU-UJI-'.Str::upper(Str::random(10));
        $subtotal = $produk->harga * $jumlah;

        $pesanan = Pesanan::create([
            'kode_pesanan' => $kodePesanan,
            'token_akses' => hash('sha256', $kodePesanan),
            'jenis' => 'langsung',
            'nama_pembeli' => 'Pelanggan Uji',
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'status_pembayaran' => 'lunas',
            'status_pesanan' => $statusPesanan,
        ]);

        ItemPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama,
            'harga' => $produk->harga,
            'jumlah' => $jumlah,
            'subtotal' => $subtotal,
        ]);
    }
}
