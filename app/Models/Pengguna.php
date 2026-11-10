<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'pengguna';

    protected $fillable = ['nama', 'email', 'password', 'peran', 'aktif','bisa_lihat_hpp',  'telepon'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'aktif'    => 'boolean',
        'bisa_lihat_hpp'=> 'boolean',
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