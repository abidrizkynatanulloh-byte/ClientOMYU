<?php

namespace App\Models;

use App\Models\Concerns\PakaiBahasaIndonesia;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use PakaiBahasaIndonesia;

    protected $table = 'log_aktivitas';

    protected $fillable = ['pengguna_id', 'aksi', 'tipe_objek', 'id_objek', 'deskripsi'];

    public function getUpdatedAtColumn()
    {
        return null;
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}