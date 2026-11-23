<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelangganAuthController extends Controller
{
    public function tampilkanLogin(Request $request)
    {
        if (Auth::guard('pelanggan')->check()) {
            return redirect()->route('pelanggan.katalog');
        }

        return view('pelanggan.auth-login', [
            'telepon' => preg_replace('/\D+/', '', (string) $request->query('telepon', old('telepon', ''))),
        ]);
    }

    public function cekTelepon(Request $request)
    {
        $data = $request->validate([
            'telepon' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{8,20}$/'],
        ], [
            'telepon.required' => 'Nomor HP wajib diisi.',
            'telepon.regex' => 'Format nomor HP tidak valid.',
        ]);

        $telepon = preg_replace('/\D+/', '', $data['telepon']);
        $pelanggan = Pelanggan::query()->where('telepon', $telepon)->first();

        return redirect()->route('pelanggan.login', [
            'telepon' => $telepon,
            'mode' => $pelanggan ? 'login' : 'daftar',
        ]);
    }

    public function masuk(Request $request)
    {
        $data = $request->validate([
            'telepon' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'password' => ['required', 'string'],
        ], [
            'telepon.required' => 'Nomor HP wajib diisi.',
            'telepon.regex' => 'Format nomor HP tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $telepon = preg_replace('/\D+/', '', $data['telepon']);
        $pelanggan = Pelanggan::query()->where('telepon', $telepon)->first();

        if (! $pelanggan || ! $pelanggan->password || ! Hash::check($data['password'], $pelanggan->password)) {
            return back()
                ->withErrors(['telepon' => 'Nomor HP atau password salah.'])
                ->withInput(['telepon' => $telepon, 'mode' => 'login']);
        }

        Auth::guard('pelanggan')->login($pelanggan, true);
        $request->session()->regenerate();

        return redirect()->intended(route('pelanggan.katalog'))
            ->with('sukses', 'Berhasil masuk. Selamat berbelanja!');
    }

    public function daftar(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'telepon.required' => 'Nomor HP wajib diisi.',
            'telepon.regex' => 'Format nomor HP tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $telepon = preg_replace('/\D+/', '', $data['telepon']);
        $pelanggan = Pelanggan::query()->where('telepon', $telepon)->first();

        if ($pelanggan && $pelanggan->password) {
            return back()
                ->withErrors(['telepon' => 'Nomor HP sudah terdaftar. Silakan login.'])
                ->withInput(['telepon' => $telepon, 'nama' => $data['nama'], 'mode' => 'login']);
        }

        if ($pelanggan) {
            $pelanggan->update([
                'nama' => trim($data['nama']),
                'password' => $data['password'],
            ]);
        } else {
            $pelanggan = Pelanggan::create([
                'nama' => trim($data['nama']),
                'telepon' => $telepon,
                'password' => $data['password'],
            ]);
        }

        Auth::guard('pelanggan')->login($pelanggan, true);
        $request->session()->regenerate();

        return redirect()->intended(route('pelanggan.katalog'))
            ->with('sukses', 'Akun berhasil dibuat. Selamat berbelanja!');
    }

    public function keluar(Request $request)
    {
        Auth::guard('pelanggan')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('pelanggan.katalog')->with('sukses', 'Berhasil keluar.');
    }
}
