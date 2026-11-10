<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        if (Produk::count() === 0) {
            $katJus = Kategori::firstOrCreate(
                ['slug' => 'jus-buah-segar'],
                ['nama' => 'Jus Buah Segar', 'aktif' => 1]
            );

            $katCold = Kategori::firstOrCreate(
                ['slug' => 'cold-pressed'],
                ['nama' => 'Cold Pressed Juice', 'aktif' => 1]
            );

            $katSmoothie = Kategori::firstOrCreate(
                ['slug' => 'smoothies'],
                ['nama' => 'Smoothies & Booster', 'aktif' => 1]
            );

            Produk::create([
                'kategori_id' => $katJus->id,
                'nama'        => 'Jus Melon Segar',
                'slug'        => 'jus-melon-segar',
                'harga'       => 12000,
                'stok'        => 24,
                'berat_gram'  => 350,
                'aktif'       => 1,
                'deskripsi'   => 'Dibuat dari 200g daging melon segar asli dengan tingkat manis pas.',
            ]);

            Produk::create([
                'kategori_id' => $katJus->id,
                'nama'        => 'Jus Mangga Arumanis',
                'slug'        => 'jus-mangga-arumanis',
                'harga'       => 15000,
                'stok'        => 30,
                'berat_gram'  => 350,
                'aktif'       => 1,
                'deskripsi'   => 'Mangga harum manis segar pilihan petani lokal.',
            ]);

            Produk::create([
                'kategori_id' => $katJus->id,
                'nama'        => 'Jus Alpukat Mentega',
                'slug'        => 'jus-alpukat-mentega',
                'harga'       => 18000,
                'stok'        => 15,
                'berat_gram'  => 380,
                'aktif'       => 1,
                'deskripsi'   => 'Alpukat lembut kental dengan susu cokelat pilihan.',
            ]);

            Produk::create([
                'kategori_id' => $katJus->id,
                'nama'        => 'Jus Jeruk Peras Murni',
                'slug'        => 'jus-jeruk-peras',
                'harga'       => 14000,
                'stok'        => 20,
                'berat_gram'  => 350,
                'aktif'       => 1,
                'deskripsi'   => 'Jeruk segar peras asli kaya vitamin C alami.',
            ]);

            Produk::create([
                'kategori_id' => $katCold->id,
                'nama'        => 'Green Detox Cold Pressed',
                'slug'        => 'green-detox-cold-pressed',
                'harga'       => 22000,
                'stok'        => 10,
                'berat_gram'  => 300,
                'aktif'       => 1,
                'deskripsi'   => 'Kombinasi bayam, apel hijau, timun, dan perasan lemon segar.',
            ]);

            Produk::create([
                'kategori_id' => $katSmoothie->id,
                'nama'        => 'Berry Banana Booster',
                'slug'        => 'berry-banana-booster',
                'harga'       => 20000,
                'stok'        => 8,
                'berat_gram'  => 400,
                'aktif'       => 1,
                'deskripsi'   => 'Strawberry segar, pisang cavendish, dan yogurt murni.',
            ]);
        }
    }
}

