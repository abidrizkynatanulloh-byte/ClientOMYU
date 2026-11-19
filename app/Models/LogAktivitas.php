<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    const UPDATED_AT = null;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'pengguna_id', 'aksi', 'tipe_objek', 'id_objek', 'deskripsi',
        'nilai_sebelum', 'nilai_sesudah', 'alasan', 'ip_address',
    ];

    protected $casts = [
        'nilai_sebelum' => 'array',
        'nilai_sesudah' => 'array',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
