<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengguna::query();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('email', 'like', "%{$cari}%")
                  ->orWhere('telepon', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('peran')) {
            $query->where('peran', $request->peran);
        }

        if ($request->filled('status')) {
            $query->where('aktif', $request->status);
        }

        // Statistik Cepat Staf
        $semuaStaf = Pengguna::all();
        $totalStaf = $semuaStaf->count();
        $totalAdmin = $semuaStaf->where('peran', 'admin')->count();
        $totalKaryawanAktif = $semuaStaf->where('peran', 'karyawan')->where('aktif', true)->count();

        $daftarKaryawan = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('panel.admin.karyawan.index', compact(
            'daftarKaryawan',
            'totalStaf',
            'totalAdmin',
            'totalKaryawanAktif'
        ));
    }

    public function create()
    {
        return view('panel.admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'max:150', 'unique:pengguna,email'],
            'password'       => ['required', 'string', 'min:6'],
            'peran'          => ['required', 'in:admin,karyawan'],
            'telepon'        => ['nullable', 'string', 'max:20'],
            'bisa_lihat_hpp' => ['nullable', 'boolean'],
        ]);

        Pengguna::create([
            'nama'           => $data['nama'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'peran'          => $data['peran'],
            'telepon'        => $data['telepon'] ?? null,
            'aktif'          => 1,
            'bisa_lihat_hpp' => $request->boolean('bisa_lihat_hpp'),
        ]);

        return redirect()->route('panel.admin.karyawan.index')
            ->with('sukses', "Akun untuk {$data['nama']} berhasil dibuat!");
    }

    public function edit($id)
    {
        $karyawan = Pengguna::findOrFail($id);
        return view('panel.admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Pengguna::findOrFail($id);

        $data = $request->validate([
            'nama'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'max:150', 'unique:pengguna,email,' . $karyawan->id],
            'password'       => ['nullable', 'string', 'min:6'],
            'peran'          => ['required', 'in:admin,karyawan'],
            'telepon'        => ['nullable', 'string', 'max:20'],
            'aktif'          => ['required', 'boolean'],
            'bisa_lihat_hpp' => ['nullable', 'boolean'],
        ]);

        $updateData = [
            'nama'           => $data['nama'],
            'email'          => $data['email'],
            'peran'          => $data['peran'],
            'telepon'        => $data['telepon'] ?? null,
            'aktif'          => $data['aktif'],
            'bisa_lihat_hpp' => $request->boolean('bisa_lihat_hpp'),
        ];

        // Jika kolom password diisi, update password baru
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $karyawan->update($updateData);

        return redirect()->route('panel.admin.karyawan.index')
            ->with('sukses', "Data akun {$karyawan->nama} berhasil diperbarui!");
    }

    public function toggleStatus($id)
    {
        $karyawan = Pengguna::findOrFail($id);

        // Pengaman: Owner tidak boleh menonaktifkan dirinya sendiri
        if (auth()->id() === $karyawan->id) {
            return back()->with('galat', 'Anda tidak bisa menonaktifkan akun Anda sendiri!');
        }

        $karyawan->aktif = !$karyawan->aktif;
        $karyawan->save();

        $statusTeks = $karyawan->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('sukses', "Akun {$karyawan->nama} berhasil {$statusTeks}!");
    }
}