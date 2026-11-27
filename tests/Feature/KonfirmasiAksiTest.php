<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class KonfirmasiAksiTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function form_simpan_admin_memiliki_konfirmasi(): void
    {
        $admin = $this->pengguna('admin');
        $produk = $this->produk();
        $bahan = $this->bahan();
        $karyawan = $this->pengguna('karyawan');

        $this->actingAs($admin)
            ->get(route('panel.admin.menu.create'))
            ->assertOk()
            ->assertSee('data-confirm="Simpan menu baru ini dan tampilkan di katalog?"', false)
            ->assertSee('data-confirm-title="Simpan Menu Baru?"', false);

        $this->actingAs($admin)
            ->get(route('panel.admin.menu.edit', $produk->id))
            ->assertOk()
            ->assertSee('data-confirm="Simpan perubahan pada menu '.$produk->nama.'?"', false)
            ->assertSee('data-confirm-title="Simpan Perubahan Menu?"', false);

        $this->actingAs($admin)
            ->get(route('panel.admin.bahan.edit', $bahan->id))
            ->assertOk()
            ->assertSee('data-confirm="Simpan perubahan data bahan '.$bahan->nama.'?"', false);

        $this->actingAs($admin)
            ->get(route('panel.admin.karyawan.edit', $karyawan->id))
            ->assertOk()
            ->assertSee('data-confirm="Simpan perubahan akun '.$karyawan->nama.'?"', false)
            ->assertSee('data-confirm-title="Perbarui Akun Staf?"', false);
    }

    #[Test]
    public function aksi_pesanan_staf_memiliki_konfirmasi(): void
    {
        $staf = $this->pengguna('karyawan');
        $pesanan = $this->pesanan('menunggu_verifikasi');

        $this->actingAs($staf)
            ->get(route('panel.beranda'))
            ->assertOk()
            ->assertSee('data-confirm="Apakah Anda yakin ingin mengaktifkan pesanan #'.$pesanan->kode_pesanan.' dan melanjutkan ke produksi?"', false)
            ->assertSee('data-confirm-title="Konfirmasi Pesanan?"', false)
            ->assertSee('data-confirm-btn="Ya, Konfirmasi"', false);

        $this->actingAs($staf)
            ->get(route('panel.staf.pesanan.index'))
            ->assertOk()
            ->assertSee('data-confirm="Apakah Anda yakin ingin menutup pesanan #'.$pesanan->kode_pesanan.' karena pembayarannya ditolak?"', false)
            ->assertSee('data-confirm="Apakah Anda yakin ingin mengaktifkan pesanan #'.$pesanan->kode_pesanan.' setelah pembayarannya dikonfirmasi?"', false)
            ->assertSee('data-confirm-type="danger"', false);
    }

    #[Test]
    public function popup_verifikasi_pesanan_memiliki_pesan_dan_tombol_yang_jelas(): void
    {
        $staf = $this->pengguna('karyawan');
        $pesanan = $this->pesanan('menunggu_verifikasi');

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'gerbang' => 'qris',
            'metode' => 'qris',
            'jumlah' => $pesanan->total,
            'status' => 'menunggu',
            'bukti_path' => 'bukti/'.Str::lower(Str::random(8)).'.png',
        ]);

        $this->actingAs($staf)
            ->get(route('panel.staf.pesanan.detail', $pesanan->id))
            ->assertOk()
            ->assertSee('data-confirm="Apakah Anda yakin ingin mengaktifkan pesanan #'.$pesanan->kode_pesanan.' setelah pembayarannya diverifikasi?"', false)
            ->assertSee('data-confirm-title="Verifikasi Pembayaran?"', false)
            ->assertSee('data-confirm-btn="Ya, Verifikasi"', false);
    }

    #[Test]
    public function aksi_stok_dan_profil_staf_memiliki_konfirmasi(): void
    {
        $staf = $this->pengguna('karyawan');

        $this->actingAs($staf)
            ->get(route('panel.staf.stok.index'))
            ->assertOk()
            ->assertSee('data-confirm="Simpan pasokan masuk ini ke gudang dan catat uang keluar kas?"', false)
            ->assertSee('data-confirm-title="Simpan Pasokan?"', false)
            ->assertSee('data-confirm="Catat pembuangan bahan ini dan kurangi stok gudang?"', false);

        $this->actingAs($staf)
            ->get(route('panel.staf.profil.index'))
            ->assertOk()
            ->assertSee('data-confirm="Simpan foto profil baru ini?"', false)
            ->assertSee('data-confirm="Perbarui kata sandi akun ini sekarang?"', false)
            ->assertSee('data-confirm-title="Perbarui Kata Sandi?"', false);

        $this->actingAs($staf)
            ->get(route('panel.staf.pesanan.scan'))
            ->assertOk()
            ->assertSee('Konfirmasi Pengambilan?', false)
            ->assertSee('window.bukaKonfirmasiModal', false);
    }

    private function pengguna(string $peran): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Konfirmasi '.ucfirst($peran).' '.Str::upper(Str::random(5)),
            'email' => 'konfirmasi-'.$peran.'-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => $peran,
            'aktif' => true,
        ]);
    }

    private function produk(): Produk
    {
        return Produk::create([
            'nama' => 'Menu Konfirmasi '.Str::upper(Str::random(6)),
            'slug' => 'menu-konfirmasi-'.Str::lower(Str::random(8)),
            'harga' => 12000,
            'berat_gram' => 250,
            'stok' => 10,
            'aktif' => true,
        ]);
    }

    private function bahan(): Bahan
    {
        return Bahan::create([
            'nama' => 'Bahan Konfirmasi '.Str::upper(Str::random(6)),
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

    private function pesanan(string $status): Pesanan
    {
        return Pesanan::create([
            'kode_pesanan' => 'OMYU-KONF-'.Str::upper(Str::random(6)),
            'token_akses' => Str::random(32),
            'jenis' => 'online',
            'nama_pembeli' => 'Pembeli Konfirmasi',
            'telepon_pembeli' => '081234567890',
            'subtotal' => 15000,
            'total' => 15000,
            'metode_bayar' => 'qris',
            'status_pembayaran' => 'menunggu',
            'status_pesanan' => $status,
            'kedaluwarsa_pada' => now()->addDay(),
        ]);
    }
}
