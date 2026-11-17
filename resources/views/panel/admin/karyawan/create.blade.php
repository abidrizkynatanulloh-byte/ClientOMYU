@extends('layouts.admin')

@section('title', 'Tambah Karyawan Baru')
@section('page_title', 'Tambah Karyawan Baru')
@section('badge', 'O-09')
@section('page_subtitle', 'Buat akun staf kasir atau admin baru untuk mengakses sistem operasional OMYU.')

@section('header_actions')
    <a href="{{ route('panel.admin.karyawan.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Karyawan</span>
    </a>
@endsection

@section('content')
<div class="max-w-xl mx-auto">

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
        
        <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Formulir Akun Staf Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan email login, peran kewenangan, dan izin akses HPP.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('panel.admin.karyawan.store') }}" class="space-y-5">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Lengkap Staf <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" 
                       placeholder="Contoh: Budi Santoso, Siti Aisyah..."
                       required
                       class="w-full px-4 py-2.5 rounded-xl border @error('nama') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/40 @enderror text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                @error('nama')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Login -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Alamat Email (Login) <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       placeholder="Contoh: kasir1@omyu.test"
                       required
                       class="w-full px-4 py-2.5 rounded-xl border @error('email') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/40 @enderror text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nomor WhatsApp / Telepon
                </label>
                <input type="text" name="telepon" value="{{ old('telepon') }}" 
                       placeholder="Contoh: 081234567890"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
            </div>

            <!-- Kata Sandi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Kata Sandi (Password) <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required
                       class="w-full px-4 py-2.5 rounded-xl border @error('password') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/40 @enderror text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Peran Pengguna -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Peran / Hak Akses <span class="text-rose-500">*</span>
                </label>
                <select name="peran" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <option value="karyawan" {{ old('peran') == 'karyawan' ? 'selected' : '' }}>Karyawan / Kasir (Fokus transaksi kasir)</option>
                    <option value="admin" {{ old('peran') == 'admin' ? 'selected' : '' }}>Admin / Owner (Akses penuh ke semua modul bisnis)</option>
                </select>
            </div>

            <!-- Izin Lihat HPP Checkbox Card -->
            <div class="rounded-2xl bg-brand-50/60 border border-brand-200 p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="bisa_lihat_hpp" value="1" {{ old('bisa_lihat_hpp') ? 'checked' : '' }}
                           class="mt-1 w-4 h-4 rounded text-brand-600 focus:ring-brand-400 border-slate-300">
                    <div>
                        <span class="text-xs font-extrabold text-slate-900 block">
                            Beri Izin Melihat Nilai HPP dan Margin Menu
                        </span>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                            Secara default kasir tidak diperbolehkan melihat margin dan biaya HPP modal (Bab 1.6). Centang ini jika staf ini berwenang membantu perhitungan resep.
                        </p>
                    </div>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('panel.admin.karyawan.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs transition-all flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Buat Akun Staf</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection