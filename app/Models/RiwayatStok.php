<?php

namespace App\Models;

use App\Models\Concerns\PakaiBahasaIndonesia;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    use PakaiBahasaIndonesia;

    protected $table = 'riwayat_stok';

    protected $fillable = [
        'produk_id', 'pengguna_id', 'pesanan_id', 'jenis',
        'jumlah', 'stok_setelah', 'catatan',
    ];

    public function getUpdatedAtColumn()
    {
        return null;
    }

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