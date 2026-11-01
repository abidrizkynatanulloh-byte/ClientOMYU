<?php

namespace App\Models;

use App\Models\Concerns\PakaiBahasaIndonesia;
use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    use PakaiBahasaIndonesia;

    protected $table = 'item_pesanan';

    protected $fillable = ['pesanan_id', 'produk_id', 'nama_produk', 'harga', 'jumlah', 'subtotal'];

    protected $casts = [
        'harga'    => 'integer',
        'jumlah'   => 'integer',
        'subtotal' => 'integer',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}