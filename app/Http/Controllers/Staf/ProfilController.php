<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * K-01: Profil Saya
     */
    public function index()
    {
        $pengguna = auth()->user();

        return view('panel.staf.profil.index', compact('pengguna'));
    }

    /**
     * K-01: Ganti Kata Sandi Staf
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|string|min:6|confirmed',
        ], [
            'password_lama.required' => 'Kata sandi saat ini wajib diisi.',
            'password.min'           => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Kata sandi saat ini tidak sesuai.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        LogAktivitas::create([
            'pengguna_id' => $user->id,
            'aksi'        => 'ubah_password',
            'tipe_objek'  => 'pengguna',
            'id_objek'    => $user->id,
            'deskripsi'   => 'Staf ' . $user->nama . ' memperbarui kata sandi akun.',
        ]);

        return back()->with('sukses', 'Kata sandi Anda berhasil diperbarui.');
    }
}

