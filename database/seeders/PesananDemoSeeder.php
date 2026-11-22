<?php

namespace Database\Seeders;

use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class PesananDemoSeeder extends Seeder
{
    public function run(): void
    {
        $katJus = Kategori::firstOrCreate(['slug' => 'jus-buah-segar'], ['nama' => 'Jus Buah Segar', 'aktif' => 1]);

        $seledri = Produk::firstOrCreate(
            ['slug' => 'jus-seledri'],
            ['kategori_id' => $katJus->id, 'nama' => 'Jus Seledri', 'harga' => 9000, 'stok' => 15, 'aktif' => 1]
        );

        $alpukat = Produk::firstOrCreate(
            ['slug' => 'jus-alpukat'],
            ['kategori_id' => $katJus->id, 'nama' => 'Jus Alpukat', 'harga' => 10000, 'stok' => 12, 'aktif' => 1]
        );

        $apel = Produk::firstOrCreate(
            ['slug' => 'jus-apel'],
            ['kategori_id' => $katJus->id, 'nama' => 'Jus Apel', 'harga' => 8000, 'stok' => 20, 'aktif' => 1]
        );

        $jeruk = Produk::firstOrCreate(
            ['slug' => 'jus-jeruk'],
            ['kategori_id' => $katJus->id, 'nama' => 'Jus Jeruk', 'harga' => 3000, 'stok' => 25, 'aktif' => 1]
        );

        $mangga = Produk::firstOrCreate(
            ['slug' => 'jus-mangga'],
            ['kategori_id' => $katJus->id, 'nama' => 'Jus Mangga', 'harga' => 15000, 'stok' => 3, 'aktif' => 1]
        );

        $jambu = Produk::firstOrCreate(
            ['slug' => 'jus-jambu-biji'],
            ['kategori_id' => $katJus->id, 'nama' => 'Jus Jambu Biji', 'harga' => 10000, 'stok' => 18, 'aktif' => 1]
        );

        // 1. Pesanan #OMY-1084 (Maya - Sedang Dibuat di Blender)
        $p84 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1084'],
            [
                'token_akses' => hash('sha256', 'OMY-1084'),
                'nama_pembeli' => 'Maya',
                'telepon_pembeli' => '081377889900',
                'alamat_pengiriman' => 'Dine-in (Meja 03)',
                'catatan' => 'No Sugar',
                'subtotal' => 20000,
                'ongkir' => 0,
                'total' => 20000,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'diproses',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => now()->subMinutes(5),
                'created_at' => now()->subMinutes(6),
            ]
        );
        $p84->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p84->id,
            'produk_id' => $alpukat->id,
            'nama_produk' => 'Jus Alpukat (Besar)',
            'harga' => 10000,
            'jumlah' => 2,
            'subtotal' => 20000,
        ]);
        Pembayaran::withTrashed()->where('referensi_gerbang', 'QRIS-BCA-1084')->forceDelete();
        Pembayaran::create([
            'pesanan_id' => $p84->id,
            'gerbang' => 'qris',
            'referensi_gerbang' => 'QRIS-BCA-1084',
            'metode' => 'QRIS BCA',
            'jumlah' => 20000,
            'status' => 'lunas',
            'dibayar_pada' => now()->subMinutes(5),
        ]);

        // 2. Pesanan #OMY-1085 (Rio - Berikutnya / Antrean #2)
        $p85 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1085'],
            [
                'token_akses' => hash('sha256', 'OMY-1085'),
                'nama_pembeli' => 'Rio',
                'telepon_pembeli' => '081244556677',
                'alamat_pengiriman' => 'takeaway',
                'catatan' => 'Normal Sweet',
                'subtotal' => 8000,
                'ongkir' => 0,
                'total' => 8000,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'dikonfirmasi',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => now()->subMinutes(8),
                'created_at' => now()->subMinutes(9),
            ]
        );
        $p85->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p85->id,
            'produk_id' => $jeruk->id,
            'nama_produk' => 'Jus Jeruk (Medium)',
            'harga' => 8000,
            'jumlah' => 1,
            'subtotal' => 8000,
        ]);
        Pembayaran::withTrashed()->where('referensi_gerbang', 'QRIS-BCA-1085')->forceDelete();
        Pembayaran::create([
            'pesanan_id' => $p85->id,
            'gerbang' => 'qris',
            'referensi_gerbang' => 'QRIS-BCA-1085',
            'metode' => 'QRIS BCA',
            'jumlah' => 8000,
            'status' => 'lunas',
            'dibayar_pada' => now()->subMinutes(8),
        ]);

        // 3. Pesanan #OMY-1087 (Siti Rahma - Antrean #3 / Menunggu)
        $p87 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1087'],
            [
                'token_akses' => hash('sha256', 'OMY-1087'),
                'nama_pembeli' => 'Siti Rahma',
                'telepon_pembeli' => '081234567890',
                'alamat_pengiriman' => 'takeaway',
                'catatan' => 'No Ice',
                'subtotal' => 28000,
                'ongkir' => 0,
                'total' => 28000,
                'status_pembayaran' => 'menunggu',
                'status_pesanan' => 'menunggu_verifikasi',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => null,
                'created_at' => now()->subMinutes(12),
            ]
        );
        $p87->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p87->id,
            'produk_id' => $jambu->id,
            'nama_produk' => 'Jus Jambu Biji (Medium)',
            'harga' => 10000,
            'jumlah' => 1,
            'subtotal' => 10000,
        ]);
        ItemPesanan::create([
            'pesanan_id' => $p87->id,
            'produk_id' => $seledri->id,
            'nama_produk' => 'Jus Seledri',
            'harga' => 9000,
            'jumlah' => 2,
            'subtotal' => 18000,
        ]);
        Pembayaran::withTrashed()->where('referensi_gerbang', 'QRIS-BCA-9821')->forceDelete();
        Pembayaran::create([
            'pesanan_id' => $p87->id,
            'gerbang' => 'qris',
            'referensi_gerbang' => 'QRIS-BCA-9821',
            'metode' => 'QRIS BCA',
            'jumlah' => 28000,
            'status' => 'menunggu',
            'data_mentah' => [
                'nama_file' => 'Bukti_Transfer_Siti.jpg',
                'jam' => '14:58 WIB',
                'id_ref' => 'ID 9821',
            ],
        ]);

        // 4. Pesanan #OMY-1086 (Santi Fatmawati - Antrean #4)
        $p86 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1086'],
            [
                'token_akses' => hash('sha256', 'OMY-1086'),
                'nama_pembeli' => 'Santi Fatmawati',
                'telepon_pembeli' => '082198765432',
                'alamat_pengiriman' => 'Counter Pickup',
                'catatan' => 'Normal Ice, Fresh',
                'subtotal' => 8000,
                'ongkir' => 0,
                'total' => 8000,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'dikonfirmasi',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => now()->subMinutes(15),
                'created_at' => now()->subMinutes(14),
            ]
        );
        $p86->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p86->id,
            'produk_id' => $apel->id,
            'nama_produk' => 'Jus Apel',
            'harga' => 8000,
            'jumlah' => 1,
            'subtotal' => 8000,
        ]);
        Pembayaran::withTrashed()->where('referensi_gerbang', 'QRIS-GOPAY-1102')->forceDelete();
        Pembayaran::create([
            'pesanan_id' => $p86->id,
            'gerbang' => 'qris',
            'referensi_gerbang' => 'QRIS-GOPAY-1102',
            'metode' => 'QRIS',
            'jumlah' => 8000,
            'status' => 'lunas',
            'dibayar_pada' => now()->subMinutes(15),
        ]);

        // 5. Pesanan #OMY-1088 (Doni H. - Antrean #5)
        $p88 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1088'],
            [
                'token_akses' => hash('sha256', 'OMY-1088'),
                'nama_pembeli' => 'Doni H.',
                'telepon_pembeli' => '081233445566',
                'alamat_pengiriman' => 'takeaway',
                'catatan' => 'Normal Sweet',
                'subtotal' => 15000,
                'ongkir' => 0,
                'total' => 15000,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'dikonfirmasi',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => now()->subMinutes(16),
                'created_at' => now()->subMinutes(18),
            ]
        );
        $p88->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p88->id,
            'produk_id' => $mangga->id,
            'nama_produk' => 'Jus Buah Naga',
            'harga' => 15000,
            'jumlah' => 1,
            'subtotal' => 15000,
        ]);

        // 6. Pesanan #OMY-1089 (Rina K. - Antrean #6)
        $p89 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1089'],
            [
                'token_akses' => hash('sha256', 'OMY-1089'),
                'nama_pembeli' => 'Rina K.',
                'telepon_pembeli' => '085611223344',
                'alamat_pengiriman' => 'takeaway',
                'catatan' => 'Less Sugar',
                'subtotal' => 12000,
                'ongkir' => 0,
                'total' => 12000,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'dikonfirmasi',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => now()->subMinutes(18),
                'created_at' => now()->subMinutes(20),
            ]
        );
        $p89->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p89->id,
            'produk_id' => $apel->id,
            'nama_produk' => 'Jus Melon Segar',
            'harga' => 12000,
            'jumlah' => 1,
            'subtotal' => 12000,
        ]);

        // 7. Pesanan #OMY-1082 (Sarah Amalia - Siap Diambil)
        $p82 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1082'],
            [
                'token_akses' => hash('sha256', 'OMY-1082'),
                'nama_pembeli' => 'Sarah Amalia',
                'telepon_pembeli' => '081299887766',
                'alamat_pengiriman' => 'Counter Pickup',
                'catatan' => 'Jus Alpukat (Less Ice 50%), Jus Mangga (No Sugar)',
                'subtotal' => 25000,
                'ongkir' => 0,
                'total' => 25000,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'siap_diambil',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => now()->subMinutes(20),
                'created_at' => now()->subMinutes(25),
            ]
        );
        $p82->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p82->id,
            'produk_id' => $alpukat->id,
            'nama_produk' => 'Jus Alpukat',
            'harga' => 10000,
            'jumlah' => 1,
            'subtotal' => 10000,
        ]);
        ItemPesanan::create([
            'pesanan_id' => $p82->id,
            'produk_id' => $mangga->id,
            'nama_produk' => 'Jus Mangga',
            'harga' => 15000,
            'jumlah' => 1,
            'subtotal' => 15000,
        ]);

        // 8. Pesanan #OMY-1079 (Andi Wijaya - Sudah Diambil / Selesai)
        $p79 = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'OMY-1079'],
            [
                'token_akses' => hash('sha256', 'OMY-1079'),
                'nama_pembeli' => 'Andi Wijaya',
                'telepon_pembeli' => '085711223344',
                'alamat_pengiriman' => 'Takeaway',
                'catatan' => 'Jus Jeruk (No Sugar)',
                'subtotal' => 9000,
                'ongkir' => 0,
                'total' => 9000,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'selesai',
                'kedaluwarsa_pada' => now()->addHours(2),
                'dibayar_pada' => now()->subMinutes(40),
                'created_at' => now()->subMinutes(45),
            ]
        );
        $p79->itemPesanan()->delete();
        ItemPesanan::create([
            'pesanan_id' => $p79->id,
            'produk_id' => $jeruk->id,
            'nama_produk' => 'Jus Jeruk',
            'harga' => 3000,
            'jumlah' => 3,
            'subtotal' => 9000,
        ]);
    }
}
