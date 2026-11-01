<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::updateOrCreate(
            ['email' => 'admin@omyu.test'],
            [
                'nama'     => 'Admin OMYU',
                'password' => 'GantiPasswordIni123',
                'peran'    => 'admin',
                'aktif'    => true,
            ]
        );
    }
}