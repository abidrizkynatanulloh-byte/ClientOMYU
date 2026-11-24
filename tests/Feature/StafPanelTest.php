<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
                'nama' => 'Staf Uji Kasir',
                'password' => bcrypt('password123'),
                'peran' => 'karyawan',
                'aktif' => 1,
                'telepon' => '08123456789',
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
                'nama' => 'Jus Melon Uji',
                'harga' => 12000,
                'stok' => 20,
                'aktif' => 1,
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

    public function test_bottom_nav_mobile_mengganti_tab_stok_dengan_menu_lainnya()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.beranda'));

        $response->assertOk();
        $response->assertSee('id="moreBtn"', false);
        $response->assertSee('Menu Lainnya');
        $response->assertSee('class="more-label">Kasir POS</span>', false);
        $response->assertSee('class="more-label">Riwayat Aktivitas</span>', false);
        $response->assertSee('class="more-label">Stok</span>', false);
        $response->assertDontSee('<span>Stok</span>', false);
    }

    public function test_tab_menu_lainnya_aktif_untuk_rute_kasir_aktivitas_dan_stok()
    {
        $rutes = [
            route('panel.staf.kasir.index'),
            route('panel.staf.aktivitas.index'),
            route('panel.staf.stok.index'),
        ];

        foreach ($rutes as $rute) {
            $response = $this->actingAs($this->staf)->get($rute);

            $response->assertOk();
            $response->assertSee('id="moreBtn" class="bnav-tab active"', false);
        }
    }

    public function test_rute_scan_qr_pengambilan_staf_tidak_tersedia()
    {
        $this->actingAs($this->staf)->get('/panel/pesanan/scan')->assertNotFound();
        $this->actingAs($this->staf)->post('/panel/pesanan/scan/validasi', ['token' => 'token-qr'])->assertNotFound();
        $this->actingAs($this->staf)->post('/panel/pesanan/1/terima-qr', ['token' => 'token-qr'])->assertNotFound();
    }

    public function test_panel_staf_tidak_menampilkan_menu_qr_pengambilan()
    {
        $pesanan = Pesanan::create([
            'kode_pesanan' => 'OMYU-TEST-'.Str::random(5),
            'token_akses' => hash('sha256', Str::random(30)),
            'nama_pembeli' => 'Siti Pembeli',
            'telepon_pembeli' => '08987654321',
            'jenis' => 'online',
            'metode_bayar' => 'bayar_ditempat',
            'subtotal' => 18000,
            'ongkir' => 0,
            'total' => 18000,
            'status_pembayaran' => 'lunas',
            'status_pesanan' => 'siap_diambil',
            'kedaluwarsa_pada' => now()->addHours(2),
        ]);

        $dashboard = $this->actingAs($this->staf)->get(route('panel.beranda'));
        $dashboard->assertDontSee('Scan QR');
        $dashboard->assertDontSee('bukaScanQrCepat');
        $dashboard->assertDontSee('bnav-scan');

        $index = $this->actingAs($this->staf)->get(route('panel.staf.pesanan.index'));
        $index->assertDontSee('Scan QR Pengambilan');
        $index->assertDontSee('Scan QR Ambil');

        $detail = $this->actingAs($this->staf)->get(route('panel.staf.pesanan.detail', $pesanan->id));
        $detail->assertOk();
        $detail->assertDontSee('QR Pengambilan Pesanan');
        $detail->assertDontSee('Buka Scanner QR Pengambilan');
        $detail->assertDontSee('qrPesanan');
    }

    public function test_qr_pengambilan_tetap_tersedia_di_halaman_lacak_pembeli()
    {
        $deviceToken = hash('sha256', 'perangkat-pembeli-qr');

        Pesanan::create([
            'kode_pesanan' => 'OMYU-TEST-'.Str::random(5),
            'token_akses' => hash('sha256', Str::random(30)),
            'device_token' => $deviceToken,
            'nama_pembeli' => 'Pembeli QR',
            'telepon_pembeli' => '081234567890',
            'jenis' => 'online',
            'metode_bayar' => 'bayar_ditempat',
            'subtotal' => 12000,
            'ongkir' => 0,
            'total' => 12000,
            'status_pembayaran' => 'lunas',
            'status_pesanan' => 'siap_diambil',
            'kedaluwarsa_pada' => now()->addHours(2),
        ]);

        $response = $this->withCookie('omyu_device_token', $deviceToken)
            ->get(route('pelanggan.lacak'));

        $response->assertOk();
        $response->assertSee('Tunjukkan QR ini saat ambil pesanan.');
        $response->assertSee('api.qrserver.com/v1/create-qr-code', false);
    }

    public function test_dashboard_menampilkan_jam_realtime_dan_status_shift_berjalan()
    {
        $this->travelTo('2026-11-21 10:00:00');

        $response = $this->actingAs($this->staf)->get(route('panel.beranda'));

        $response->assertOk();
        $response->assertSee('Shift Pagi (07:00 - 16:00)');
        $response->assertSee('id="jamRealtime"', false);
        $response->assertSee('id="notifShiftSelesai"', false);
    }

    public function test_dashboard_menampilkan_notifikasi_shift_selesai_di_luar_jam_kerja()
    {
        $this->travelTo('2026-11-21 22:30:00');

        $response = $this->actingAs($this->staf)->get(route('panel.beranda'));

        $response->assertOk();
        $response->assertSee('Shift Selesai');
        $response->assertSee('Shift kamu sudah selesai');
        $response->assertSee('Shift berakhir pukul 22:00 WIB');
    }

    public function test_staf_dapat_melakukan_transaksi_kasir_pos()
    {
        $stokAwal = $this->produk->fresh()->stok;
        $qtyBeli = 2;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.kasir.simpan'), [
            'metode_bayar' => 'tunai',
            'uang_diterima' => 50000,
            'nama_pembeli' => 'Budi Pembeli',
            'items' => [
                [
                    'id' => $this->produk->id,
                    'qty' => $qtyBeli,
                ],
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
            'jenis' => 'keluar_pesanan',
            'jumlah' => -$qtyBeli,
        ]);

        $this->assertDatabaseHas('log_aktivitas', [
            'aksi' => 'penjualan_langsung',
            'id_objek' => $pesanan->id,
        ]);
    }

    public function test_halaman_kasir_pos_tidak_menampilkan_scan_barcode()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.staf.kasir.index'));

        $response->assertOk();
        $response->assertDontSee('Scan Barcode');
        $response->assertDontSee('cari-barcode');
        $response->assertSee('Cari nama menu');
    }

    public function test_endpoint_cari_barcode_kasir_tidak_tersedia()
    {
        $response = $this->actingAs($this->staf)->get('/panel/kasir/cari-barcode?barcode=8990000000000');

        $response->assertNotFound();
    }

    public function test_pencarian_kasir_hanya_menggunakan_nama_menu()
    {
        $this->produk->update(['barcode' => '8990000000001']);

        $response = $this->actingAs($this->staf)->get(route('panel.staf.kasir.index', ['cari' => '8990000000001']));

        $response->assertOk();
        $response->assertSee('Tidak ada menu yang sesuai dengan pencarian atau kategori ini.');
    }

    public function test_staf_mengonfirmasi_pesanan_qris_menggunakan_bukti_yang_diunggah_pembeli()
    {
        Storage::fake('public');

        $pesanan = Pesanan::create([
            'kode_pesanan' => 'OMYU-TEST-'.Str::random(5),
            'token_akses' => hash('sha256', Str::random(30)),
            'nama_pembeli' => 'Siti Pembeli',
            'telepon_pembeli' => '08987654321',
            'alamat_pengiriman' => 'Outlet OMYU',
            'jenis' => 'online',
            'metode_bayar' => 'qris',
            'subtotal' => 24000,
            'ongkir' => 0,
            'total' => 24000,
            'status_pembayaran' => 'menunggu',
            'status_pesanan' => 'menunggu_verifikasi',
            'kedaluwarsa_pada' => now()->addHours(2),
        ]);

        $pembayaran = $pesanan->pembayaran()->create([
            'gerbang' => 'manual',
            'referensi_gerbang' => 'QRIS-OMYU-TEST',
            'metode' => 'qris',
            'jumlah' => 24000,
            'status' => 'menunggu_verifikasi',
            'bukti_path' => 'bukti_bayar/bukti-qris.jpg',
        ]);

        $response = $this->actingAs($this->staf)->post(route('panel.staf.pesanan.verifikasi-bayar', $pesanan->id), [
            'catatan_verifikasi' => 'Bukti QRIS sesuai mutasi',
            'bukti_bayar' => UploadedFile::fake()->create('bukti-staf.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHas('sukses');

        $pesanan->refresh();
        $this->assertSame('lunas', $pesanan->status_pembayaran);
        $this->assertSame('dikonfirmasi', $pesanan->status_pesanan);
        $this->assertSame($this->staf->id, $pesanan->ditangani_oleh);
        $this->assertNotNull($pesanan->dibayar_pada);

        $pembayaran->refresh();
        $this->assertSame('lunas', $pembayaran->status);
        $this->assertSame('bukti_bayar/bukti-qris.jpg', $pembayaran->bukti_path);
        $this->assertSame($this->staf->id, $pembayaran->diverifikasi_oleh);

        $this->assertDatabaseHas('log_aktivitas', [
            'aksi' => 'verifikasi_pembayaran',
            'id_objek' => $pesanan->id,
            'pengguna_id' => $this->staf->id,
        ]);
    }

    public function test_staf_menolak_konfirmasi_qris_tanpa_bukti_pembayaran()
    {
        $pesanan = Pesanan::create([
            'kode_pesanan' => 'OMYU-TEST-'.Str::random(5),
            'token_akses' => hash('sha256', Str::random(30)),
            'nama_pembeli' => 'Rudi Pembeli',
            'telepon_pembeli' => '08987654322',
            'alamat_pengiriman' => 'Outlet OMYU',
            'jenis' => 'online',
            'metode_bayar' => 'qris',
            'subtotal' => 18000,
            'ongkir' => 0,
            'total' => 18000,
            'status_pembayaran' => 'menunggu',
            'status_pesanan' => 'menunggu_verifikasi',
            'kedaluwarsa_pada' => now()->addHours(2),
        ]);

        $pembayaran = $pesanan->pembayaran()->create([
            'gerbang' => 'manual',
            'referensi_gerbang' => 'QRIS-OMYU-TANPA-BUKTI',
            'metode' => 'qris',
            'jumlah' => 18000,
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($this->staf)->post(route('panel.staf.pesanan.verifikasi-bayar', $pesanan->id), [
            'konfirmasi_tanpa_bukti' => 1,
            'catatan_verifikasi' => 'Sudah dicek manual lewat WhatsApp',
        ]);

        $response->assertSessionHas('error', 'Bukti pembayaran belum tersedia pada pesanan ini. Minta pembeli mengunggah bukti terlebih dahulu.');

        $pesanan->refresh();
        $this->assertSame('menunggu', $pesanan->status_pembayaran);
        $this->assertSame('menunggu_verifikasi', $pesanan->status_pesanan);

        $pembayaran->refresh();
        $this->assertSame('menunggu_verifikasi', $pembayaran->status);
        $this->assertNull($pembayaran->diverifikasi_oleh);
    }

    public function test_detail_pesanan_belum_lunas_tidak_menampilkan_form_unggah_bukti_staf()
    {
        $pesanan = Pesanan::create([
            'kode_pesanan' => 'OMYU-TEST-'.Str::random(5),
            'token_akses' => hash('sha256', Str::random(30)),
            'nama_pembeli' => 'Ani Pembeli',
            'telepon_pembeli' => '08987654323',
            'alamat_pengiriman' => 'Outlet OMYU',
            'jenis' => 'online',
            'metode_bayar' => 'qris',
            'subtotal' => 15000,
            'ongkir' => 0,
            'total' => 15000,
            'status_pembayaran' => 'menunggu',
            'status_pesanan' => 'menunggu_verifikasi',
            'kedaluwarsa_pada' => now()->addHours(2),
        ]);

        $pesanan->pembayaran()->create([
            'gerbang' => 'manual',
            'referensi_gerbang' => 'QRIS-OMYU-DETAIL',
            'metode' => 'qris',
            'jumlah' => 15000,
            'status' => 'menunggu_verifikasi',
            'bukti_path' => 'bukti_bayar/bukti-qris-detail.jpg',
        ]);

        $response = $this->actingAs($this->staf)->get(route('panel.staf.pesanan.detail', $pesanan->id));

        $response->assertOk();
        $response->assertSee('Konfirmasi Pembayaran &amp; Pesanan', false);
        $response->assertSee('Bukti/referensi pembayaran sudah tersedia', false);
        $response->assertDontSee('name="bukti_bayar"', false);
        $response->assertDontSee('konfirmasi_tanpa_bukti', false);
    }

    public function test_staf_dapat_memproses_dan_menyelesaikan_pesanan()
    {
        $stokAwal = $this->produk->fresh()->stok;

        $pesanan = Pesanan::create([
            'kode_pesanan' => 'OMYU-TEST-PROC-'.Str::random(5),
            'token_akses' => hash('sha256', Str::random(30)),
            'nama_pembeli' => 'Ahmad',
            'telepon_pembeli' => '0812341234',
            'alamat_pengiriman' => 'Outlet OMYU',
            'subtotal' => 12000,
            'ongkir' => 0,
            'total' => 12000,
            'status_pembayaran' => 'lunas',
            'status_pesanan' => 'diproses',
            'kedaluwarsa_pada' => now()->addHours(2),
        ]);

        ItemPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $this->produk->id,
            'nama_produk' => $this->produk->nama,
            'harga' => 12000,
            'jumlah' => 1,
            'subtotal' => 12000,
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
            'kode_pesanan' => 'OMYU-TEST-BATAL-'.Str::random(5),
            'token_akses' => hash('sha256', Str::random(30)),
            'nama_pembeli' => 'Rini',
            'telepon_pembeli' => '0812341234',
            'alamat_pengiriman' => 'Outlet OMYU',
            'subtotal' => 12000,
            'ongkir' => 0,
            'total' => 12000,
            'status_pembayaran' => 'menunggu',
            'status_pesanan' => 'menunggu_verifikasi',
            'kedaluwarsa_pada' => now()->addHours(2),
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
            'jumlah' => 3,
            'alasan' => 'Buah melon busuk saat disortir pagi',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal - 3, $this->produk->fresh()->stok);

        $this->assertDatabaseHas('riwayat_stok', [
            'produk_id' => $this->produk->id,
            'jenis' => 'penyesuaian',
            'jumlah' => -3,
            'catatan' => 'Buah melon busuk saat disortir pagi',
        ]);
    }

    public function test_halaman_profil_staf_menampilkan_form_unggah_foto()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.staf.profil.index'));

        $response->assertOk();
        $response->assertSee('Foto Profil');
        $response->assertSee('enctype="multipart/form-data"', false);
    }

    public function test_staf_dapat_mengunggah_foto_profil_dan_menampilkannya_di_navbar()
    {
        Storage::fake('public');

        $this->actingAs($this->staf)->post(route('panel.staf.profil.foto'), [
            'foto' => UploadedFile::fake()->create('foto.jpg', 100, 'image/jpeg'),
        ])->assertSessionHas('sukses');

        $foto = $this->staf->fresh()->foto;
        $this->assertNotNull($foto);
        Storage::disk('public')->assertExists($foto);

        $response = $this->actingAs($this->staf)->get(route('panel.beranda'));

        $response->assertOk();
        $response->assertSee($this->staf->fresh()->foto_url, false);
    }

    public function test_staf_dapat_menghapus_foto_profil()
    {
        Storage::fake('public');

        $this->actingAs($this->staf)->post(route('panel.staf.profil.foto'), [
            'foto' => UploadedFile::fake()->create('foto.jpg', 100, 'image/jpeg'),
        ])->assertSessionHas('sukses');

        $foto = $this->staf->fresh()->foto;

        $this->actingAs($this->staf)->post(route('panel.staf.profil.foto'), [
            'hapus_foto' => 1,
        ])->assertSessionHas('sukses');

        $this->assertNull($this->staf->fresh()->foto);
        Storage::disk('public')->assertMissing($foto);
    }

    public function test_menolak_foto_profil_yang_bukan_gambar()
    {
        $this->actingAs($this->staf)->post(route('panel.staf.profil.foto'), [
            'foto' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('foto');

        $this->assertNull($this->staf->fresh()->foto);
    }

    public function test_staf_dapat_mengubah_kata_sandi_akun()
    {
        $response = $this->actingAs($this->staf)->post(route('panel.staf.profil.password'), [
            'password_lama' => 'password123',
            'password' => 'sandiBaru456',
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
            'kode_pesanan' => 'OMY-TEST-'.rand(100, 999),
            'token_akses' => Str::random(32),
            'nama_pembeli' => 'Pembeli Racik Uji',
            'subtotal' => 12000,
            'ongkir' => 0,
            'total' => 12000,
            'status_pembayaran' => 'lunas',
            'status_pesanan' => 'dikonfirmasi',
            'kedaluwarsa_pada' => now()->addHours(2),
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
            'item_id' => $this->produk->id,
            'jumlah' => 1,
            'alasan' => 'Blender tumpah saat penuangan',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal - 1, $this->produk->fresh()->stok);

        $this->assertDatabaseHas('riwayat_stok', [
            'produk_id' => $this->produk->id,
            'catatan' => '[Gagal Racik/Waste] Blender tumpah saat penuangan',
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
        $bahan = Bahan::firstOrCreate(
            ['nama' => 'Mangga Uji Pasokan'],
            [
                'jenis' => 'buah',
                'satuan_dasar' => 'g',
                'satuan_beli' => 'kg',
                'konversi' => 1000,
                'stok' => 5000,
                'batas_minimum' => 3000,
                'aktif' => 1,
            ]
        );

        $stokAwal = (float) $bahan->fresh()->stok;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.stok.pasokan'), [
            'bahan_id' => $bahan->id,
            'jumlah_masuk' => 5, // 5 kg = 5000 g
            'satuan_input' => 'kg',
            'catatan' => 'Pasokan Segar Pagi',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal + 5000, (float) $bahan->fresh()->stok);
    }

    public function test_staf_dapat_mencatat_pembuangan_bahan_rusak_busuk()
    {
        $bahan = Bahan::firstOrCreate(
            ['nama' => 'Alpukat Uji Buang'],
            [
                'jenis' => 'buah',
                'satuan_dasar' => 'g',
                'satuan_beli' => 'kg',
                'konversi' => 1000,
                'stok' => 8000,
                'batas_minimum' => 2000,
                'aktif' => 1,
            ]
        );

        $stokAwal = (float) $bahan->fresh()->stok;

        $response = $this->actingAs($this->staf)->post(route('panel.staf.stok.pembuangan'), [
            'bahan_id' => $bahan->id,
            'jumlah_buang' => 1.5, // 1.5 kg = 1500 g
            'satuan_input' => 'kg',
            'alasan' => 'Buah busuk / berjamur saat sortiran pagi',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertEquals($stokAwal - 1500, (float) $bahan->fresh()->stok);
    }

    public function test_staf_dapat_audit_dan_toggle_aktif_bahan()
    {
        $bahan = Bahan::firstOrCreate(
            ['nama' => 'Jeruk Uji Audit'],
            [
                'jenis' => 'buah',
                'satuan_dasar' => 'g',
                'satuan_beli' => 'kg',
                'konversi' => 1000,
                'stok' => 3000,
                'batas_minimum' => 1000,
                'aktif' => 1,
            ]
        );

        // Audit opname ke 4 kg (4000 g)
        $respAudit = $this->actingAs($this->staf)->post(route('panel.staf.stok.audit', $bahan->id), [
            'stok_fisik' => 4,
            'satuan_input' => 'kg',
            'catatan' => 'Timbangan fisik opname 14:00 WIB',
        ]);
        $respAudit->assertSessionHas('sukses');
        $this->assertEquals(4000, (float) $bahan->fresh()->stok);

        // Toggle Aktif
        $respToggle = $this->actingAs($this->staf)->post(route('panel.staf.stok.toggle-aktif', $bahan->id));
        $respToggle->assertSessionHas('sukses');
        $this->assertEquals(0, $bahan->fresh()->aktif);
    }

    public function test_tombol_bantuan_tersedia_di_kiri_bawah_panel_staf()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.beranda'));

        $response->assertOk();
        $response->assertSee('id="helpFab"', false);
        $response->assertSee('id="helpPanel"', false);
        $response->assertSee(route('panel.staf.bantuan.index'), false);
    }

    public function test_halaman_pusat_bantuan_staf_dapat_diakses()
    {
        $response = $this->actingAs($this->staf)->get(route('panel.staf.bantuan.index'));

        $response->assertOk();
        $response->assertSee('Pusat Bantuan Staf');
        $response->assertSee('Transaksi kasir gagal dicatat?');
        $response->assertDontSee('Cara memindai QR pengambilan pesanan?');
        $response->assertSee('cariBantuan');
    }
}
