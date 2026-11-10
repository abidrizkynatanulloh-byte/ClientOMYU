<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{  
    use SoftDeletes;
    protected $table = 'item_pesanan';

    protected $fillable = [
        'pesanan_id', 'produk_id', 'varian_id', 'nama_produk', 'nama_varian',
        'harga', 'jumlah', 'subtotal', 'resep_id', 'hpp_bahan', 'hpp_kemasan'
    ];

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