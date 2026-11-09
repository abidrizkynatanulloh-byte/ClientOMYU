<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Pengguna::updateOrCreate(
            ['email' => 'admin@omyu.test'],
            [
                'nama'     => 'Admin OMYU',
                'password' => 'ADMIN123',
                'peran'    => 'admin',
                'aktif'    => true,
            ]
        );

        // Karyawan (tambah baris sesuai kebutuhan)
        $karyawan = [
            ['nama' => 'Budi',  'email' => 'budi@omyu.test',  'password' => 'Rahasia12345', 'telepon' => '081234567890'],
            ['nama' => 'Siti',  'email' => 'siti@omyu.test',  'password' => 'Rahasia67890', 'telepon' => null],
        ];

        foreach ($karyawan as $data) {
            Pengguna::updateOrCreate(
                ['email' => $data['email']],
                [
                    'nama'     => $data['nama'],
                    'password' => $data['password'],
                    'peran'    => 'karyawan',
                    'aktif'    => true,
                    'telepon'  => $data['telepon'],
                ]
            );
        }
    }
}