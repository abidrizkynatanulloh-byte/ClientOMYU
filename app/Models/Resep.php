<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $table = 'resep';

    protected $fillable = [
        'varian_id',
        'versi',
        'berlaku_mulai',
        'aktif',
        'alasan_perubahan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'versi'         => 'integer',
        'aktif'         => 'boolean',
        'berlaku_mulai' => 'datetime',
    ];

    public function varian()
    {
        return $this->belongsTo(VarianProduk::class, 'varian_id');
    }

    public function bahanList()
    {
        return $this->belongsToMany(Bahan::class, 'resep_bahan', 'resep_id', 'bahan_id')
            ->withPivot('takaran');
    }
}