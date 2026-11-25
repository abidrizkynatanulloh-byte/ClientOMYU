<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CadanganBahan extends Model
{
    protected $table = 'cadangan_bahan';

    protected $fillable = [
        'pesanan_id',
        'bahan_id',
        'jumlah',
        'status',
    ];

    protected $casts = [
        'jumlah' => 'decimal:3',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function bahan(): BelongsTo
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    /**
     * @param  array<int, float>  $kebutuhan  jumlah per bahan_id yang dicadangkan untuk satu pesanan
     */
    public static function catatPesanan(int $pesananId, array $kebutuhan): void
    {
        foreach ($kebutuhan as $bahanId => $jumlah) {
            if ($jumlah <= 0) {
                continue;
            }

            static::create([
                'pesanan_id' => $pesananId,
                'bahan_id' => $bahanId,
                'jumlah' => $jumlah,
                'status' => 'aktif',
            ]);

            Bahan::whereKey($bahanId)->increment('stok_dicadangkan', $jumlah);
        }
    }

    public static function lepasPesanan(int $pesananId, string $status): void
    {
        static::where('pesanan_id', $pesananId)
            ->where('status', 'aktif')
            ->lockForUpdate()
            ->get()
            ->each(function (self $cadangan) use ($status): void {
                Bahan::whereKey($cadangan->bahan_id)
                    ->decrement('stok_dicadangkan', (float) $cadangan->jumlah);

                $cadangan->update(['status' => $status]);
            });
    }
}
