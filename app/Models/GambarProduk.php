<?php

namespace App\Models;

use App\Models\Concerns\PakaiBahasaIndonesia;
use Illuminate\Database\Eloquent\Model;

class GambarProduk extends Model
{
    use PakaiBahasaIndonesia;

    protected $table = 'gambar_produk';

    protected $fillable = ['produk_id', 'path', 'urutan'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}