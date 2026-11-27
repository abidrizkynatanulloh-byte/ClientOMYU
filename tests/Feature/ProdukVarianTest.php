<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\Pengguna;
use App\Models\Produk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class ProdukVarianTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function form_tambah_menu_menampilkan_input_varian_dan_harga(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('panel.admin.menu.create'))
            ->assertOk()
            ->assertSee('Tambah Varian')
            ->assertSee('varians[0][nama]', false)
            ->assertSee('varians[0][harga]', false);
    }

    #[Test]
    public function admin_menyimpan_menu_dengan_varian_hot_dan_ice_serta_harga_masing_masing(): void
    {
        $admin = $this->admin();
        $bahan = $this->bahan();
        $nama = 'Jus Varian '.Str::upper(Str::random(6));

        $this->actingAs($admin)
            ->post(route('panel.admin.menu.store'), $this->payload($nama, $bahan, [
                ['id' => '', 'nama' => 'Reguler', 'harga' => 12000],
                ['id' => '', 'nama' => 'Hot', 'harga' => 14000],
                ['id' => '', 'nama' => 'Ice', 'harga' => 13000],
            ]))
            ->assertRedirect(route('panel.admin.menu.index'));

        $produk = Produk::where('nama', $nama)->firstOrFail();
        $varians = $produk->varian()->orderBy('id')->get();

        $this->assertSame(12000, (int) $produk->harga);
        $this->assertSame(['Reguler', 'Hot', 'Ice'], $varians->pluck('nama')->all());
        $this->assertSame([12000, 14000, 13000], array_map('intval', $varians->pluck('harga')->all()));

        foreach ($varians as $varian) {
            $resep = $varian->resepAktif;

            $this->assertNotNull($resep);
            $this->assertSame(1, $resep->bahanList()->count());
            $this->assertSame(100.0, (float) $resep->bahanList()->first()->pivot->takaran);
        }

        $varianHot = $varians->firstWhere('nama', 'Hot');

        $this->actingAs($admin)
            ->get(route('panel.admin.resep.detail', $varianHot->id))
            ->assertOk()
            ->assertSee('Rp 14.000');
    }

    #[Test]
    public function admin_memperbarui_nama_harga_dan_daftar_varian_menu(): void
    {
        $admin = $this->admin();
        $bahan = $this->bahan();
        $nama = 'Jus Edit Varian '.Str::upper(Str::random(6));

        $this->actingAs($admin)
            ->post(route('panel.admin.menu.store'), $this->payload($nama, $bahan, [
                ['id' => '', 'nama' => 'Reguler', 'harga' => 12000],
                ['id' => '', 'nama' => 'Hot', 'harga' => 14000],
                ['id' => '', 'nama' => 'Ice', 'harga' => 13000],
            ]))
            ->assertRedirect();

        $produk = Produk::where('nama', $nama)->firstOrFail();
        $variansAwal = $produk->varian()->orderBy('id')->get()->keyBy('nama');

        $this->actingAs($admin)
            ->put(route('panel.admin.menu.update', $produk->id), $this->payload($nama, $bahan, [
                ['id' => $variansAwal->get('Reguler')->id, 'nama' => 'Original', 'harga' => 12500],
                ['id' => $variansAwal->get('Hot')->id, 'nama' => 'Hot Special', 'harga' => 15000],
            ], 120))
            ->assertRedirect(route('panel.admin.menu.index'));

        $produk->refresh();
        $variansAktif = $produk->varian()->orderBy('id')->get();

        $this->assertSame(12500, (int) $produk->harga);
        $this->assertSame(['Original', 'Hot Special'], $variansAktif->pluck('nama')->all());
        $this->assertSame([12500, 15000], array_map('intval', $variansAktif->pluck('harga')->all()));
        $this->assertSoftDeleted('varian_produk', ['id' => $variansAwal->get('Ice')->id]);

        foreach ($variansAktif as $varian) {
            $this->assertSame(2, (int) $varian->resepAktif->versi);
            $this->assertSame(120.0, (float) $varian->resepAktif->bahanList()->first()->pivot->takaran);
        }
    }

    #[Test]
    public function admin_menolak_varian_dengan_nama_yang_sama(): void
    {
        $admin = $this->admin();
        $bahan = $this->bahan();
        $nama = 'Jus Duplikat '.Str::upper(Str::random(6));

        $this->actingAs($admin)
            ->post(route('panel.admin.menu.store'), $this->payload($nama, $bahan, [
                ['id' => '', 'nama' => 'Hot', 'harga' => 14000],
                ['id' => '', 'nama' => ' hot ', 'harga' => 15000],
            ]))
            ->assertRedirect()
            ->assertSessionHasErrors('varians');

        $this->assertDatabaseMissing('produk', ['nama' => $nama]);
    }

    /**
     * @param  array<int, array{id: int|string|null, nama: string, harga: int}>  $varians
     * @return array<string, mixed>
     */
    private function payload(string $nama, Bahan $bahan, array $varians, int|float $takaran = 100): array
    {
        return [
            'nama' => $nama,
            'kategori_nama' => 'Jus Buah',
            'deskripsi' => 'Jus segar dengan beberapa pilihan varian.',
            'harga' => $varians[0]['harga'],
            'berat_gram' => 250,
            'aktif' => 1,
            'varians' => $varians,
            'resep_bahan' => [
                ['bahan_id' => $bahan->id, 'takaran' => $takaran],
            ],
        ];
    }

    private function admin(): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Admin Varian '.Str::upper(Str::random(5)),
            'email' => 'admin-varian-'.Str::lower(Str::random(8)).'@omyu.test',
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
            'nama' => 'Bahan Varian '.Str::upper(Str::random(6)),
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
    }
}
