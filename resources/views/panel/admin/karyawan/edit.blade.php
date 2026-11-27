@extends('layouts.admin')

@section('title', 'Edit Akun Staf ' . $karyawan->nama)
@section('page_title', 'Edit Akun Staf')
@section('badge', $karyawan->nama)
@section('page_subtitle', 'Perbarui data staf, hak akses peran, ubah kata sandi, atau atur perizinan HPP.')

@section('header_actions')
    <a href="{{ route('panel.admin.karyawan.index') }}" class="btn-press inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Karyawan</span>
    </a>
@endsection

@section('content')
<div class="max-w-xl mx-auto">

    <div class="glass-card anim-fade-up delay-1 rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
        
        <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Perbarui Akun: {{ $karyawan->nama }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">ID Pengguna: #{{ $karyawan->id }} • Terdaftar sejak {{ $karyawan->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('panel.admin.karyawan.update', $karyawan->id) }}" class="space-y-5"
              data-confirm="Simpan perubahan akun {{ $karyawan->nama }}?"
              data-confirm-title="Perbarui Akun Staf?"
              data-confirm-btn="Ya, Simpan"
              data-confirm-type="success">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Lengkap Staf <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama', $karyawan->nama) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                @error('nama')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Login -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Alamat Email (Login) <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $karyawan->email) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nomor WhatsApp / Telepon
                </label>
                <input type="text" name="telepon" value="{{ old('telepon', $karyawan->telepon) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
            </div>

            <!-- Ganti Password -->
            <div class="rounded-2xl bg-amber-50/60 border border-amber-200/80 p-4">
                <label class="block text-xs font-extrabold text-amber-950 mb-1">
                    Ganti Kata Sandi (Opsional)
                </label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mereset password"
                       class="w-full px-4 py-2.5 rounded-xl border border-amber-300 bg-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
                <span class="text-[11px] text-amber-800 mt-1 block">Minimal 6 karakter jika ingin mengganti sandi baru.</span>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Peran Pengguna & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Peran Hak Akses <span class="text-rose-500">*</span>
                    </label>
                    <select name="peran" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="karyawan" {{ old('peran', $karyawan->peran) == 'karyawan' ? 'selected' : '' }}>Karyawan / Kasir</option>
                        <option value="admin" {{ old('peran', $karyawan->peran) == 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Status Akun Login <span class="text-rose-500">*</span>
                    </label>
                    <select name="aktif" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="1" {{ old('aktif', $karyawan->aktif) ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                        <option value="0" {{ !old('aktif', $karyawan->aktif) ? 'selected' : '' }}>Nonaktif (Akses Dibekukan)</option>
                    </select>
                </div>
            </div>

            <!-- Izin Lihat HPP Checkbox Card -->
            <div class="rounded-2xl bg-brand-50/60 border border-brand-200 p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="bisa_lihat_hpp" value="1" {{ old('bisa_lihat_hpp', $karyawan->bisa_lihat_hpp) ? 'checked' : '' }}
                           class="mt-1 w-4 h-4 rounded text-brand-600 focus:ring-brand-400 border-slate-300">
                    <div>
                        <span class="text-xs font-extrabold text-slate-900 block">
                            Beri Izin Melihat Nilai HPP dan Margin Menu
                        </span>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                            Jika dicentang, pengguna ini diperbolehkan melihat data modal HPP per cup dan kalkulasi resep.
                        </p>
                    </div>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('panel.admin.karyawan.index') }}" class="btn-press px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="btn-press px-6 py-2.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Perbarui Akun Staf</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection