<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id', 'gerbang', 'referensi_gerbang', 'metode',
        'jumlah', 'status', 'dibayar_pada', 'data_mentah',
        'bukti_path', 'biaya_transaksi', 'diverifikasi_oleh',
        'diverifikasi_pada', 'catatan_verifikasi',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'biaya_transaksi' => 'decimal:2',
        'dibayar_pada' => 'datetime',
        'diverifikasi_pada' => 'datetime',
        'data_mentah' => 'array',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(Pengguna::class, 'diverifikasi_oleh');
    }

    public function memilikiBuktiPembayaran(): bool
    {
        return filled($this->bukti_path)
            || filled(data_get($this->data_mentah, 'nama_file'))
            || filled(data_get($this->data_mentah, 'referensi'));
    }
}
