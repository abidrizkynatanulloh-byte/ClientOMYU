<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganUlasanController extends Controller
{
    /**
     * Simpan atau perbarui penilaian bintang untuk menu yang sudah dipesan
     * dan status pesanannya selesai.
     */
    public function simpan(Request $request)
    {
        $data = $request->validate([
            'pesanan_id' => ['required', 'integer', 'exists:pesanan,id'],
            'produk_id' => ['required', 'integer', 'exists:produk,id'],
            'bintang' => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['nullable', 'string', 'max:500'],
        ], [
            'pesanan_id.required' => 'Pesanan tidak ditemukan.',
            'produk_id.required' => 'Menu yang dinilai tidak ditemukan.',
            'bintang.required' => 'Bintang wajib dipilih.',
            'bintang.integer' => 'Bintang harus berupa angka.',
            'bintang.min' => 'Bintang minimal 1.',
            'bintang.max' => 'Bintang maksimal 5.',
            'komentar.max' => 'Komentar maksimal 500 karakter.',
        ]);

        $pelanggan = Auth::guard('pelanggan')->user();
        $pelanggan = $pelanggan instanceof Pelanggan ? $pelanggan : null;
        $deviceToken = $this->deviceToken($request);

        if (! $pelanggan && $deviceToken === '') {
            return back()->with('galat', 'Silakan login atau buka halaman lacak dari perangkat yang memesan untuk memberi bintang.');
        }

        $query = Pesanan::query()
            ->whereKey((int) $data['pesanan_id'])
            ->where('status_pesanan', 'selesai');

        if ($pelanggan) {
            $query->where(function ($q) use ($pelanggan, $deviceToken) {
                $q->where('pelanggan_id', $pelanggan->id);

                if ($deviceToken !== '') {
                    $q->orWhere(function ($guest) use ($deviceToken) {
                        $guest->whereNull('pelanggan_id')->where('device_token', $deviceToken);
                    });
                }
            });
        } else {
            $query->whereNull('pelanggan_id')->where('device_token', $deviceToken);
        }

        $pesanan = $query->first();

        if (! $pesanan) {
            return back()->with('galat', 'Penilaian hanya bisa diberikan untuk pesanan yang sudah selesai.');
        }

        if (! $pesanan->itemPesanan()->where('produk_id', (int) $data['produk_id'])->exists()) {
            return back()->with('galat', 'Menu itu tidak ditemukan di pesanan yang sudah selesai.');
        }

        $komentar = trim((string) ($data['komentar'] ?? ''));

        Ulasan::updateOrCreate([
            'pesanan_id' => $pesanan->id,
            'produk_id' => (int) $data['produk_id'],
        ], [
            'pelanggan_id' => $pelanggan?->id,
            'device_token' => $pelanggan ? null : $deviceToken,
            'bintang' => (int) $data['bintang'],
            'komentar' => $komentar !== '' ? $komentar : null,
        ]);

        return back()->with('sukses', 'Terima kasih! Penilaian bintangmu sudah disimpan.');
    }

    private function deviceToken(Request $request): string
    {
        $token = (string) $request->cookie('omyu_device_token', '');

        return preg_match('/\A[a-f0-9]{40,80}\z/', $token) ? $token : '';
    }
}
