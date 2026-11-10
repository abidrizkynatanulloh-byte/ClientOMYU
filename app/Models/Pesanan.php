<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use SoftDeletes;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan', 'token_akses', 'jenis', 'nama_pembeli', 'telepon_pembeli',
        'email_pembeli', 'alamat_pengiriman', 'catatan', 'subtotal', 'ongkir',
        'total', 'metode_bayar', 'kode_unik', 'status_pembayaran', 'status_pesanan',
        'kedaluwarsa_pada', 'dibayar_pada', 'ditangani_oleh', 'nomor_resi',
    ];

    protected $casts = [
        'subtotal'         => 'integer',
        'ongkir'           => 'integer',
        'total'            => 'integer',
        'kedaluwarsa_pada' => 'datetime',
        'dibayar_pada'     => 'datetime',
    ];

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class, 'pesanan_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }

    public function penangan()
    {
        return $this->belongsTo(Pengguna::class, 'ditangani_oleh');
    }

    public function riwayatStok()
    {
        return $this->hasMany(RiwayatStok::class, 'pesanan_id');
    }

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }
}