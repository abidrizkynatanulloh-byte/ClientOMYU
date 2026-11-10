<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    /**
     * K-14: Riwayat Aktivitas
     * Menampilkan log audit aktivitas staf/kasir (append-only, tidak dapat dihapus).
     */
    public function index(Request $request)
    {
        $query = LogAktivitas::with('pengguna')->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('cari')) {
            $query->where('deskripsi', 'like', '%' . $request->cari . '%');
        }

        $daftarAktivitas = $query->paginate(20)->withQueryString();

        return view('panel.staf.aktivitas.index', compact('daftarAktivitas'));
    }
}

