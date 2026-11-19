<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today()->toDateString();

        $ringkasan = Pesanan::selectRaw("
            SUM(CASE WHEN status_pesanan IN ('menunggu_pembayaran', 'menunggu_verifikasi') THEN 1 ELSE 0 END) AS baru,
            SUM(CASE WHEN status_pembayaran <> 'lunas' AND metode_bayar IN ('qris', 'transfer') THEN 1 ELSE 0 END) AS menunggu_verifikasi,
            SUM(CASE WHEN status_pesanan = 'diproses' THEN 1 ELSE 0 END) AS diproses,
            SUM(CASE WHEN status_pesanan = 'siap_diambil' THEN 1 ELSE 0 END) AS siap_diambil,
            SUM(CASE WHEN status_pesanan = 'selesai' AND COALESCE(DATE(diselesaikan_pada), DATE(updated_at)) = ? THEN 1 ELSE 0 END) AS selesai_hari_ini
        ", [$hariIni])->first();

        $pesananBaru = (int) $ringkasan->baru;
        $pesananVerif = (int) $ringkasan->menunggu_verifikasi;
        $pesananDiproses = (int) $ringkasan->diproses;
        $pesananSiap = (int) $ringkasan->siap_diambil;
        $pesananSelesaiHariIni = (int) $ringkasan->selesai_hari_ini;

        $antreanPesanan = Pesanan::with(['itemPesanan.produk', 'penangan'])
            ->whereIn('status_pesanan', ['menunggu_verifikasi', 'dikonfirmasi', 'diproses', 'siap_diambil'])
            ->orderByRaw("FIELD(status_pesanan, 'diproses', 'dikonfirmasi', 'menunggu_verifikasi', 'siap_diambil')")
            ->orderBy('created_at', 'asc')
            ->take(8)
            ->get();

        $ambangStok = (int) (DB::table('pengaturan')->where('kunci', 'ambang_stok_cup')->value('nilai') ?: 5);

        $stokRingkasan = DB::table('produk')
            ->selectRaw('
                COUNT(*) AS total,
                SUM(CASE WHEN stok > ? THEN 1 ELSE 0 END) AS tersedia,
                SUM(CASE WHEN stok > 0 AND stok <= ? THEN 1 ELSE 0 END) AS menipis,
                SUM(CASE WHEN stok <= 0 THEN 1 ELSE 0 END) AS habis
            ', [$ambangStok, $ambangStok])
            ->where('aktif', 1)
            ->first();

        $totalSku = (int) $stokRingkasan->total;
        $produkTersedia = (int) $stokRingkasan->tersedia;
        $produkMenipis = (int) $stokRingkasan->menipis;
        $produkHabis = (int) $stokRingkasan->habis;

        $namaMenipis = DB::table('produk')->where('aktif', 1)->where('stok', '>', 0)->where('stok', '<=', $ambangStok)->value('nama');
        $namaHabis = DB::table('produk')->where('aktif', 1)->where('stok', '<=', 0)->value('nama');

        $jam = Carbon::now()->hour;
        $shiftInfo = ($jam >= 7 && $jam < 16) ? 'Shift Pagi (07:00 - 16:00)' : 'Shift Sore (16:00 - 22:00)';
        $waktuUpdate = Carbon::now()->format('H:i').' WIB';

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
            'waktuUpdate',
            'ambangStok'
        ));
    }
}
