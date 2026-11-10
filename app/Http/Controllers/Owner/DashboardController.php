<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();
        $awalBulan = Carbon::now()->startOfMonth();

        // 1. Hitung Ringkasan Penjualan Hari Ini & Bulan Ini (hanya pesanan yang statusnya 'selesai')
        $pesananSelesaiBulanIni = Pesanan::where('status_pesanan', 'selesai')
            ->where('created_at', '>=', $awalBulan);

        $omzetBulanIni = (clone $pesananSelesaiBulanIni)->sum('total');
        $jumlahPesananBulanIni = (clone $pesananSelesaiBulanIni)->count();

        $pesananSelesaiHariIni = Pesanan::where('status_pesanan', 'selesai')
            ->whereDate('created_at', $hariIni);

        $omzetHariIni = (clone $pesananSelesaiHariIni)->sum('total');
        $jumlahPesananHariIni = (clone $pesananSelesaiHariIni)->count();

        // 2. Hitung Total Cup Terjual Bulan Ini (dari item_pesanan)
        $totalCupBulanIni = ItemPesanan::whereHas('pesanan', function ($q) use ($awalBulan) {
            $q->where('status_pesanan', 'selesai')
              ->where('created_at', '>=', $awalBulan);
        })->sum('jumlah');

        // 3. Hitung Estimasi Total HPP Bahan & Laba Kotor Bulan Ini
        // Formula: Laba Kotor = Omzet - HPP (Sesuai Bab 2 & 6 Buku Panduan)
        $totalHppBulanIni = ItemPesanan::whereHas('pesanan', function ($q) use ($awalBulan) {
            $q->where('status_pesanan', 'selesai')
              ->where('created_at', '>=', $awalBulan);
        })->sum(DB::raw('COALESCE(hpp_bahan, 0) * jumlah'));

        $labaKotorBulanIni = $omzetBulanIni - $totalHppBulanIni;
        $marginLabaKotor = $omzetBulanIni > 0 
            ? round(($labaKotorBulanIni / $omzetBulanIni) * 100, 1) 
            : 0;

        // 4. Bahan yang Stoknya Kritis (stok tersedia <= batas_minimum)
        // Fitur O-01: Owner langsung melihat bahan apa yang perlu dibeli
        $bahanKritis = Bahan::where('aktif', 1)
            ->whereRaw('(stok - stok_dicadangkan) <= batas_minimum')
            ->orderByRaw('(stok - stok_dicadangkan) ASC')
            ->take(5)
            ->get();

        // 5. 5 Menu Terlaris Bulan Ini (Top Products)
        $menuTerlaris = ItemPesanan::select(
                'nama_produk',
                DB::raw('SUM(jumlah) as total_terjual'),
                DB::raw('SUM(subtotal) as total_omzet')
            )
            ->whereHas('pesanan', function ($q) use ($awalBulan) {
                $q->where('status_pesanan', 'selesai')
                  ->where('created_at', '>=', $awalBulan);
            })
            ->groupBy('nama_produk')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        // 6. 5 Pesanan Terbaru (untuk monitoring aktivitas kasir & online)
        $pesananTerbaru = Pesanan::latest()
            ->take(5)
            ->get();

        // Kirim semua variabel hitungan ini ke view owner
        return view('panel.admin.beranda', compact(
            'omzetHariIni',
            'jumlahPesananHariIni',
            'omzetBulanIni',
            'jumlahPesananBulanIni',
            'totalCupBulanIni',
            'totalHppBulanIni',
            'labaKotorBulanIni',
            'marginLabaKotor',
            'bahanKritis',
            'menuTerlaris',
            'pesananTerbaru'
        ));
    }
}