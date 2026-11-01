<?php

namespace App\Models;

use App\Models\Concerns\PakaiBahasaIndonesia;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable, PakaiBahasaIndonesia;

    protected $table = 'pengguna';

    protected $fillable = ['nama', 'email', 'password', 'peran', 'aktif', 'telepon'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'aktif'    => 'boolean',
        'password' => 'hashed',
    ];

    public function adalahAdmin(): bool
    {
        return $this->peran === 'admin';
    }

    public function pesananDitangani()
    {
        return $this->hasMany(Pesanan::class, 'ditangani_oleh');
    }
}