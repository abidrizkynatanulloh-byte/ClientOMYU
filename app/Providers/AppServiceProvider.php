<?php

namespace App\Providers;

use App\Models\Bahan;
use App\Models\Pesanan;
use App\Support\Shift;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.staf', function ($view) {
            if (! auth()->check()) {
                $view->with(['notifikasiStaf' => collect(), 'notifStafCount' => 0]);

                return;
            }

            $items = collect();
            $jumlah = 0;

            // Pesanan yang butuh perhatian (belum dibayar / perlu verifikasi)
            $menunggu = Pesanan::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi'])
                ->latest()
                ->take(3)
                ->get();

            $jumlah += Pesanan::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi'])->count();

            foreach ($menunggu as $pesanan) {
                $perluVerifikasi = $pesanan->status_pesanan === 'menunggu_verifikasi';

                $items->push([
                    'tone' => $perluVerifikasi ? 'danger' : 'warning',
                    'tag' => 'Pesanan',
                    'title' => ($perluVerifikasi ? 'Pembayaran ' : 'Pesanan ').$pesanan->kode_pesanan
                        .($perluVerifikasi ? ' perlu diverifikasi' : ' menunggu pembayaran'),
                    'teks' => $pesanan->nama_pembeli.' · Rp '.number_format((int) $pesanan->total, 0, ',', '.'),
                    'url' => route('panel.staf.pesanan.index', ['tab' => 'menunggu_cek']),
                    'waktu' => $pesanan->created_at,
                ]);
            }

            // Pesanan siap diambil
            $siapDiambil = Pesanan::where('status_pesanan', 'siap_diambil')
                ->latest()
                ->take(2)
                ->get();

            $jumlah += Pesanan::where('status_pesanan', 'siap_diambil')->count();

            foreach ($siapDiambil as $pesanan) {
                $items->push([
                    'tone' => 'success',
                    'tag' => 'Pesanan',
                    'title' => 'Pesanan '.$pesanan->kode_pesanan.' siap diambil',
                    'teks' => $pesanan->nama_pembeli.' menunggu pesanan diambil di outlet.',
                    'url' => route('panel.staf.pesanan.index', ['tab' => 'siap']),
                    'waktu' => $pesanan->updated_at,
                ]);
            }

            // Bahan baku kritis / habis
            $formatAngka = fn ($nilai) => rtrim(rtrim(number_format((float) $nilai, 2, ',', '.'), '0'), ',');

            $bahanKritis = Bahan::where('aktif', 1)
                ->whereRaw('stok <= batas_minimum')
                ->orderBy('stok')
                ->take(3)
                ->get();

            $jumlah += Bahan::where('aktif', 1)->whereRaw('stok <= batas_minimum')->count();

            foreach ($bahanKritis as $bahan) {
                $stok = (float) $bahan->stok;
                $batas = (float) $bahan->batas_minimum;
                $satuan = $bahan->satuan_dasar;

                $items->push([
                    'tone' => $stok <= 0 ? 'danger' : 'warning',
                    'tag' => 'Stok',
                    'title' => 'Stok '.$bahan->nama.($stok <= 0 ? ' habis' : ' menipis'),
                    'teks' => $stok <= 0
                        ? 'Segera lakukan restock agar menu tetap tersedia.'
                        : 'Tersisa '.$formatAngka($stok).' '.$satuan.' (batas minimum '.$formatAngka($batas).' '.$satuan.').',
                    'url' => route('panel.staf.stok.index', ['tab' => 'kritis']),
                    'waktu' => $bahan->updated_at,
                ]);
            }

            $shift = Shift::info();

            if ($shift['selesaiShift']) {
                $items->push([
                    'tone' => 'warning',
                    'tag' => 'Shift',
                    'title' => 'Shift kamu sudah selesai',
                    'teks' => 'Shift berakhir pukul '.$shift['selesaiPukul'].' WIB. '.$shift['berikutnya'].'.',
                    'url' => route('panel.beranda'),
                    'waktu' => now(),
                ]);

                $jumlah++;
            }

            $view->with([
                'notifikasiStaf' => $items->sortByDesc('waktu')->take(6)->values(),
                'notifStafCount' => $jumlah,
            ]);
        });
    }
}
