<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GambarProduk extends Model
{
    use SoftDeletes;

    protected $table = 'gambar_produk';

    protected $fillable = ['produk_id', 'path', 'urutan'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}