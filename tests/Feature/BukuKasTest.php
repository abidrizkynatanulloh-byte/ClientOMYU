<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class BukuKasTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function buku_kas_menampilkan_pembayaran_lunas_sebagai_kas_masuk(): void
    {
        $admin = $this->admin();
        $periode = Carbon::create(2024, 1, 15);
        $pesanan = $this->pesanan('OMYU-KAS-'.Str::upper(Str::random(6)), 'lunas');
        $this->pembayaran($pesanan, $admin, 'lunas', 25000, $periode);

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas', ['bulan' => 1, 'tahun' => 2024]))
            ->assertOk()
            ->assertSee('Kas Masuk')
            ->assertSee($pesanan->kode_pesanan)
            ->assertSee('Rp 25.000')
            ->assertSee('Total Kas Masuk');
    }

    #[Test]
    public function buku_kas_tidak_menampilkan_pembayaran_yang_belum_lunas(): void
    {
        $admin = $this->admin();
        $periode = Carbon::create(2024, 1, 15);
        $pesanan = $this->pesanan('OMYU-KAS-'.Str::upper(Str::random(6)));

        $this->pembayaran($pesanan, $admin, 'menunggu', 25000, $periode);

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas', ['bulan' => 1, 'tahun' => 2024]))
            ->assertOk()
            ->assertDontSee($pesanan->kode_pesanan);
    }

    #[Test]
    public function buku_kas_menggabungkan_kas_keluar_dan_menghitung_saldo(): void
    {
        $admin = $this->admin();
        $periode = Carbon::create(2024, 1, 15);
        $pesanan = $this->pesanan('OMYU-KAS-'.Str::upper(Str::random(6)), 'lunas');
        $this->pembayaran($pesanan, $admin, 'lunas', 50000, $periode);

        $shiftId = DB::table('shift_kas')->insertGetId([
            'pengguna_id' => $admin->id,
            'dibuka_pada' => $periode,
            'modal_awal' => 0,
            'status' => 'buka',
            'created_at' => $periode,
            'updated_at' => $periode,
        ]);

        DB::table('kas_transaksi')->insert([
            'shift_id' => $shiftId,
            'jenis' => 'keluar',
            'jumlah' => 15000,
            'keterangan' => 'Belanja gelas',
            'pengguna_id' => $admin->id,
            'created_at' => $periode,
        ]);

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas', ['bulan' => 1, 'tahun' => 2024]))
            ->assertOk()
            ->assertSee('Belanja gelas')
            ->assertSee('Kas Keluar')
            ->assertSee('Rp 50.000')
            ->assertSee('Rp 15.000')
            ->assertSee('Rp 35.000');
    }

    #[Test]
    public function kas_keluar_mengikuti_total_nilai_persediaan(): void
    {
        $admin = $this->admin();

        Bahan::query()->update(['aktif' => 0]);
        DB::table('pergerakan_stok')->delete();
        Bahan::create([
            'nama' => 'Bahan Kas '.Str::upper(Str::random(6)),
            'jenis' => 'buah',
            'satuan_dasar' => 'g',
            'satuan_beli' => 'kg',
            'konversi' => 1000,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'stok' => 10,
            'biaya_rata_rata' => 1500,
            'aktif' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas'))
            ->assertOk()
            ->assertSee('Modal persediaan (stok awal / di luar catatan pasokan)')
            ->assertSee('Total Kas Keluar')
            ->assertSee('Rp 15.000');
    }

    #[Test]
    public function pasokan_staf_tercatat_sebagai_kas_keluar_dengan_pihak_dan_keperluan(): void
    {
        $staf = $this->staf();
        $bahan = $this->bahan('Jeruk Nipis '.Str::upper(Str::random(5)), 0, 1000);

        $this->actingAs($staf)
            ->post(route('panel.staf.stok.pasokan'), [
                'bahan_id' => $bahan->id,
                'jumlah_masuk' => 2,
                'satuan_input' => 'kg',
                'total_harga' => 9000,
                'catatan' => 'Supplier Buah Segar',
            ])
            ->assertRedirect();

        $this->assertSame(9000.0, (float) DB::table('pergerakan_stok')
            ->where('bahan_id', $bahan->id)
            ->value('nilai'));

        $this->actingAs($this->admin())
            ->get(route('panel.admin.keuangan.buku_kas'))
            ->assertOk()
            ->assertSee('Pasokan '.$bahan->nama)
            ->assertSee('Supplier Buah Segar')
            ->assertSee('Rp 9.000')
            ->assertSee('Untuk: '.$bahan->nama)
            ->assertSee($staf->nama)
            ->assertSee('Total Kas Keluar');
    }

    #[Test]
    public function pasokan_tanpa_total_harga_memakai_stok_kali_biaya_rata_rata(): void
    {
        $staf = $this->staf();
        $bahan = $this->bahan('Gula Pasir '.Str::upper(Str::random(5)), 0, 5);

        $this->actingAs($staf)
            ->post(route('panel.staf.stok.pasokan'), [
                'bahan_id' => $bahan->id,
                'jumlah_masuk' => 3,
                'satuan_input' => 'kg',
            ])
            ->assertRedirect();

        $this->assertSame(15000.0, (float) DB::table('pergerakan_stok')
            ->where('bahan_id', $bahan->id)
            ->value('nilai'));

        $this->actingAs($this->admin())
            ->get(route('panel.admin.keuangan.buku_kas'))
            ->assertOk()
            ->assertSee('Pasokan '.$bahan->nama)
            ->assertSee('Rp 15.000')
            ->assertSee('Total Kas Keluar');
    }

    #[Test]
    public function buku_kas_menampilkan_nama_pembeli_sebagai_sumber_kas_masuk(): void
    {
        $admin = $this->admin();
        $pesanan = $this->pesanan('OMYU-KAS-'.Str::upper(Str::random(6)), 'lunas');
        $pesanan->update(['nama_pembeli' => 'Pembeli Buah Segar']);
        $this->pembayaran($pesanan, $admin, 'lunas', 25000, now());

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas'))
            ->assertOk()
            ->assertSee('Dari: Pembeli Buah Segar')
            ->assertSee('Pembeli Buah Segar');
    }

    #[Test]
    public function buku_kas_hanya_menampilkan_transaksi_pada_bulan_yang_dipilih(): void
    {
        $admin = $this->admin();
        $bulanLalu = Carbon::create(2024, 1, 15);
        $bulanBerikutnya = Carbon::create(2024, 2, 15);

        $pesananLama = $this->pesanan('OMYU-KAS-LAMA-'.Str::upper(Str::random(4)), 'lunas');
        $this->pembayaran($pesananLama, $admin, 'lunas', 10000, $bulanLalu);

        $pesananBaru = $this->pesanan('OMYU-KAS-BARU-'.Str::upper(Str::random(4)), 'lunas');
        $this->pembayaran($pesananBaru, $admin, 'lunas', 20000, $bulanBerikutnya);

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas', [
                'bulan' => $bulanLalu->month,
                'tahun' => $bulanLalu->year,
            ]))
            ->assertOk()
            ->assertSee($pesananLama->kode_pesanan)
            ->assertDontSee($pesananBaru->kode_pesanan);
    }

    #[Test]
    public function waktu_kas_keluar_pasokan_lama_tidak_berubah_saat_laporan_dibuka(): void
    {
        $admin = $this->admin();
        $waktuLaporan = Carbon::now()->startOfMonth()->addDays(3)->setTime(15, 39, 41);

        Bahan::query()->update(['aktif' => 0]);
        DB::table('pergerakan_stok')->delete();

        $bahan = $this->bahan('Jeruk Lama '.Str::upper(Str::random(5)), 1000, 10);
        DB::table('bahan')->where('id', $bahan->id)
            ->update(['created_at' => $waktuLaporan->copy()->subMinutes(10)]);

        DB::table('pergerakan_stok')->insert([
            'bahan_id' => $bahan->id,
            'jenis' => 'pembelian',
            'jumlah' => 1000,
            'nilai' => 10000,
            'stok_setelah' => 1000,
            'biaya_rata_rata_setelah' => 10,
            'rujukan_tipe' => 'pasokan',
            'pengguna_id' => $admin->id,
            'alasan' => 'Restock Supplier',
            'created_at' => null,
        ]);

        DB::table('log_aktivitas')->insert([
            'pengguna_id' => $admin->id,
            'aksi' => 'pasokan_bahan_masuk',
            'tipe_objek' => 'bahan',
            'id_objek' => $bahan->id,
            'deskripsi' => 'Pasokan lama dicatat',
            'created_at' => $waktuLaporan,
        ]);

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas'))
            ->assertOk()
            ->assertSee('Pasokan '.$bahan->nama)
            ->assertSee($waktuLaporan->format('d/m/Y'))
            ->assertSee($waktuLaporan->format('H:i').' WIB');
    }

    #[Test]
    public function waktu_kas_keluar_modal_persediaan_tetap_mengikuti_waktu_bahan_awal(): void
    {
        $admin = $this->admin();
        $waktuStokAwal = Carbon::now()->startOfMonth()->addDays(2)->setTime(15, 29, 3);

        Bahan::query()->update(['aktif' => 0]);
        DB::table('pergerakan_stok')->delete();

        $bahan = $this->bahan('Stok Awal '.Str::upper(Str::random(5)), 10, 1500);
        DB::table('bahan')->where('id', $bahan->id)
            ->update(['created_at' => $waktuStokAwal]);

        $this->actingAs($admin)
            ->get(route('panel.admin.keuangan.buku_kas'))
            ->assertOk()
            ->assertSee('Modal persediaan (stok awal / di luar catatan pasokan)')
            ->assertSee($waktuStokAwal->format('d/m/Y'))
            ->assertSee($waktuStokAwal->format('H:i').' WIB');
    }

    private function admin(): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Admin Buku Kas',
            'email' => 'admin-kas-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'admin',
            'aktif' => true,
            'bisa_lihat_hpp' => true,
        ]);
    }

    private function pesanan(string $kode, string $statusPembayaran = 'menunggu'): Pesanan
    {
        return Pesanan::create([
            'kode_pesanan' => $kode,
            'token_akses' => 'KAS-'.Str::upper(Str::random(12)),
            'jenis' => 'online',
            'nama_pembeli' => 'Pembeli Test',
            'telepon_pembeli' => '081234567890',
            'subtotal' => 25000,
            'total' => 25000,
            'metode_bayar' => 'qris',
            'status_pembayaran' => $statusPembayaran,
            'status_pesanan' => 'dikonfirmasi',
        ]);
    }

    private function pembayaran(
        Pesanan $pesanan,
        Pengguna $admin,
        string $status,
        int $jumlah,
        Carbon $waktu
    ): Pembayaran {
        return Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'gerbang' => 'manual',
            'referensi_gerbang' => 'KAS-'.Str::upper(Str::random(8)),
            'metode' => 'qris',
            'jumlah' => $jumlah,
            'status' => $status,
            'dibayar_pada' => $waktu,
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => $waktu,
            'catatan_verifikasi' => 'Diverifikasi oleh admin',
        ]);
    }

    private function staf(): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Staf Stok '.Str::upper(Str::random(5)),
            'email' => 'stok-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'karyawan',
            'aktif' => true,
            'bisa_lihat_hpp' => false,
        ]);
    }

    private function bahan(string $nama, float $stok, float $biaya): Bahan
    {
        Bahan::query()->update(['aktif' => 0]);

        return Bahan::create([
            'nama' => $nama,
            'jenis' => 'buah',
            'satuan_dasar' => 'g',
            'satuan_beli' => 'kg',
            'konversi' => 1000,
            'rendemen_standar' => 100,
            'batas_minimum' => 0,
            'stok' => $stok,
            'biaya_rata_rata' => $biaya,
            'aktif' => true,
        ]);
    }
}
