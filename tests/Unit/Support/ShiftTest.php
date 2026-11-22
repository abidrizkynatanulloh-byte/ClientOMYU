<?php

namespace Tests\Unit\Support;

use App\Support\Shift;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ShiftTest extends TestCase
{
    public function test_menampilkan_shift_pagi_mulai_pukul_tujuh(): void
    {
        $info = Shift::info(Carbon::create(2026, 11, 21, 7, 0, 0, 'Asia/Jakarta'));

        $this->assertSame('Shift Pagi (07:00 - 16:00)', $info['label']);
        $this->assertTrue($info['aktif']);
        $this->assertFalse($info['selesaiShift']);
    }

    public function test_menampilkan_shift_sore_mulai_pukul_empat_belas(): void
    {
        $info = Shift::info(Carbon::create(2026, 11, 21, 16, 0, 0, 'Asia/Jakarta'));

        $this->assertSame('Shift Sore (16:00 - 22:00)', $info['label']);
        $this->assertTrue($info['aktif']);
        $this->assertFalse($info['selesaiShift']);
    }

    public function test_menandai_shift_selesai_mulai_pukul_dua_puluh_dua(): void
    {
        $info = Shift::info(Carbon::create(2026, 11, 21, 22, 0, 0, 'Asia/Jakarta'));

        $this->assertSame('Shift Selesai', $info['label']);
        $this->assertFalse($info['aktif']);
        $this->assertTrue($info['selesaiShift']);
        $this->assertSame('Shift Pagi besok (07:00 - 16:00)', $info['berikutnya']);
    }

    public function test_menandai_shift_selesai_sebelum_shift_pagi_dimulai(): void
    {
        $info = Shift::info(Carbon::create(2026, 11, 21, 6, 59, 0, 'Asia/Jakarta'));

        $this->assertTrue($info['selesaiShift']);
        $this->assertSame('Shift Pagi (07:00 - 16:00)', $info['berikutnya']);
    }
}
