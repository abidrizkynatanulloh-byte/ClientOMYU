@extends('layouts.admin')

@section('title', 'Edit Bahan ' . $bahan->nama)
@section('page_title', 'Edit Bahan Baku')
@section('badge', $bahan->nama)
@section('page_subtitle', 'Perbarui konfigurasi satuan konversi, rendemen, batas peringatan, dan status keaktifan.')

@section('header_actions')
    <a href="{{ route('panel.admin.bahan.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Persediaan</span>
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
        
        <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Perbarui Data: {{ $bahan->nama }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">Stok gudang saat ini: <strong>{{ (float) $bahan->stok_tersedia }} {{ $bahan->satuan_dasar }}</strong></p>
            </div>
        </div>

        <form method="POST" action="{{ route('panel.admin.bahan.update', $bahan->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Bahan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Bahan Baku / Buah <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama', $bahan->nama) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                @error('nama')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Bahan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Jenis Kategori Bahan <span class="text-rose-500">*</span>
                </label>
                <select name="jenis" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <option value="buah" {{ old('jenis', $bahan->jenis) == 'buah' ? 'selected' : '' }}>Buah Segar</option>
                    <option value="bahan_lain" {{ old('jenis', $bahan->jenis) == 'bahan_lain' ? 'selected' : '' }}>Bahan Lain / Pembantu (Gula, Susu, Es)</option>
                    <option value="kemasan" {{ old('jenis', $bahan->jenis) == 'kemasan' ? 'selected' : '' }}>Kemasan (Cup, Tutup, Sedotan)</option>
                </select>
            </div>

            <!-- Satuan Beli & Satuan Dasar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Satuan Beli di Toko/Pasar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="satuan_beli" value="{{ old('satuan_beli', $bahan->satuan_beli) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Satuan Dasar di Gudang / Resep <span class="text-rose-500">*</span>
                    </label>
                    <select name="satuan_dasar" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="g" {{ old('satuan_dasar', $bahan->satuan_dasar) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="ml" {{ old('satuan_dasar', $bahan->satuan_dasar) == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                        <option value="pcs" {{ old('satuan_dasar', $bahan->satuan_dasar) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                    </select>
                </div>
            </div>

            <!-- Konversi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nilai Konversi Satuan Beli ke Dasar <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="any" name="konversi" value="{{ old('konversi', (float) $bahan->konversi) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
            </div>

            <!-- Rendemen Card -->
            <div class="rounded-2xl bg-brand-50/70 border border-brand-200 p-4">
                <label class="block text-xs font-extrabold text-brand-900 mb-1">
                    Rendemen Standar Daging Buah (%) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="0.01" min="1" max="100" name="rendemen_standar" value="{{ old('rendemen_standar', (float) $bahan->rendemen_standar) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-brand-300 bg-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 font-bold">
            </div>

            <!-- Batas Minimum Stok Kritis -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Batas Minimum Peringatan Stok (Satuan Dasar) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="any" name="batas_minimum" value="{{ old('batas_minimum', (float) $bahan->batas_minimum) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
            </div>

            <!-- Status Aktif -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Status Keaktifan Bahan <span class="text-rose-500">*</span>
                </label>
                <select name="aktif" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <option value="1" {{ old('aktif', $bahan->aktif) ? 'selected' : '' }}>Aktif (Digunakan dalam operasional & resep)</option>
                    <option value="0" {{ !old('aktif', $bahan->aktif) ? 'selected' : '' }}>Nonaktif (Disembunyikan dari resep)</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('panel.admin.bahan.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Perbarui Data Bahan</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection