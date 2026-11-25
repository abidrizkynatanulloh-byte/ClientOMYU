<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\VarianProduk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_menambah_menu_dengan_gambar_terkompres_maksimal_seratus_kb(): void
    {
        Storage::fake('public');

        $admin = $this->buatPengguna('admin_menu_uji@omyu.test', 'admin');
        $gambar = $this->buatGambarBesar();
        $buah = $this->buatBahan('Buah Uji Unggahan', 'buah', 'g', 500);
        $gula = $this->buatBahan('Gula Uji Unggahan', 'bahan_lain', 'g', 100);
        $cup = $this->buatBahan('Cup Uji Unggahan', 'kemasan', 'pcs', 30);

        $response = $this->actingAs($admin)->post(route('panel.admin.menu.store'), [
            'nama' => 'Jus Uji Unggahan',
            'kategori_nama' => 'Jus Uji Kategori',
            'harga' => 15000,
            'gambar' => $gambar,
            'aktif' => '1',
            'resep_bahan' => [
                ['bahan_id' => $buah->id, 'takaran' => 20],
                ['bahan_id' => $gula->id, 'takaran' => 10],
                ['bahan_id' => $cup->id, 'takaran' => 1],
            ],
        ]);

        $response->assertRedirect(route('panel.admin.menu.index'));

        $produk = Produk::where('nama', 'Jus Uji Unggahan')->firstOrFail();
        $varian = VarianProduk::where('produk_id', $produk->id)->firstOrFail();
        $resep = Resep::where('varian_id', $varian->id)->firstOrFail();

        $this->actingAs($admin)
            ->get(route('panel.admin.menu.edit', $produk->id))
            ->assertOk();

        Storage::disk('public')->assertExists($produk->gambar);
        $this->assertGreaterThan(100 * 1024, filesize($gambar->getRealPath()));
        $this->assertLessThanOrEqual(100 * 1024, Storage::disk('public')->size($produk->gambar));
        $this->assertSame('Reguler', $varian->nama);
        $this->assertSame(15000, $varian->harga);
        $this->assertSame('Jus Uji Kategori', $produk->kategori?->nama);
        $this->assertSame(1, $resep->versi);
        $this->assertSame(3, $resep->bahanList()->count());
        $this->assertSame(10, $produk->stok, 'Gula menjadi pembatas: 100gr / 10gr = 10 cup.');
    }

    public function test_menyimpan_resep_baru_membuat_versi_baru_dan_menyesuaikan_stok(): void
    {
        $admin = $this->buatPengguna('admin_resep_uji@omyu.test', 'admin');
        $gula = $this->buatBahan('Gula Resep Uji', 'bahan_lain', 'g', 100);
        $cup = $this->buatBahan('Cup Resep Uji', 'kemasan', 'pcs', 50);

        $this->actingAs($admin)->post(route('panel.admin.menu.store'), [
            'nama' => 'Jus Resep Uji',
            'harga' => 12000,
            'aktif' => '1',
            'resep_bahan' => [
                ['bahan_id' => $gula->id, 'takaran' => 10],
                ['bahan_id' => $cup->id, 'takaran' => 1],
            ],
        ])->assertRedirect(route('panel.admin.menu.index'));

        $produk = Produk::where('nama', 'Jus Resep Uji')->firstOrFail();
        $resepLama = Resep::where('varian_id', $produk->varian->first()->id)->firstOrFail();

        $this->actingAs($admin)->put(route('panel.admin.menu.update', $produk->id), [
            'nama' => 'Jus Resep Uji',
            'harga' => 13000,
            'aktif' => '1',
            'resep_bahan' => [
                ['bahan_id' => $gula->id, 'takaran' => 20],
                ['bahan_id' => $cup->id, 'takaran' => 1],
            ],
        ])->assertRedirect(route('panel.admin.menu.index'));

        $resepBaru = Resep::where('varian_id', $produk->varian->first()->id)
            ->where('aktif', 1)
            ->firstOrFail();

        $this->assertSame(1, $resepLama->fresh()->versi);
        $this->assertFalse((bool) $resepLama->fresh()->aktif);
        $this->assertSame(2, $resepBaru->versi);
        $this->assertSame(5, $produk->fresh()->stok);
    }

    public function test_menu_tanpa_resep_ditolak(): void
    {
        $admin = $this->buatPengguna('admin_tanpa_resep@omyu.test', 'admin');

        $this->actingAs($admin)->post(route('panel.admin.menu.store'), [
            'nama' => 'Jus Tanpa Resep Uji',
            'harga' => 12000,
            'aktif' => '1',
        ])->assertSessionHasErrors('resep_bahan');

        $this->assertDatabaseMissing('produk', ['nama' => 'Jus Tanpa Resep Uji']);
    }

    public function test_karyawan_tidak_dapat_mengakses_halaman_tambah_menu(): void
    {
        $karyawan = $this->buatPengguna('karyawan_menu_uji@omyu.test', 'karyawan');

        $this->actingAs($karyawan)
            ->get(route('panel.admin.menu.create'))
            ->assertForbidden();
    }

    public function test_katalog_menampilkan_gambar_menu_yang_diunggah(): void
    {
        $produk = Produk::create([
            'nama' => 'Jus Uji Gambar Katalog',
            'slug' => 'jus-uji-gambar-katalog-'.Str::lower(Str::random(6)),
            'harga' => 12000,
            'stok' => 5,
            'gambar' => 'menu/contoh-gambar.webp',
            'aktif' => 1,
        ]);

        $this->get(route('pelanggan.katalog'))
            ->assertOk()
            ->assertSee('storage/menu/contoh-gambar.webp');
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

    private function buatPengguna(string $email, string $peran): Pengguna
    {
        return Pengguna::firstOrCreate(
            ['email' => $email],
            [
                'nama' => 'Pengguna Uji Menu',
                'password' => 'password123',
                'peran' => $peran,
                'aktif' => 1,
            ]
        );
    }

    private function buatGambarBesar(): UploadedFile
    {
        $gambar = imagecreatetruecolor(1600, 1200);
        $benih = 12345;

        for ($y = 0; $y < 1200; $y += 4) {
            for ($x = 0; $x < 1600; $x += 4) {
                $benih = ($benih * 1103515245 + 12345) & 0x7FFFFFFF;
                $merah = ($benih >> 16) & 255;
                $benih = ($benih * 1103515245 + 12345) & 0x7FFFFFFF;
                $hijau = ($benih >> 16) & 255;
                $benih = ($benih * 1103515245 + 12345) & 0x7FFFFFFF;
                $biru = ($benih >> 16) & 255;

                $warna = imagecolorallocate($gambar, $merah, $hijau, $biru);
                imagefilledrectangle($gambar, $x, $y, $x + 3, $y + 3, $warna);
            }
        }

        $path = tempnam(sys_get_temp_dir(), 'omyu-uji-').'.png';
        imagepng($gambar, $path);
        imagedestroy($gambar);

        return new UploadedFile($path, 'gambar-menu-uji.png', 'image/png', null, true);
    }
}
