<?php

namespace Tests\Feature;

use App\Models\ItemPesanan;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class StrukPenyerahanTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function menyerahkan_pesanan_mengarahkan_staf_ke_struk_yang_bisa_dicetak(): void
    {
        $staf = $this->staf();
        $produk = $this->produk();
        $pesanan = $this->pesananSiapDiambil('QR-'.Str::upper(Str::random(12)));

        ItemPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama,
            'nama_varian' => 'Ice',
            'harga' => 15000,
            'jumlah' => 1,
            'subtotal' => 15000,
        ]);

        $this->actingAs($staf)
            ->post(route('panel.staf.pesanan.update-status', $pesanan->id), [
                'status_pesanan' => 'selesai',
            ])
            ->assertRedirect(route('panel.staf.pesanan.struk', $pesanan->id));

        $this->assertDatabaseHas('pesanan', [
            'id' => $pesanan->id,
            'status_pesanan' => 'selesai',
            'ditangani_oleh' => $staf->id,
        ]);

        $this->actingAs($staf)
            ->get(route('panel.staf.pesanan.struk', $pesanan->id))
            ->assertOk()
            ->assertSee('Struk Penyerahan Pesanan')
            ->assertSee($pesanan->kode_pesanan)
            ->assertSee('Pembeli Struk')
            ->assertSee($produk->nama)
            ->assertSee('Ice')
            ->assertSee('Rp 15.000')
            ->assertSee('Cetak Struk');
    }

    #[Test]
    public function terima_qr_mengembalikan_tautan_struk_penyerahan(): void
    {
        $staf = $this->staf();
        $token = 'QR-'.Str::upper(Str::random(12));
        $pesanan = $this->pesananSiapDiambil($token);

        $response = $this->actingAs($staf)
            ->postJson(route('panel.staf.pesanan.terima-qr', $pesanan->id), ['token' => $token])
            ->assertOk()
            ->assertJsonPath('valid', true)
            ->assertJsonPath('struk_url', route('panel.staf.pesanan.struk', $pesanan->id));

        $this->actingAs($staf)
            ->get($response->json('struk_url'))
            ->assertOk()
            ->assertSee($pesanan->kode_pesanan)
            ->assertSee('Cetak Struk');
    }

    #[Test]
    public function struk_penyerahan_hanya_bisa_diakses_oleh_staf_yang_masuk(): void
    {
        $pesanan = $this->pesananSiapDiambil('QR-'.Str::upper(Str::random(12)));

        $this->get(route('panel.staf.pesanan.struk', $pesanan->id))
            ->assertRedirect(route('login'));
    }

    private function staf(): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Staf Struk '.Str::upper(Str::random(5)),
            'email' => 'staf-struk-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'karyawan',
            'aktif' => true,
        ]);
    }

    private function produk(): Produk
    {
        return Produk::create([
            'nama' => 'Jus Struk '.Str::upper(Str::random(6)),
            'slug' => 'jus-struk-'.Str::lower(Str::random(8)),
            'harga' => 15000,
            'berat_gram' => 250,
            'stok' => 10,
            'aktif' => true,
        ]);
    }

    private function pesananSiapDiambil(string $token): Pesanan
    {
        return Pesanan::create([
            'kode_pesanan' => 'OMYU-STRUK-'.Str::upper(Str::random(6)),
            'token_akses' => $token,
            'jenis' => 'online',
            'nama_pembeli' => 'Pembeli Struk',
            'telepon_pembeli' => '081234567890',
            'catatan' => 'Jadwal Ambil: '.now()->addDay()->toDateString(),
            'subtotal' => 15000,
            'total' => 15000,
            'metode_bayar' => 'qris',
            'status_pembayaran' => 'lunas',
            'status_pesanan' => 'siap_diambil',
            'kedaluwarsa_pada' => now()->addDay(),
            'jadwal_ambil_selesai' => now()->addDay(),
        ]);
    }
}
