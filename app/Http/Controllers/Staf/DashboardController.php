<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * K-02: Dashboard Operasional Staf / Barista
     * Menampilkan metrik operasional harian, antrean prioritas cepat, dan status stok bahan.
     */
    public function index()
    {
        $hariIni = Carbon::today();

        // 1. Ringkasan Antrean (sesuai 4 kartu pada desain mockup)
        $pesananBaru = Pesanan::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi'])->count();
        $pesananDiproses = Pesanan::where('status_pesanan', 'diproses')->count();
        $pesananVerif = Pesanan::where('status_pesanan', 'dikonfirmasi')->count();
        $pesananSiap = Pesanan::where('status_pesanan', 'siap_diambil')->count();
        $pesananSelesaiHariIni = Pesanan::where('status_pesanan', 'selesai')->whereDate('created_at', $hariIni)->count();

        // 2. Antrean Prioritas Cepat (pesanan aktif yang mendesak)
        $antreanPesanan = Pesanan::with(['itemPesanan.produk', 'penangan'])
            ->whereIn('status_pesanan', ['menunggu_verifikasi', 'dikonfirmasi', 'diproses', 'siap_diambil'])
            ->orderByRaw("FIELD(status_pesanan, 'dikonfirmasi', 'diproses', 'menunggu_verifikasi', 'siap_diambil')")
            ->orderBy('created_at', 'asc')
            ->take(8)
            ->get();

        // 3. Status Bahan Baku Segar (Tersedia, Menipis, Habis)
        $totalSku = Produk::where('aktif', 1)->count();
        $produkTersedia = Produk::where('aktif', 1)->where('stok', '>', 5)->count();
        $produkMenipis = Produk::where('aktif', 1)->where('stok', '>', 0)->where('stok', '<=', 5)->count();
        $produkHabis = Produk::where('aktif', 1)->where('stok', '<=', 0)->count();

        $namaMenipis = Produk::where('aktif', 1)->where('stok', '>', 0)->where('stok', '<=', 5)->value('nama') ?? 'Mangga';
        $namaHabis = Produk::where('aktif', 1)->where('stok', '<=', 0)->value('nama') ?? 'Tomat';

        // 4. Informasi Shift & Waktu
        $jam = Carbon::now()->hour;
        $shiftInfo = ($jam >= 7 && $jam < 16) ? 'Shift Pagi (08:00 - 16:00)' : 'Shift Sore (16:00 - 22:00)';
        $waktuUpdate = Carbon::now()->format('H:i') . ' WIB';

        return view('panel.staf.beranda', compact(
            'pesananBaru',
            'pesananDiproses',
            'pesananVerif',
            'pesananSiap',
            'pesananSelesaiHariIni',
            'antreanPesanan',
            'totalSku',
            'produkTersedia',
            'produkMenipis',
            'produkHabis',
            'namaMenipis',
            'namaHabis',
            'shiftInfo',
            'waktuUpdate'
        ));
    }
}
