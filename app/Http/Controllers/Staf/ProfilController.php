<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password_lama.required' => 'Kata sandi saat ini wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Kata sandi saat ini tidak sesuai.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        LogAktivitas::create([
            'pengguna_id' => $user->id,
            'aksi' => 'ubah_password',
            'tipe_objek' => 'pengguna',
            'id_objek' => $user->id,
            'deskripsi' => 'Staf '.$user->nama.' memperbarui kata sandi akun.',
            'alasan' => 'Perubahan kata sandi oleh pemilik akun',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('sukses', 'Kata sandi Anda berhasil diperbarui.');
    }

    public function updateFoto(Request $request)
    {
        $hapusFoto = $request->boolean('hapus_foto');

        $request->validate([
            'foto' => ($hapusFoto ? 'nullable' : 'required').'|file|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto.required' => 'Foto profil wajib dipilih.',
            'foto.file' => 'Foto profil harus berupa berkas.',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $pengguna = $request->user();
        $fotoLama = $pengguna->foto;

        if ($hapusFoto) {
            $pengguna->foto = null;
        } else {
            $pengguna->foto = $request->file('foto')->store('foto-profil', 'public');
        }

        $pengguna->save();

        if ($fotoLama && $fotoLama !== $pengguna->foto) {
            Storage::disk('public')->delete($fotoLama);
        }

        LogAktivitas::create([
            'pengguna_id' => $pengguna->id,
            'aksi' => $hapusFoto ? 'hapus_foto_profil' : 'ubah_foto_profil',
            'tipe_objek' => 'pengguna',
            'id_objek' => $pengguna->id,
            'deskripsi' => 'Staf '.$pengguna->nama.($hapusFoto ? ' menghapus foto profil.' : ' memperbarui foto profil.'),
            'alasan' => $hapusFoto ? 'Penghapusan foto profil oleh pemilik akun' : 'Pembaruan foto profil oleh pemilik akun',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('sukses', $hapusFoto ? 'Foto profil berhasil dihapus.' : 'Foto profil berhasil diperbarui.');
    }
}
