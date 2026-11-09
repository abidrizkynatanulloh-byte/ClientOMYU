<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatStok extends Model
{
    use SoftDeletes;

    const UPDATED_AT = null;

    protected $table = 'riwayat_stok';

    protected $fillable = [
        'produk_id', 'pengguna_id', 'pesanan_id', 'jenis',
        'jumlah', 'stok_setelah', 'catatan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}