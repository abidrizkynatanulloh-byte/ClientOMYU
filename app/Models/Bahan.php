<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bahan';

    protected $fillable = [
        'nama',
        'jenis',
        'satuan_dasar',
        'satuan_beli',
        'konversi',
        'rendemen_standar',
        'batas_minimum',
        'stok',
        'stok_dicadangkan',
        'biaya_rata_rata',
        'aktif',
    ];

    protected $casts = [
        'konversi'         => 'decimal:3',
        'rendemen_standar' => 'decimal:2',
        'batas_minimum'    => 'decimal:3',
        'stok'             => 'decimal:3',
        'stok_dicadangkan' => 'decimal:3',
        'biaya_rata_rata'  => 'decimal:4',
        'aktif'            => 'boolean',
    ];

    // Helper: menghitung stok yang benar-benar siap dipakai (stok fisik - cadangan pesanan)
    public function getStokTersediaAttribute(): float
    {
        return max(0, (float) $this->stok - (float) $this->stok_dicadangkan);
    }

    // Helper: mengecek apakah stok sudah di bawah batas minimum
    public function getIsKritisAttribute(): bool
    {
        return $this->stok_tersedia <= (float) $this->batas_minimum;
    }

    // Relasi ke riwayat pembelian bahan
    public function pembelian()
    {
        return $this->hasMany(PembelianBahan::class, 'bahan_id');
    }
}