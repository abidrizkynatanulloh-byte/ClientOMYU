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
     * K-02: Dashboard Operasional Staf
     * Menampilkan metrik operasional harian, antrean pesanan mendesak, dan status stok.
     */
    public function index()
    {
        $hariIni = Carbon::today();

        // 1. Counter status pesanan hari ini
        $pesananHariIni = Pesanan::whereDate('created_at', $hariIni);

        $totalPesananHariIni = (clone $pesananHariIni)->count();
        $pesananBaru = (clone $pesananHariIni)->where('status_pesanan', 'baru')->count();
        $pesananDiproses = (clone $pesananHariIni)->where('status_pesanan', 'diproses')->count();
        $pesananSiap = (clone $pesananHariIni)->where('status_pesanan', 'dikirim')->count(); // dikirim = siap diambil
        $pesananSelesai = (clone $pesananHariIni)->where('status_pesanan', 'selesai')->count();
        $pesananMenungguBayar = (clone $pesananHariIni)->where('status_pembayaran', 'menunggu')->count();

        // 2. Daftar pesanan yang sedang aktif dan perlu perhatian segera
        $antreanPesanan = Pesanan::with(['itemPesanan', 'penangan'])
            ->whereIn('status_pesanan', ['baru', 'diproses', 'dikirim'])
            ->orderByRaw("FIELD(status_pesanan, 'baru', 'diproses', 'dikirim')")
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        // 3. Status Stok Produk (K-02 Cek Ketersediaan Stok)
        $totalProduk = Produk::where('aktif', 1)->count();
        $produkHabis = Produk::where('aktif', 1)->where('stok', '<=', 0)->count();
        $produkMenipis = Produk::where('aktif', 1)->where('stok', '>', 0)->where('stok', '<=', 5)->count();
        $produkTersedia = Produk::where('aktif', 1)->where('stok', '>', 5)->count();

        // Produk dengan stok paling kritis
        $daftarStokKritis = Produk::where('aktif', 1)
            ->orderBy('stok', 'asc')
            ->take(6)
            ->get();

        return view('panel.staf.beranda', compact(
            'totalPesananHariIni',
            'pesananBaru',
            'pesananDiproses',
            'pesananSiap',
            'pesananSelesai',
            'pesananMenungguBayar',
            'antreanPesanan',
            'totalProduk',
            'produkHabis',
            'produkMenipis',
            'produkTersedia',
            'daftarStokKritis'
        ));
    }
}

