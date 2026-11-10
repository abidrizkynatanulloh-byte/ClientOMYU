<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';

    protected $fillable = [
        'kategori_id', 'nama', 'slug', 'deskripsi', 'harga', 'stok',
        'berat_gram', 'gambar', 'aktif', 'dibuat_oleh', 'diubah_oleh',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok'  => 'integer',
        'aktif' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function gambarProduk()
    {
        return $this->hasMany(GambarProduk::class, 'produk_id')->orderBy('urutan');
    }

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class, 'produk_id');
    }

    public function riwayatStok()
    {
        return $this->hasMany(RiwayatStok::class, 'produk_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function pengubah()
    {
        return $this->belongsTo(Pengguna::class, 'diubah_oleh');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

        public function varian()
    {
        return $this->hasMany(VarianProduk::class, 'produk_id');
    }
    
}