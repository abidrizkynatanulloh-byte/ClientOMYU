<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;

trait PakaiBahasaIndonesia
{
    use SoftDeletes;

    public function getCreatedAtColumn()
    {
        return 'dibuat_pada';
    }

    public function getUpdatedAtColumn()
    {
        return 'diperbarui_pada';
    }

    public function getDeletedAtColumn()
    {
        return 'dihapus_pada';
    }
}