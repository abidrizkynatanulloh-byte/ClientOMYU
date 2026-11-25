<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Bahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bahan';

    protected $fillable = [
        'nama',
        'jenis',
        'satuan_dasar',
        'satuan_beli',
        'konversi',
        'rendemen_standar',
        'batas_minimum',
        'stok',
        'stok_dicadangkan',
        'biaya_rata_rata',
        'aktif',
    ];

    protected $casts = [
        'konversi' => 'decimal:3',
        'rendemen_standar' => 'decimal:2',
        'batas_minimum' => 'decimal:3',
        'stok' => 'decimal:3',
        'stok_dicadangkan' => 'decimal:3',
        'biaya_rata_rata' => 'decimal:4',
        'aktif' => 'boolean',
    ];

    // Helper: menghitung stok yang benar-benar siap dipakai (stok fisik - cadangan pesanan)
    public function getStokTersediaAttribute(): float
    {
        return max(0, (float) $this->stok - (float) $this->stok_dicadangkan);
    }

    // Helper: mengecek apakah stok sudah di bawah batas minimum
    public function getIsKritisAttribute(): bool
    {
        return $this->stok_tersedia <= (float) $this->batas_minimum;
    }

    // Harga beli per satuan beli (kg, pack, dll.) untuk ditampilkan kembali di form.
    public function getHargaBeliAttribute(): float
    {
        $konversi = max(1.0, (float) $this->konversi);

        return round((float) $this->biaya_rata_rata * $konversi, 4);
    }

    // Relasi ke riwayat pembelian bahan
    public function pembelian()
    {
        return $this->hasMany(PembelianBahan::class, 'bahan_id');
    }

    public function konversiKeDasar(string $satuan, float $jumlah): float
    {
        $satuan = strtolower(trim($satuan));
        $dasar = strtolower((string) $this->satuan_dasar);

        if ($satuan === $dasar) {
            return $jumlah;
        }

        if ($this->satuan_beli && $satuan === strtolower((string) $this->satuan_beli)) {
            return $jumlah * (float) $this->konversi;
        }

        $faktor = match ($satuan) {
            'kg' => $dasar === 'g' ? 1000 : 1,
            'gram', 'gr', 'g' => $dasar === 'g' ? 1 : 1,
            'liter', 'l' => $dasar === 'ml' ? 1000 : 1,
            'ml' => 1,
            'pack' => $dasar === 'pcs' ? (float) ($this->konversi ?: 50) : 1,
            default => 1,
        };

        return $jumlah * $faktor;
    }

    public function catatPergerakan(
        string $jenis,
        float $jumlah,
        float $stokSesudah,
        ?string $rujukanTipe = null,
        ?int $rujukanId = null,
        ?int $stafId = null,
        ?string $alasan = null,
        float $nilai = 0
    ): void {
        DB::table('pergerakan_stok')->insert([
            'bahan_id' => $this->id,
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'nilai' => $nilai,
            'stok_setelah' => $stokSesudah,
            'biaya_rata_rata_setelah' => (float) $this->biaya_rata_rata,
            'rujukan_tipe' => $rujukanTipe,
            'rujukan_id' => $rujukanId,
            'pengguna_id' => $stafId ?? auth()->id(),
            'alasan' => $alasan,
        ]);
    }
}
