<?php

namespace Database\Seeders;

use App\Models\Bahan;
use Illuminate\Database\Seeder;

class BahanDemoSeeder extends Seeder
{
    public function run(): void
    {
        $daftarBahan = [
            // 1. Mangga (14.5 kg tersisa, normal)
            [
                'nama'             => 'Mangga',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 75.00,
                'batas_minimum'    => 5000,
                'stok'             => 14500, // 14.5 kg
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 18.00,
                'aktif'            => 1,
            ],
            // 2. Alpukat (3.2 kg tersisa, kritis/menipis)
            [
                'nama'             => 'Alpukat',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 70.00,
                'batas_minimum'    => 5000,
                'stok'             => 3200, // 3.2 kg (kritis)
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 25.00,
                'aktif'            => 1,
            ],
            // 3. Jeruk (0.0 kg tersisa, habis)
            [
                'nama'             => 'Jeruk',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 60.00,
                'batas_minimum'    => 4000,
                'stok'             => 0, // 0.0 kg (habis)
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 14.00,
                'aktif'            => 0, // Tutup/Off
            ],
            // 4. Buah Naga (8.0 kg tersisa, normal)
            [
                'nama'             => 'Buah Naga',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 80.00,
                'batas_minimum'    => 3000,
                'stok'             => 8000, // 8.0 kg
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 16.00,
                'aktif'            => 1,
            ],
            // 5. Nanas (1.5 kg, kritis)
            [
                'nama'             => 'Nanas',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 65.00,
                'batas_minimum'    => 3000,
                'stok'             => 1500, // 1.5 kg (kritis)
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 12.00,
                'aktif'            => 1,
            ],
            // 6. Stroberi (2.0 kg, kritis)
            [
                'nama'             => 'Stroberi',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 95.00,
                'batas_minimum'    => 3000,
                'stok'             => 2000, // 2.0 kg (kritis)
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 35.00,
                'aktif'            => 1,
            ],
            // 7. Melon (10.0 kg, normal)
            [
                'nama'             => 'Melon',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 70.00,
                'batas_minimum'    => 4000,
                'stok'             => 10000, // 10.0 kg
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 15.00,
                'aktif'            => 1,
            ],
            // 8. Apel Fuji (12.0 kg, normal)
            [
                'nama'             => 'Apel Fuji',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 85.00,
                'batas_minimum'    => 3000,
                'stok'             => 12000,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 28.00,
                'aktif'            => 1,
            ],
            // 9. Sirsak (6.5 kg, normal)
            [
                'nama'             => 'Sirsak',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 60.00,
                'batas_minimum'    => 3000,
                'stok'             => 6500,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 17.00,
                'aktif'            => 1,
            ],
            // 10. Jambu Biji (9.0 kg, normal)
            [
                'nama'             => 'Jambu Biji',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 75.00,
                'batas_minimum'    => 3000,
                'stok'             => 9000,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 13.00,
                'aktif'            => 1,
            ],
            // 11. Pisang Cavendish (11.0 kg, normal)
            [
                'nama'             => 'Pisang Cavendish',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 65.00,
                'batas_minimum'    => 3000,
                'stok'             => 11000,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 16.00,
                'aktif'            => 1,
            ],
            // 12. Lemon Segar (5.0 kg, normal)
            [
                'nama'             => 'Lemon Segar',
                'jenis'            => 'buah',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 50.00,
                'batas_minimum'    => 2000,
                'stok'             => 5000,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 30.00,
                'aktif'            => 1,
            ],
            // 13. Gula Pasir Murni
            [
                'nama'             => 'Gula Pasir',
                'jenis'            => 'bahan_lain',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 100.00,
                'batas_minimum'    => 5000,
                'stok'             => 25000,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 15.00,
                'aktif'            => 1,
            ],
            // 14. Susu Kental Manis
            [
                'nama'             => 'Susu Kental Manis',
                'jenis'            => 'bahan_lain',
                'satuan_dasar'     => 'ml',
                'satuan_beli'      => 'kaleng',
                'konversi'         => 370,
                'rendemen_standar' => 100.00,
                'batas_minimum'    => 1000,
                'stok'             => 7400,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 28.00,
                'aktif'            => 1,
            ],
            // 15. Es Batu Kristal
            [
                'nama'             => 'Es Batu Kristal',
                'jenis'            => 'bahan_lain',
                'satuan_dasar'     => 'g',
                'satuan_beli'      => 'kg',
                'konversi'         => 1000,
                'rendemen_standar' => 100.00,
                'batas_minimum'    => 10000,
                'stok'             => 40000,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 3.00,
                'aktif'            => 1,
            ],
            // 16. Cup & Sealer Lid
            [
                'nama'             => 'Cup & Sealer Lid',
                'jenis'            => 'kemasan',
                'satuan_dasar'     => 'pcs',
                'satuan_beli'      => 'pack',
                'konversi'         => 50,
                'rendemen_standar' => 100.00,
                'batas_minimum'    => 50,
                'stok'             => 350,
                'stok_dicadangkan' => 0,
                'biaya_rata_rata'  => 1200.00,
                'aktif'            => 1,
            ],
        ];

        foreach ($daftarBahan as $item) {
            Bahan::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}

