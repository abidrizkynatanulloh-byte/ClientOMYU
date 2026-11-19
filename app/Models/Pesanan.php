<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class Pesanan extends Model
{
    use SoftDeletes;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan', 'token_akses', 'jenis', 'nama_pembeli', 'telepon_pembeli',
        'email_pembeli', 'alamat_pengiriman', 'catatan', 'subtotal', 'ongkir',
        'total', 'metode_bayar', 'kode_unik', 'status_pembayaran', 'status_pesanan',
        'kedaluwarsa_pada', 'dibayar_pada', 'ditangani_oleh', 'nomor_resi',
        'uang_diterima', 'kembalian', 'jadwal_ambil_mulai', 'jadwal_ambil_selesai',
        'alasan_batal', 'diselesaikan_pada', 'shift_id',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'ongkir' => 'integer',
        'total' => 'integer',
        'kode_unik' => 'integer',
        'uang_diterima' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'kedaluwarsa_pada' => 'datetime',
        'dibayar_pada' => 'datetime',
        'jadwal_ambil_mulai' => 'datetime',
        'jadwal_ambil_selesai' => 'datetime',
        'diselesaikan_pada' => 'datetime',
        'shift_id' => 'integer',
    ];

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class, 'pesanan_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }

    public function penangan()
    {
        return $this->belongsTo(Pengguna::class, 'ditangani_oleh');
    }

    public function riwayatStok()
    {
        return $this->hasMany(RiwayatStok::class, 'pesanan_id');
    }

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function terbitkanTokenAkses(): string
    {
        do {
            $token = hash('sha256', Str::random(64));
        } while (static::withTrashed()->where('token_akses', $token)->exists());

        $this->token_akses = $token;

        return $token;
    }

    public function qrDapatDiterbitkan(): bool
    {
        return $this->status_pembayaran === 'lunas'
            && ! in_array($this->status_pesanan, ['dibatalkan', 'kedaluwarsa'], true)
            && filled($this->token_akses);
    }

    public function catatPenguranganStokProduksi(int $stafId): bool
    {
        $stokProdukDicatat = $this->riwayatStok()->where('jenis', 'keluar_pesanan')->exists();
        $stokBahanDicatat = DB::table('pergerakan_stok')
            ->where('rujukan_tipe', 'pesanan')
            ->where('rujukan_id', $this->id)
            ->where('jenis', 'pemakaian_resep')
            ->exists();

        if ($stokProdukDicatat && $stokBahanDicatat) {
            return false;
        }

        DB::transaction(function () use ($stafId, $stokProdukDicatat, $stokBahanDicatat) {
            $this->loadMissing('itemPesanan');

            foreach ($this->itemPesanan as $item) {
                if (! $stokProdukDicatat && $item->produk_id) {
                    $produk = Produk::whereKey($item->produk_id)->lockForUpdate()->first();

                    if (! $produk) {
                        continue;
                    }

                    if ($produk->stok < $item->jumlah) {
                        throw new RuntimeException("Stok {$produk->nama} tidak mencukupi untuk pesanan {$this->kode_pesanan}.");
                    }

                    $stokSesudah = $produk->stok - $item->jumlah;
                    $produk->update(['stok' => $stokSesudah]);

                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'pengguna_id' => $stafId,
                        'pesanan_id' => $this->id,
                        'jenis' => 'keluar_pesanan',
                        'jumlah' => -$item->jumlah,
                        'stok_setelah' => $stokSesudah,
                        'catatan' => 'Produksi pesanan '.$this->kode_pesanan,
                    ]);
                }

                if ($stokBahanDicatat) {
                    continue;
                }

                $resep = $item->resep_id
                    ? Resep::with('bahanList')->find($item->resep_id)
                    : ($item->varian_id
                        ? Resep::with('bahanList')->where('varian_id', $item->varian_id)->where('aktif', 1)->first()
                        : null);

                if (! $resep || $resep->bahanList->isEmpty()) {
                    continue;
                }

                $hppBahan = 0;
                $hppKemasan = 0;
                $biayaDibutuhkan = [];

                foreach ($resep->bahanList as $bahanResep) {
                    $bahan = Bahan::whereKey($bahanResep->bahan_id)->lockForUpdate()->first();

                    if (! $bahan) {
                        continue;
                    }

                    $takaran = (float) $bahanResep->pivot->takaran;
                    $kebutuhan = $takaran * $item->jumlah;
                    $biayaSatuan = (float) $bahan->biaya_rata_rata;
                    $biaya = $kebutuhan * $biayaSatuan;

                    if ($bahan->jenis === 'kemasan') {
                        $hppKemasan += $takaran * $biayaSatuan;
                    } else {
                        $hppBahan += $takaran * $biayaSatuan;
                    }

                    if ($kebutuhan <= 0) {
                        continue;
                    }

                    if ($bahan->stok_tersedia < $kebutuhan) {
                        throw new RuntimeException("Stok bahan {$bahan->nama} tidak mencukupi untuk pesanan {$this->kode_pesanan}.");
                    }

                    $stokSesudah = (float) $bahan->stok - $kebutuhan;
                    $bahan->update(['stok' => $stokSesudah]);

                    $biayaDibutuhkan[] = [
                        'bahan_id' => $bahan->id,
                        'jenis' => 'pemakaian_resep',
                        'jumlah' => -$kebutuhan,
                        'nilai' => $biaya,
                        'stok_setelah' => $stokSesudah,
                        'biaya_rata_rata_setelah' => $biayaSatuan,
                        'rujukan_tipe' => 'pesanan',
                        'rujukan_id' => $this->id,
                        'pengguna_id' => $stafId,
                        'alasan' => 'Resep pesanan '.$this->kode_pesanan,
                    ];
                }

                foreach ($biayaDibutuhkan as $catatan) {
                    DB::table('pergerakan_stok')->insert($catatan);
                }

                $item->update([
                    'resep_id' => $resep->id,
                    'hpp_bahan' => round($hppBahan, 2),
                    'hpp_kemasan' => round($hppKemasan, 2),
                ]);
            }
        });

        return true;
    }

    public function kembalikanStokPesanan(int $stafId): bool
    {
        $sudahDikembalikan = DB::table('pergerakan_stok')
            ->where('rujukan_tipe', 'pembatalan_pesanan')
            ->where('rujukan_id', $this->id)
            ->exists();

        if ($sudahDikembalikan) {
            return false;
        }

        DB::transaction(function () use ($stafId) {
            foreach ($this->riwayatStok()->where('jenis', 'keluar_pesanan')->get() as $riwayat) {
                $produk = Produk::whereKey($riwayat->produk_id)->lockForUpdate()->first();

                if (! $produk) {
                    continue;
                }

                $stokSesudah = $produk->stok + abs($riwayat->jumlah);
                $produk->update(['stok' => $stokSesudah]);

                RiwayatStok::create([
                    'produk_id' => $produk->id,
                    'pengguna_id' => $stafId,
                    'pesanan_id' => $this->id,
                    'jenis' => 'masuk',
                    'jumlah' => abs($riwayat->jumlah),
                    'stok_setelah' => $stokSesudah,
                    'catatan' => 'Pembatalan pesanan '.$this->kode_pesanan,
                ]);
            }

            $bahanDipakai = DB::table('pergerakan_stok')
                ->where('rujukan_tipe', 'pesanan')
                ->where('rujukan_id', $this->id)
                ->where('jenis', 'pemakaian_resep')
                ->get();

            foreach ($bahanDipakai as $catatan) {
                $bahan = Bahan::whereKey($catatan->bahan_id)->lockForUpdate()->first();

                if (! $bahan) {
                    continue;
                }

                $jumlah = abs((float) $catatan->jumlah);
                $stokSesudah = (float) $bahan->stok + $jumlah;
                $bahan->update(['stok' => $stokSesudah]);

                DB::table('pergerakan_stok')->insert([
                    'bahan_id' => $bahan->id,
                    'jenis' => 'penyesuaian',
                    'jumlah' => $jumlah,
                    'nilai' => abs((float) $catatan->nilai),
                    'stok_setelah' => $stokSesudah,
                    'biaya_rata_rata_setelah' => $catatan->biaya_rata_rata_setelah,
                    'rujukan_tipe' => 'pembatalan_pesanan',
                    'rujukan_id' => $this->id,
                    'pengguna_id' => $stafId,
                    'alasan' => 'Pembatalan pesanan '.$this->kode_pesanan,
                ]);
            }
        });

        return true;
    }

    public function validasiQrPickup(string $token): array
    {
        $token = trim($token);

        if ($token === '') {
            return ['valid' => false, 'alasan' => 'Token QR belum terbaca.'];
        }

        $pesanan = static::where('token_akses', $token)->first();

        if (! $pesanan) {
            return ['valid' => false, 'alasan' => 'Token QR tidak ditemukan.'];
        }

        if ($pesanan->status_pembayaran !== 'lunas') {
            return ['valid' => false, 'alasan' => 'Pembayaran pesanan belum dikonfirmasi.'];
        }

        if (in_array($pesanan->status_pesanan, ['dibatalkan', 'kedaluwarsa'], true)) {
            return ['valid' => false, 'alasan' => 'Pesanan sudah dibatalkan atau kedaluwarsa.'];
        }

        if ($pesanan->status_pesanan === 'selesai' || filled($pesanan->diselesaikan_pada)) {
            return ['valid' => false, 'alasan' => 'Pesanan ini sudah diambil.'];
        }

        if ($pesanan->status_pesanan !== 'siap_diambil') {
            return ['valid' => false, 'alasan' => 'Pesanan belum siap diambil.'];
        }

        if ($pesanan->kedaluwarsa_pada && $pesanan->kedaluwarsa_pada->isPast()) {
            return ['valid' => false, 'alasan' => 'Token QR sudah kedaluwarsa.'];
        }

        if ($pesanan->jadwal_ambil_selesai && $pesanan->jadwal_ambil_selesai->isPast()) {
            return ['valid' => false, 'alasan' => 'Jadwal pengambilan pesanan sudah berakhir.'];
        }

        return [
            'valid' => true,
            'pesanan' => $pesanan->loadMissing(['itemPesanan.produk', 'pembayaran']),
        ];
    }

    public function konfirmasiPengambilan(int $stafId, string $token): array
    {
        return DB::transaction(function () use ($stafId, $token) {
            $pesanan = static::whereKey($this->id)->lockForUpdate()->firstOrFail();
            $validasi = $pesanan->validasiQrPickup($token);

            if (! $validasi['valid']) {
                return $validasi;
            }

            if ($validasi['pesanan']->getKey() !== $pesanan->getKey()) {
                return ['valid' => false, 'alasan' => 'Token QR bukan milik pesanan ini.'];
            }

            $pesanan->status_pesanan = 'selesai';
            $pesanan->diselesaikan_pada = now();
            $pesanan->ditangani_oleh = $stafId;
            $pesanan->terbitkanTokenAkses();
            $pesanan->save();

            return [
                'valid' => true,
                'pesanan' => $pesanan->loadMissing(['itemPesanan.produk', 'pembayaran']),
            ];
        });
    }
}
