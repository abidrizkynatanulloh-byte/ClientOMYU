<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AktivitasController extends Controller
{
    /**
     * K-14: Riwayat Aktivitas
     * Menampilkan log audit aktivitas staf/kasir (append-only, tidak dapat dihapus).
     */
    public function index(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date_format:Y-m-d',
            'aksi' => 'nullable|string|max:60',
            'cari' => 'nullable|string|max:100',
        ]);

        $query = LogAktivitas::with('pengguna')->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('deskripsi', 'like', "%{$cari}%")
                    ->orWhere('alasan', 'like', "%{$cari}%");
            });
        }

        $daftarAktivitas = $query->paginate(20)->withQueryString();

        // Statistik hari ini
        $totalHariIni = LogAktivitas::whereDate('created_at', Carbon::today())->count();

        $transaksiHariIni = LogAktivitas::whereDate('created_at', Carbon::today())
            ->whereIn('aksi', [
                'penjualan_langsung', 'verifikasi_pembayaran', 'update_status_pesanan',
                'batalkan_pesanan', 'penyelesaian_pesanan', 'konfirmasi_pengambilan',
            ])
            ->count();

        $stokProduksiHariIni = LogAktivitas::whereDate('created_at', Carbon::today())
            ->whereIn('aksi', [
                'mulai_racik_produksi', 'selesai_racik_produksi', 'catat_waste_gagal_racik',
                'pasokan_bahan_masuk', 'pembuangan_bahan_rusak', 'audit_stok_bahan',
                'penyesuaian_stok', 'toggle_status_bahan', 'ubah_password',
            ])
            ->count();

        return view('panel.staf.aktivitas.index', compact(
            'daftarAktivitas',
            'totalHariIni',
            'transaksiHariIni',
            'stokProduksiHariIni'
        ));
    }
}
