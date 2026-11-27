<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class StafScanQrTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function staf_dapat_membuka_halaman_scan_qr(): void
    {
        $staf = $this->stafKaryawan();

        $this->actingAs($staf)
            ->get(route('panel.staf.pesanan.scan'))
            ->assertOk()
            ->assertSee('Scan QR Pengambilan')
            ->assertSee('bukaScanQrCepat');
    }

    #[Test]
    public function validasi_qr_mengembalikan_detail_pesanan_yang_siap_diambil(): void
    {
        $staf = $this->stafKaryawan();
        $token = 'QR-'.Str::upper(Str::random(12));
        $pesanan = $this->pesananSiapDiambil($token);

        $this->actingAs($staf)
            ->postJson(route('panel.staf.pesanan.scan.validasi'), ['token' => $token])
            ->assertOk()
            ->assertJsonPath('valid', true)
            ->assertJsonPath('pesanan.kode_pesanan', $pesanan->kode_pesanan)
            ->assertJsonPath('pesanan.nama_pembeli', $pesanan->nama_pembeli);
    }

    #[Test]
    public function validasi_qr_menerima_kode_pesanan_yang_tertera_di_qr_pelanggan(): void
    {
        $staf = $this->stafKaryawan();
        $pesanan = $this->pesananSiapDiambil('QR-'.Str::upper(Str::random(12)));

        $this->actingAs($staf)
            ->postJson(route('panel.staf.pesanan.scan.validasi'), ['token' => $pesanan->kode_pesanan])
            ->assertOk()
            ->assertJsonPath('valid', true)
            ->assertJsonPath('pesanan.id', $pesanan->id)
            ->assertJsonPath('pesanan.kode_pesanan', $pesanan->kode_pesanan);
    }

    #[Test]
    public function validasi_qr_tetap_valid_untuk_pesanan_lunas_yang_melewati_batas_bayar(): void
    {
        $staf = $this->stafKaryawan();
        $pesanan = $this->pesananSiapDiambil('QR-'.Str::upper(Str::random(12)), now()->subHour());

        $this->actingAs($staf)
            ->postJson(route('panel.staf.pesanan.scan.validasi'), ['token' => $pesanan->kode_pesanan])
            ->assertOk()
            ->assertJsonPath('valid', true)
            ->assertJsonPath('pesanan.kode_pesanan', $pesanan->kode_pesanan);
    }

    #[Test]
    public function validasi_qr_menolak_token_yang_tidak_ditemukan(): void
    {
        $staf = $this->stafKaryawan();
        $this->pesananSiapDiambil('QR-VALID');

        $this->actingAs($staf)
            ->postJson(route('panel.staf.pesanan.scan.validasi'), ['token' => 'QR-TIDAK-ADA'])
            ->assertStatus(422)
            ->assertJsonPath('valid', false)
            ->assertJsonPath('alasan', 'QR tidak ditemukan.');
    }

    #[Test]
    public function terima_qr_menyelesaikan_pesanan_dan_mencatat_aktivitas(): void
    {
        $staf = $this->stafKaryawan();
        $token = 'QR-'.Str::upper(Str::random(12));
        $pesanan = $this->pesananSiapDiambil($token);

        $this->actingAs($staf)
            ->postJson(route('panel.staf.pesanan.terima-qr', $pesanan->id), ['token' => $token])
            ->assertOk()
            ->assertJsonPath('valid', true)
            ->assertJsonPath('pesanan.status_pesanan', 'selesai');

        $this->assertDatabaseHas('pesanan', [
            'id' => $pesanan->id,
            'status_pesanan' => 'selesai',
            'ditangani_oleh' => $staf->id,
        ]);

        $this->assertDatabaseHas('log_aktivitas', [
            'aksi' => 'konfirmasi_pengambilan',
            'tipe_objek' => 'pesanan',
            'id_objek' => $pesanan->id,
            'pengguna_id' => $staf->id,
        ]);
    }

    private function stafKaryawan(): Pengguna
    {
        return Pengguna::create([
            'nama' => 'Staf Scan',
            'email' => 'staf-scan-'.Str::lower(Str::random(8)).'@omyu.test',
            'password' => 'rahasia123',
            'peran' => 'karyawan',
            'aktif' => true,
            'bisa_lihat_hpp' => false,
        ]);
    }

    private function pesananSiapDiambil(string $token, ?Carbon $kedaluwarsa = null): Pesanan
    {
        return Pesanan::create([
            'kode_pesanan' => 'OMYU-TEST-'.Str::upper(Str::random(6)),
            'token_akses' => $token,
            'jenis' => 'online',
            'nama_pembeli' => 'Pembeli Test',
            'telepon_pembeli' => '081234567890',
            'catatan' => 'Jadwal Ambil: '.now()->addDay()->toDateString(),
            'subtotal' => 15000,
            'total' => 15000,
            'metode_bayar' => 'bayar_ditempat',
            'status_pembayaran' => 'lunas',
            'status_pesanan' => 'siap_diambil',
            'kedaluwarsa_pada' => $kedaluwarsa ?? now()->addDay(),
            'jadwal_ambil_selesai' => now()->addDay(),
        ]);
    }
}
