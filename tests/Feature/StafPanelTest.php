<?php

namespace Tests\Feature;

use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class StafPanelTest extends TestCase
{
    use DatabaseTransactions;

    protected Pengguna $staf;
    protected Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staf = Pengguna::firstOrCreate(
            ['email' => 'staf_uji@omyu.test'],
            [
                'nama'     => 'Staf Uji Kasir',
                'password' => bcrypt('password123'),
                'peran'    => 'karyawan',
                'aktif'    => 1,
                'telepon'  => '08123456789',
            ]
        );

        $kategori = Kategori::firstOrCreate(
            ['slug' => 'jus-uji'],
            ['nama' => 'Jus Uji', 'aktif' => 1]
        );

        $this->produk = Produk::firstOrCreate(
            ['slug' => 'jus-melon-uji'],
            [
                'kategori_id' => $kategori->id,
                'nama'        => 'Jus Melon Uji',
                'harga'       => 12000,
                'stok'        => 20,
                'aktif'       => 1,
            ]
        );
    }

    public function test_tamu_tidak_bisa_mengakses_panel_staf()
    {
        $response = $this->get(route('panel.beranda'));
        $response->assertRedirect(route('login'));
    }

    public function test_staf_dapat_mengakses_dashboard_operasional()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.beranda'));
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Antrean');
        $response->assertSee('Bahan Baku Segar');
        $response->assertSee('Antrean Prioritas Cepat');
        $response->assertSee('Aksi Cepat Barista');
        $response->assertSee('Menerima Pesanan');
        $response->assertSee('bottom-nav');
    }

    public function test_staf_dapat_melakukan_transaksi_kasir_pos()
    {
        $stokAwal = $this->produk->fresh()->stok;
        $qtyBeli = 2;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.kasir.simpan'), [
            'metode_bayar'  => 'tunai',
            'uang_diterima' => 50000,
            'nama_pembeli'  => 'Budi Pembeli',
            'items'         => [
                [
                    'id'  => $this->produk->id,
                    'qty' => $qtyBeli,
                ]
            ],
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals(26000, session('kembalian')); // 50000 - 24000 = 26000

        // Cek stok terpotong
        $this->assertEquals($stokAwal - $qtyBeli, $this->produk->fresh()->stok);

        // Cek pesanan tercatat selesai
        $pesanan = Pesanan::latest()->first();
        $this->assertEquals('selesai', $pesanan->status_pesanan);
        $this->assertEquals('lunas', $pesanan->status_pembayaran);
        $this->assertEquals(24000, $pesanan->total);

        // Cek riwayat stok & log aktivitas
        $this->assertDatabaseHas('riwayat_stok', [
            'produk_id' => $this->produk->id,
            'jenis'     => 'keluar_pesanan',
            'jumlah'    => -$qtyBeli,
        ]);

        $this->assertDatabaseHas('log_aktivitas', [
            'aksi'       => 'penjualan_langsung',
            'id_objek'   => $pesanan->id,
        ]);
    }

    public function test_staf_dapat_memverifikasi_pembayaran_pesanan()
    {
        $pesanan = Pesanan::create([
            'kode_pesanan'      => 'OMYU-TEST-' . Str::random(5),
            'token_akses'       => hash('sha256', Str::random(30)),
            'nama_pembeli'      => 'Siti Pembeli',
            'telepon_pembeli'   => '08987654321',
            'alamat_pengiriman' => 'Outlet OMYU',
            'subtotal'          => 24000,
            'ongkir'            => 0,
            'total'             => 24000,
            'status_pembayaran' => 'menunggu',
            'status_pesanan'    => 'menunggu_verifikasi',
            'kedaluwarsa_pada'  => now()->addHours(2),
        ]);

        $response = $this->actingAs($this->staf)->post(route('panel.staf.pesanan.verifikasi-bayar', $pesanan->id));
        $response->assertSessionHas('sukses');

        $this->assertEquals('lunas', $pesanan->fresh()->status_pembayaran);
        $this->assertNotNull($pesanan->fresh()->dibayar_pada);
    }

    public function test_staf_dapat_memproses_dan_menyelesaikan_pesanan()
    {
        $stokAwal = $this->produk->fresh()->stok;

        $pesanan = Pesanan::create([
            'kode_pesanan'      => 'OMYU-TEST-PROC-' . Str::random(5),
            'token_akses'       => hash('sha256', Str::random(30)),
            'nama_pembeli'      => 'Ahmad',
            'telepon_pembeli'   => '0812341234',
            'alamat_pengiriman' => 'Outlet OMYU',
            'subtotal'          => 12000,
            'ongkir'            => 0,
            'total'             => 12000,
            'status_pembayaran' => 'lunas',
            'status_pesanan'    => 'diproses',
            'kedaluwarsa_pada'  => now()->addHours(2),
        ]);

        ItemPesanan::create([
            'pesanan_id'  => $pesanan->id,
            'produk_id'   => $this->produk->id,
            'nama_produk' => $this->produk->nama,
            'harga'       => 12000,
            'jumlah'      => 1,
            'subtotal'    => 12000,
        ]);

        // Selesaikan pesanan (K-12)
        $response = $this->actingAs($this->staf)->post(route('panel.staf.pesanan.update-status', $pesanan->id), [
            'status_pesanan' => 'selesai',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals('selesai', $pesanan->fresh()->status_pesanan);

        // Pastikan stok terpotong otomatis saat status Selesai
        $this->assertEquals($stokAwal - 1, $this->produk->fresh()->stok);
    }

    public function test_staf_dapat_membatalkan_pesanan_dengan_alasan()
    {
        $pesanan = Pesanan::create([
            'kode_pesanan'      => 'OMYU-TEST-BATAL-' . Str::random(5),
            'token_akses'       => hash('sha256', Str::random(30)),
            'nama_pembeli'      => 'Rini',
            'telepon_pembeli'   => '0812341234',
            'alamat_pengiriman' => 'Outlet OMYU',
            'subtotal'          => 12000,
            'ongkir'            => 0,
            'total'             => 12000,
            'status_pembayaran' => 'menunggu',
            'status_pesanan'    => 'menunggu_verifikasi',
            'kedaluwarsa_pada'  => now()->addHours(2),
        ]);

        $response = $this->actingAs($this->staf)->post(route('panel.staf.pesanan.batal', $pesanan->id), [
            'alasan_batal' => 'Pembeli membatalkan pesanan karena salah jam ambil',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals('dibatalkan', $pesanan->fresh()->status_pesanan);
        $this->assertStringContainsString('Pembeli membatalkan pesanan', $pesanan->fresh()->catatan);
    }

    public function test_staf_dapat_menyesuaikan_stok_produk()
    {
        $stokAwal = $this->produk->fresh()->stok;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.stok.penyesuaian', $this->produk->id), [
            'tipe_aksi' => 'kurang',
            'jumlah'    => 3,
            'alasan'    => 'Buah melon busuk saat disortir pagi',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal - 3, $this->produk->fresh()->stok);

        $this->assertDatabaseHas('riwayat_stok', [
            'produk_id' => $this->produk->id,
            'jenis'     => 'penyesuaian',
            'jumlah'    => -3,
            'catatan'   => 'Buah melon busuk saat disortir pagi',
        ]);
    }

    public function test_staf_dapat_mengubah_kata_sandi_akun()
    {
        $response = $this->actingAs($this->staf)->post(route('panel.staf.profil.password'), [
            'password_lama'         => 'password123',
            'password'              => 'sandiBaru456',
            'password_confirmation' => 'sandiBaru456',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertTrue(Hash::check('sandiBaru456', $this->staf->fresh()->password));
    }

    public function test_staf_dapat_mengakses_halaman_produksi_antrean_racik()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.staf.produksi.index'));
        $response->assertStatus(200);
        $response->assertSee('Antrean Racik Jus');
        $response->assertSee('PESANAN AKTIF');
        $response->assertSee('Tampilan Kartu');
        $response->assertSee('KDS Mode');
        $response->assertSee('Blender Station 01 Aktif');
        $response->assertSee('Catat Bahan Tumpah / Gagal / gagal racik, dll');
    }

    public function test_staf_dapat_mengakses_produksi_mode_kds()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.staf.produksi.index', ['mode' => 'kds']));
        $response->assertStatus(200);
        $response->assertSee('Kitchen Display System');
        $response->assertSee('Refresh Layar');
    }

    public function test_staf_dapat_memulai_dan_menyelesaikan_racik_pesanan()
    {
        $pesanan = Pesanan::create([
            'kode_pesanan'      => 'OMY-TEST-' . rand(100, 999),
            'token_akses'       => Str::random(32),
            'nama_pembeli'      => 'Pembeli Racik Uji',
            'subtotal'          => 12000,
            'ongkir'            => 0,
            'total'             => 12000,
            'status_pembayaran' => 'lunas',
            'status_pesanan'    => 'dikonfirmasi',
            'kedaluwarsa_pada'  => now()->addHours(2),
        ]);

        // Mulai Racik
        $respMulai = $this->actingAs($this->staf)->post(route('panel.staf.produksi.mulai-racik', $pesanan->id));
        $respMulai->assertSessionHas('sukses');
        $this->assertEquals('diproses', $pesanan->fresh()->status_pesanan);

        // Selesai Racik (Pindah ke Siap Diambil)
        $respSelesai = $this->actingAs($this->staf)->post(route('panel.staf.produksi.selesai-racik', $pesanan->id));
        $respSelesai->assertSessionHas('sukses');
        $this->assertEquals('siap_diambil', $pesanan->fresh()->status_pesanan);
    }

    public function test_staf_dapat_mencatat_gagal_racik_atau_bahan_tumpah()
    {
        $stokAwal = $this->produk->fresh()->stok;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.produksi.catat-gagal'), [
            'tipe_item' => 'produk',
            'item_id'   => $this->produk->id,
            'jumlah'    => 1,
            'alasan'    => 'Blender tumpah saat penuangan',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal - 1, $this->produk->fresh()->stok);

        $this->assertDatabaseHas('riwayat_stok', [
            'produk_id' => $this->produk->id,
            'catatan'   => '[Gagal Racik/Waste] Blender tumpah saat penuangan',
        ]);
    }

    public function test_staf_dapat_mengakses_manajemen_stok_dan_bahan_segar()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.staf.stok.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Stok');
        $response->assertSee('Audit Rutin: Pukul 14:00 WIB');
        $response->assertSee('Semua Item');
        $response->assertSee('Stok Menipis');
        $response->assertSee('Habis');
        $response->assertSee('Input Pasokan / Masuk');
        $response->assertSee('Catat Pembuangan (Rusak/Busuk)');
        $response->assertSee('Bahan Baku Segar');
        $response->assertSee('Katalog Resep');
        $response->assertSee('Penggunaan');
        $response->assertSee('Kerusakan Bahan');
    }

    public function test_staf_dapat_menginput_pasokan_bahan_masuk()
    {
        $bahan = \App\Models\Bahan::firstOrCreate(
            ['nama' => 'Mangga Uji Pasokan'],
            [
                'jenis'         => 'buah',
                'satuan_dasar'  => 'g',
                'satuan_beli'   => 'kg',
                'konversi'      => 1000,
                'stok'          => 5000,
                'batas_minimum' => 3000,
                'aktif'         => 1,
            ]
        );

        $stokAwal = (float) $bahan->fresh()->stok;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.stok.pasokan'), [
            'bahan_id'     => $bahan->id,
            'jumlah_masuk' => 5, // 5 kg = 5000 g
            'satuan_input' => 'kg',
            'catatan'      => 'Pasokan Segar Pagi',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal + 5000, (float) $bahan->fresh()->stok);
    }

    public function test_staf_dapat_mencatat_pembuangan_bahan_rusak_busuk()
    {
        $bahan = \App\Models\Bahan::firstOrCreate(
            ['nama' => 'Alpukat Uji Buang'],
            [
                'jenis'         => 'buah',
                'satuan_dasar'  => 'g',
                'satuan_beli'   => 'kg',
                'konversi'      => 1000,
                'stok'          => 8000,
                'batas_minimum' => 2000,
                'aktif'         => 1,
            ]
        );

        $stokAwal = (float) $bahan->fresh()->stok;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.stok.pembuangan'), [
            'bahan_id'     => $bahan->id,
            'jumlah_buang' => 1.5, // 1.5 kg = 1500 g
            'satuan_input' => 'kg',
            'alasan'       => 'Buah busuk / berjamur saat sortiran pagi',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal - 1500, (float) $bahan->fresh()->stok);
    }

    public function test_staf_dapat_audit_dan_toggle_aktif_bahan()
    {
        $bahan = \App\Models\Bahan::firstOrCreate(
            ['nama' => 'Jeruk Uji Audit'],
            [
                'jenis'         => 'buah',
                'satuan_dasar'  => 'g',
                'satuan_beli'   => 'kg',
                'konversi'      => 1000,
                'stok'          => 3000,
                'batas_minimum' => 1000,
                'aktif'         => 1,
            ]
        );

        // Audit opname ke 4 kg (4000 g)
        $respAudit = $this->actingAs($this->staf)->post(route('panel.staf.stok.audit', $bahan->id), [
            'stok_fisik'   => 4,
            'satuan_input' => 'kg',
            'catatan'      => 'Timbangan fisik opname 14:00 WIB',
        ]);
        $respAudit->assertSessionHas('sukses');
        $this->assertEquals(4000, (float) $bahan->fresh()->stok);

        // Toggle Aktif
        $respToggle = $this->actingAs($this->staf)->post(route('panel.staf.stok.toggle-aktif', $bahan->id));
        $respToggle->assertSessionHas('sukses');
        $this->assertEquals(0, $bahan->fresh()->aktif);
    }
}
