<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarianProduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'varian_produk';

    protected $fillable = [
        'produk_id',
        'nama',
        'harga',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'integer',
        'aktif' => 'boolean',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Mengambil resep yang sedang aktif untuk varian ini
    public function resepAktif()
    {
        return $this->hasOne(Resep::class, 'varian_id')->where('aktif', 1);
    }
}