<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $query = Bahan::query();

        // Filter pencarian nama bahan
        if ($request->filled('cari')) {
            $query->where('nama', 'like', "%{$request->cari}%");
        }

        // Filter jenis bahan (buah, bahan_lain, kemasan)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter status stok (kritis / aman)
        if ($request->status === 'kritis') {
            $query->whereRaw('(stok - stok_dicadangkan) <= batas_minimum');
        } elseif ($request->status === 'aman') {
            $query->whereRaw('(stok - stok_dicadangkan) > batas_minimum');
        }

        // Hitung Ringkasan Aset Persediaan (Bab 6 Buku Panduan)
        $semuaBahan = Bahan::where('aktif', 1)->get();
        $totalJenisBahan = $semuaBahan->count();
        $totalNilaiPersediaan = $semuaBahan->sum(function ($b) {
            return (float) $b->stok * (float) $b->biaya_rata_rata;
        });
        $jumlahBahanKritis = $semuaBahan->filter(fn($b) => $b->is_kritis)->count();

        $daftarBahan = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('panel.admin.bahan.index', compact(
            'daftarBahan',
            'totalJenisBahan',
            'totalNilaiPersediaan',
            'jumlahBahanKritis'
        ));
    }

    public function create()
    {
        return view('panel.admin.bahan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'             => ['required', 'string', 'max:100', 'unique:bahan,nama'],
            'jenis'            => ['required', 'in:buah,bahan_lain,kemasan'],
            'satuan_dasar'     => ['required', 'in:g,ml,pcs'],
            'satuan_beli'      => ['required', 'string', 'max:20'],
            'konversi'         => ['required', 'numeric', 'min:1'],
            'rendemen_standar' => ['required', 'numeric', 'min:1', 'max:100'],
            'batas_minimum'    => ['required', 'numeric', 'min:0'],
            'stok_awal'        => ['nullable', 'numeric', 'min:0'],
            'biaya_rata_rata'  => ['nullable', 'numeric', 'min:0'],
        ]);

        Bahan::create([
            'nama'             => $data['nama'],
            'jenis'            => $data['jenis'],
            'satuan_dasar'     => $data['satuan_dasar'],
            'satuan_beli'      => $data['satuan_beli'],
            'konversi'         => $data['konversi'],
            'rendemen_standar' => $data['rendemen_standar'],
            'batas_minimum'    => $data['batas_minimum'],
            'stok'             => $data['stok_awal'] ?? 0,
            'stok_dicadangkan' => 0,
            'biaya_rata_rata'  => $data['biaya_rata_rata'] ?? 0,
            'aktif'            => 1,
        ]);

        return redirect()->route('panel.admin.bahan.index')
            ->with('sukses', "Bahan {$data['nama']} berhasil didaftarkan ke sistem!");
    }

    public function edit($id)
    {
        $bahan = Bahan::findOrFail($id);
        return view('panel.admin.bahan.edit', compact('bahan'));
    }

    public function update(Request $request, $id)
    {
        $bahan = Bahan::findOrFail($id);

        $data = $request->validate([
            'nama'             => ['required', 'string', 'max:100', 'unique:bahan,nama,' . $bahan->id],
            'jenis'            => ['required', 'in:buah,bahan_lain,kemasan'],
            'satuan_dasar'     => ['required', 'in:g,ml,pcs'],
            'satuan_beli'      => ['required', 'string', 'max:20'],
            'konversi'         => ['required', 'numeric', 'min:1'],
            'rendemen_standar' => ['required', 'numeric', 'min:1', 'max:100'],
            'batas_minimum'    => ['required', 'numeric', 'min:0'],
            'aktif'            => ['required', 'boolean'],
        ]);

        $bahan->update($data);

        return redirect()->route('panel.admin.bahan.index')
            ->with('sukses', "Data bahan {$bahan->nama} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $bahan = Bahan::findOrFail($id);
        $bahan->delete(); // Soft delete agar riwayat transaksi masa lalu tidak rusak

        return redirect()->route('panel.admin.bahan.index')
            ->with('sukses', "Bahan {$bahan->nama} berhasil dinonaktifkan.");
    }
}