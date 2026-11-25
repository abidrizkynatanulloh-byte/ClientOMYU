<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminBahanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_menyimpan_harga_per_kg_sebagai_biaya_per_gram(): void
    {
        $admin = $this->buatPengguna('admin_harga_bahan@omyu.test');

        $this->actingAs($admin)->post(route('panel.admin.bahan.store'), [
            'nama' => 'Jeruk Harga Uji',
            'jenis' => 'buah',
            'satuan_dasar' => 'g',
            'satuan_beli' => 'kg',
            'konversi' => 1000,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'stok_awal' => 5000,
            'harga_beli' => 20000,
        ])->assertRedirect(route('panel.admin.bahan.index'));

        $bahan = Bahan::where('nama', 'Jeruk Harga Uji')->firstOrFail();

        $this->assertSame(20.0, (float) $bahan->biaya_rata_rata);
        $this->assertSame(20000.0, (float) $bahan->harga_beli);
    }

    public function test_form_edit_menampilkan_dan_menyimpan_harga_beli_per_pack(): void
    {
        $admin = $this->buatPengguna('admin_edit_bahan@omyu.test');
        $bahan = $this->buatBahan('Cup Harga Uji', 'kemasan', 'pcs', 'pack', 10, 500);

        $this->actingAs($admin)->get(route('panel.admin.bahan.edit', $bahan->id))
            ->assertOk()
            ->assertSee('Biaya per', false)
            ->assertSee('id="label-satuan-beli">pack</span>', false)
            ->assertSee('name="harga_beli"', false)
            ->assertSee('value="5000"', false);

        $this->actingAs($admin)->put(route('panel.admin.bahan.update', $bahan->id), [
            'nama' => 'Cup Harga Uji',
            'jenis' => 'kemasan',
            'satuan_dasar' => 'pcs',
            'satuan_beli' => 'pack',
            'konversi' => 10,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'aktif' => 1,
            'harga_beli' => 6000,
        ])->assertRedirect(route('panel.admin.bahan.index'));

        $this->assertSame(600.0, (float) $bahan->fresh()->biaya_rata_rata);
        $this->assertSame(6000.0, (float) $bahan->fresh()->harga_beli);
    }

    public function test_admin_menolak_harga_beli_negatif(): void
    {
        $admin = $this->buatPengguna('admin_harga_negatif@omyu.test');

        $this->actingAs($admin)->post(route('panel.admin.bahan.store'), [
            'nama' => 'Jeruk Harga Negatif',
            'jenis' => 'buah',
            'satuan_dasar' => 'g',
            'satuan_beli' => 'kg',
            'konversi' => 1000,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'harga_beli' => -1,
        ])->assertSessionHasErrors(['harga_beli' => 'Biaya beli tidak boleh negatif.']);

        $this->assertDatabaseMissing('bahan', ['nama' => 'Jeruk Harga Negatif']);
    }

    private function buatBahan(
        string $nama,
        string $jenis,
        string $satuanDasar,
        string $satuanBeli,
        float $konversi,
        float $biayaPerDasar
    ): Bahan {
        return Bahan::create([
            'nama' => $nama,
            'jenis' => $jenis,
            'satuan_dasar' => $satuanDasar,
            'satuan_beli' => $satuanBeli,
            'konversi' => $konversi,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'stok' => 0,
            'stok_dicadangkan' => 0,
            'biaya_rata_rata' => $biayaPerDasar,
            'aktif' => 1,
        ]);
    }

    private function buatPengguna(string $email): Pengguna
    {
        return Pengguna::firstOrCreate(
            ['email' => $email],
            [
                'nama' => 'Pengguna Uji Bahan',
                'password' => 'password123',
                'peran' => 'admin',
                'aktif' => 1,
            ]
        );
    }
}
