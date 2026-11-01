<?php

namespace App\Models;

use App\Models\Concerns\PakaiBahasaIndonesia;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use PakaiBahasaIndonesia;

    protected $table = 'kategori';

    protected $fillable = ['nama', 'slug', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}