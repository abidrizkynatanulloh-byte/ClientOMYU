<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahan extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan';

    protected $fillable = [
        'tanggal_beli',
        'bahan_id',
        'pemasok_id',
        'jumlah_beli',
        'satuan_beli',
        'total_harga',
        'rendemen_dipakai',
        'jumlah_bersih',
        'biaya_per_satuan',
        'cara_bayar',
        'kedaluwarsa_pada',
        'dicatat_oleh',
        'catatan',
    ];

    protected $casts = [
        'tanggal_beli'     => 'date',
        'kedaluwarsa_pada' => 'date',
        'jumlah_beli'      => 'decimal:3',
        'rendemen_dipakai' => 'decimal:2',
        'jumlah_bersih'    => 'decimal:3',
        'biaya_per_satuan' => 'decimal:4',
        'total_harga'      => 'integer',
    ];

    // Relasi ke bahan yang dibeli
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    // Relasi ke pengguna/staf yang mencatat
    public function pencatat()
    {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh');
    }
}
