<?php

namespace App\Models;

use App\Models\Concerns\PakaiBahasaIndonesia;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use PakaiBahasaIndonesia;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id', 'gerbang', 'referensi_gerbang', 'metode',
        'jumlah', 'status', 'dibayar_pada', 'data_mentah',
    ];

    protected $casts = [
        'jumlah'       => 'integer',
        'dibayar_pada' => 'datetime',
        'data_mentah'  => 'array',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}