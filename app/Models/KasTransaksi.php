<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTransaksi extends Model
{
    use HasFactory;

    protected $table = 'kas_transaksi';

    protected $fillable = [
        'shift_id',
        'jenis',       // 'masuk' atau 'keluar'
        'jumlah',
        'keterangan',
        'pengguna_id',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function pencatat()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}