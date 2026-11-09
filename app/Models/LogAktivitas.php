<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use SoftDeletes;

    const UPDATED_AT = null;
    protected $table = 'log_aktivitas';

    protected $fillable = ['pengguna_id', 'aksi', 'tipe_objek', 'id_objek', 'deskripsi'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}