<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class SoftRefreshTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    #[DataProvider('halamanPublik')]
    public function halaman_publik_memuat_pembaruan_otomatis(string $rute): void
    {
        $url = str_starts_with($rute, '/') ? $rute : route($rute);

        $this->get($url)
            ->assertOk()
            ->assertSee('js/soft-refresh.js', false);
    }

    #[Test]
    public function panel_admin_memuat_pembaruan_otomatis(): void
    {
        $admin = Pengguna::create([
            'nama' => 'Admin Refresh '.Str::upper(Str::random(5)),
            'email' => 'admin-refresh-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'admin',
            'aktif' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('panel.admin.beranda'))
            ->assertOk()
            ->assertSee('js/soft-refresh.js', false);
    }

    #[Test]
    public function panel_staf_memuat_pembaruan_otomatis(): void
    {
        $staf = Pengguna::create([
            'nama' => 'Staf Refresh '.Str::upper(Str::random(5)),
            'email' => 'staf-refresh-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'karyawan',
            'aktif' => true,
        ]);

        $this->actingAs($staf)
            ->get(route('panel.beranda'))
            ->assertOk()
            ->assertSee('js/soft-refresh.js', false);
    }

    #[Test]
    public function halaman_scan_qr_staf_tidak_memuat_pembaruan_otomatis(): void
    {
        $staf = Pengguna::create([
            'nama' => 'Staf Scan Refresh '.Str::upper(Str::random(5)),
            'email' => 'staf-scan-refresh-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'karyawan',
            'aktif' => true,
        ]);

        $this->actingAs($staf)
            ->get(route('panel.staf.pesanan.scan'))
            ->assertOk()
            ->assertDontSee('js/soft-refresh.js', false)
            ->assertSee('window.OMYU_SOFT_REFRESH = false', false);
    }

    public static function halamanPublik(): array
    {
        return [
            'beranda' => ['/'],
            'login staf' => ['login'],
            'login pelanggan' => ['pelanggan.login'],
            'katalog' => ['pelanggan.katalog'],
        ];
    }
}
