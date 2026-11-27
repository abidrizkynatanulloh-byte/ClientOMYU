<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';

    protected $fillable = [
        'kategori_id', 'nama', 'slug', 'barcode', 'deskripsi', 'harga', 'stok',
        'berat_gram', 'gambar', 'aktif', 'dibuat_oleh', 'diubah_oleh',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'aktif' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function gambarUrl(int $lebar = 600): string
    {
        if ($this->gambar) {
            return str_starts_with($this->gambar, 'http')
                ? $this->gambar
                : asset('storage/'.$this->gambar);
        }

        $nama = strtolower($this->nama);

        if (str_contains($nama, 'mangga')) {
            $foto = 'photo-1546173159-315724a31696';
        } elseif (str_contains($nama, 'alpukat')) {
            $foto = 'photo-1550258987-190a2d41a8ba';
        } elseif (str_contains($nama, 'tomat')) {
            $foto = 'photo-1592924357228-91a4daadcfea';
        } elseif (str_contains($nama, 'seledri') || str_contains($nama, 'green')) {
            $foto = 'photo-1610970881699-44a5587cabec';
        } elseif (str_contains($nama, 'kopi') || str_contains($nama, 'coffe') || str_contains($nama, 'nescafe')) {
            $foto = 'photo-1517256064527-09c73fc73e38';
        } else {
            $foto = 'photo-1622597467836-f3285f2131b8';
        }

        return "https://images.unsplash.com/{$foto}?w={$lebar}&auto=format&fit=crop&q=80";
    }

    public function gambarProduk()
    {
        return $this->hasMany(GambarProduk::class, 'produk_id')->orderBy('urutan');
    }

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class, 'produk_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'produk_id');
    }

    /**
     * Rata-rata bintang hasil agregasi ulasan. Menu belum dinilai bernilai 0.
     */
    public function bintangRataRata(): float
    {
        return round((float) ($this->ulasan_avg_bintang ?? 0), 1);
    }

    /**
     * Jumlah ulasan hasil agregasi; nol untuk menu yang belum dinilai.
     */
    public function jumlahUlasan(): int
    {
        return (int) ($this->ulasan_count ?? 0);
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
