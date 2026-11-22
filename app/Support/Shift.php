<?php

namespace App\Support;

use Carbon\Carbon;

class Shift
{
    public const MULAI_PAGI = 7;

    public const MULAI_SORE = 16;

    public const SELESAI = 22;

    public static function info(?Carbon $saat = null): array
    {
        $saat = $saat ?: now();
        $menit = ($saat->hour * 60) + $saat->minute;

        if ($menit >= self::MULAI_PAGI * 60 && $menit < self::MULAI_SORE * 60) {
            return [
                'label' => 'Shift Pagi (07:00 - 16:00)',
                'nama' => 'Shift Pagi',
                'selesaiPukul' => '16:00',
                'aktif' => true,
                'selesaiShift' => false,
                'berikutnya' => 'Shift Sore (16:00 - 22:00)',
            ];
        }

        if ($menit >= self::MULAI_SORE * 60 && $menit < self::SELESAI * 60) {
            return [
                'label' => 'Shift Sore (16:00 - 22:00)',
                'nama' => 'Shift Sore',
                'selesaiPukul' => '22:00',
                'aktif' => true,
                'selesaiShift' => false,
                'berikutnya' => 'Shift Pagi besok (07:00 - 16:00)',
            ];
        }

        return [
            'label' => 'Shift Selesai',
            'nama' => 'Shift Selesai',
            'selesaiPukul' => '22:00',
            'aktif' => false,
            'selesaiShift' => true,
            'berikutnya' => $menit < self::MULAI_PAGI * 60
                ? 'Shift Pagi (07:00 - 16:00)'
                : 'Shift Pagi besok (07:00 - 16:00)',
        ];
    }
}
