<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function tampilkanLogin()
    {
        return view('auth.login');
    }

    public function masuk(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // hanya akun aktif yang boleh login (akun yang di-soft delete otomatis ditolak)
        $kredensial = $data + ['aktif' => 1];

        if (! Auth::attempt($kredensial, $request->boolean('ingat'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah, atau akun tidak aktif.'])
                ->onlyInput('email');
        }

                $request->session()->regenerate();

        // Cek peran pengguna: jika admin arahkan ke panel admin, jika karyawan ke panel biasa
        if (Auth::user()->peran === 'admin') {
            return redirect()->intended('/panel/admin');
        }

        return redirect()->intended('/panel');
    }
    public function keluar(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}