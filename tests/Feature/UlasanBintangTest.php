<?php

namespace Tests\Feature;

use App\Models\ItemPesanan;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class UlasanBintangTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function katalog_menampilkan_bintang_nol_dan_nol_ulasan_untuk_menu_baru(): void
    {
        $produk = $this->produk('Jus Ulasan Baru '.Str::upper(Str::random(6)));

        $this->get(route('pelanggan.katalog', ['cari' => $produk->nama]))
            ->assertOk()
            ->assertSee('☆☆☆☆☆', false)
            ->assertSeeText('0,0')
            ->assertSeeText('0 ulasan');
    }

    #[Test]
    public function pelanggan_menyimpan_penilaian_untuk_menu_di_pesanan_yang_sudah_selesai(): void
    {
        $pelanggan = $this->pelanggan();
        $produk = $this->produk('Jus Ulasan Selesai '.Str::upper(Str::random(6)));
        $pesanan = $this->pesananSelesai($produk, $pelanggan);

        $this->actingAs($pelanggan, 'pelanggan')
            ->post(route('pelanggan.ulasan.simpan'), [
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk->id,
                'bintang' => 4,
                'komentar' => 'Segar sekali',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('ulasan', [
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'pelanggan_id' => $pelanggan->id,
            'bintang' => 4,
            'komentar' => 'Segar sekali',
        ]);

        $this->get(route('pelanggan.katalog', ['cari' => $produk->nama]))
            ->assertOk()
            ->assertSee('★★★★☆', false)
            ->assertSeeText('4,0')
            ->assertSeeText('1 ulasan');
    }

    #[Test]
    public function rata_rata_bintang_dan_jumlah_ulasan_menyesuaikan_penilaian_berbagai_pelanggan(): void
    {
        $produk = $this->produk('Jus Ulasan Rata Rata '.Str::upper(Str::random(6)));

        foreach ([2, 5] as $bintang) {
            $pelanggan = $this->pelanggan();
            $pesanan = $this->pesananSelesai($produk, $pelanggan);

            $this->actingAs($pelanggan, 'pelanggan')
                ->post(route('pelanggan.ulasan.simpan'), [
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $produk->id,
                    'bintang' => $bintang,
                ])
                ->assertRedirect();
        }

        $this->assertSame(2, Ulasan::where('produk_id', $produk->id)->count());

        $this->get(route('pelanggan.katalog', ['cari' => $produk->nama]))
            ->assertOk()
            ->assertSeeText('3,5')
            ->assertSeeText('2 ulasan');
    }

    #[Test]
    public function penilaian_ulang_memperbarui_bintang_tanpa_menambah_jumlah_ulasan(): void
    {
        $pelanggan = $this->pelanggan();
        $produk = $this->produk('Jus Ulasan Ulang '.Str::upper(Str::random(6)));
        $pesanan = $this->pesananSelesai($produk, $pelanggan);
        $payload = [
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
        ];

        $this->actingAs($pelanggan, 'pelanggan')
            ->post(route('pelanggan.ulasan.simpan'), $payload + ['bintang' => 5])
            ->assertRedirect();

        $this->actingAs($pelanggan, 'pelanggan')
            ->post(route('pelanggan.ulasan.simpan'), $payload + ['bintang' => 2])
            ->assertRedirect();

        $this->assertSame(1, Ulasan::where('produk_id', $produk->id)->count());

        $this->get(route('pelanggan.katalog', ['cari' => $produk->nama]))
            ->assertOk()
            ->assertSeeText('2,0')
            ->assertSeeText('1 ulasan');
    }

    #[Test]
    public function pelanggan_tidak_dapat_menilai_menu_yang_tidak_ada_di_pesanan_selesai(): void
    {
        $pelanggan = $this->pelanggan();
        $produkDipesan = $this->produk('Jus Ulasan Dipesan '.Str::upper(Str::random(6)));
        $produkLain = $this->produk('Jus Ulasan Lain '.Str::upper(Str::random(6)));
        $pesanan = $this->pesananSelesai($produkDipesan, $pelanggan);

        $this->actingAs($pelanggan, 'pelanggan')
            ->post(route('pelanggan.ulasan.simpan'), [
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produkLain->id,
                'bintang' => 5,
            ])
            ->assertRedirect()
            ->assertSessionHas('galat');

        $this->assertDatabaseMissing('ulasan', [
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produkLain->id,
        ]);
    }

    #[Test]
    public function bintang_di_luar_rentang_satu_sampai_lima_ditolak(): void
    {
        $pelanggan = $this->pelanggan();
        $produk = $this->produk('Jus Ulasan Validasi '.Str::upper(Str::random(6)));
        $pesanan = $this->pesananSelesai($produk, $pelanggan);

        $this->actingAs($pelanggan, 'pelanggan')
            ->post(route('pelanggan.ulasan.simpan'), [
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk->id,
                'bintang' => 6,
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('bintang');

        $this->assertDatabaseMissing('ulasan', ['produk_id' => $produk->id]);
    }

    #[Test]
    public function penilaian_guest_memakai_token_perangkat_yang_memesan(): void
    {
        $token = str_repeat('a', 40);
        $produk = $this->produk('Jus Ulasan Guest '.Str::upper(Str::random(6)));
        $pesanan = $this->pesananSelesai($produk, null, $token);

        $this->withCookie('omyu_device_token', $token)
            ->post(route('pelanggan.ulasan.simpan'), [
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk->id,
                'bintang' => 3,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('ulasan', [
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'pelanggan_id' => null,
            'device_token' => $token,
            'bintang' => 3,
        ]);
    }

    #[Test]
    public function halaman_lacak_menampilkan_form_penilaian_untuk_pesanan_selesai(): void
    {
        $token = str_repeat('b', 40);
        $produk = $this->produk('Jus Ulasan Lacak '.Str::upper(Str::random(6)));
        $this->pesananSelesai($produk, null, $token);

        $this->withCookie('omyu_device_token', $token)
            ->get(route('pelanggan.lacak'))
            ->assertOk()
            ->assertSeeText('Beri Bintang untuk Menu Kamu')
            ->assertSee(route('pelanggan.ulasan.simpan'), false)
            ->assertSee('name="bintang"', false);
    }

    private function produk(string $nama): Produk
    {
        return Produk::create([
            'nama' => $nama,
            'slug' => Str::lower(Str::random(10)).'-'.Str::lower(Str::random(6)),
            'harga' => 15000,
            'stok' => 10,
            'aktif' => true,
        ]);
    }

    private function pelanggan(): Pelanggan
    {
        return Pelanggan::create([
            'nama' => 'Pelanggan Ulasan '.Str::upper(Str::random(5)),
            'telepon' => '08'.Str::random(10),
            'password' => 'rahasia123',
        ]);
    }

    private function pesananSelesai(Produk $produk, ?Pelanggan $pelanggan, ?string $deviceToken = null): Pesanan
    {
        $pesanan = Pesanan::create([
            'kode_pesanan' => 'ULS'.Str::upper(Str::random(8)),
            'token_akses' => hash('sha256', Str::random(32)),
            'pelanggan_id' => $pelanggan?->id,
            'device_token' => $deviceToken,
            'jenis' => 'online',
            'nama_pembeli' => $pelanggan?->nama ?? 'Pelanggan Guest',
            'telepon_pembeli' => $pelanggan?->telepon ?? '081234567890',
            'subtotal' => $produk->harga,
            'total' => $produk->harga,
            'metode_bayar' => 'qris',
            'status_pembayaran' => 'lunas',
            'status_pesanan' => 'selesai',
        ]);

        ItemPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama,
            'harga' => $produk->harga,
            'jumlah' => 1,
            'subtotal' => $produk->harga,
        ]);

        return $pesanan;
    }
}
