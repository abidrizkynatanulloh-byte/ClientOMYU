<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query dasar dengan eager loading agar efisien (tidak query berulang)
        $query = Pesanan::with(['penangan', 'itemPesanan'])->latest();

        // 2. Terapkan Filter jika dipilih oleh Owner
        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->metode_bayar);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('kode_pesanan', 'like', "%{$cari}%")
                  ->orWhere('nama_pembeli', 'like', "%{$cari}%");
            });
        }

        // 3. Hitung Ringkasan / Rekap dari data yang sedang terfilter
        $queryRekap = clone $query;
        $totalTransaksi = $queryRekap->count();

        // Omzet hanya dari transaksi yang selesai/lunas
        $omzetTerfilter = (clone $queryRekap)
            ->where('status_pesanan', 'selesai')
            ->sum('total');

        $transaksiSelesaiCount = (clone $queryRekap)
            ->where('status_pesanan', 'selesai')
            ->count();

        // Average Order Value (AOV): Rata-rata nilai belanja per transaksi
        $rataRataTransaksi = $transaksiSelesaiCount > 0 
            ? round($omzetTerfilter / $transaksiSelesaiCount) 
            : 0;

        // Rekap per metode bayar
        $rekapMetode = (clone $queryRekap)
            ->where('status_pesanan', 'selesai')
            ->select('metode_bayar', DB::raw('SUM(total) as total_nominal'), DB::raw('COUNT(*) as total_transaksi'))
            ->reorder()
            ->groupBy('metode_bayar')
            ->get();

        // 4. Ambil data dengan Pagination (15 baris per halaman)
        $daftarPesanan = $query->paginate(15)->withQueryString();

        return view('panel.admin.penjualan.index', compact(
            'daftarPesanan',
            'totalTransaksi',
            'omzetTerfilter',
            'rataRataTransaksi',
            'rekapMetode'
        ));
    }

    public function detail($id)
    {
        // Cari pesanan berdasarkan ID lengkap dengan relasi item dan staf kasirnya
        $pesanan = Pesanan::with(['itemPesanan', 'penangan', 'pembayaran'])->findOrFail($id);

        return view('panel.admin.penjualan.detail', compact('pesanan'));
    }
}