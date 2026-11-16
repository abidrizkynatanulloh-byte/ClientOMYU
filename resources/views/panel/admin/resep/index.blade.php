@extends('layouts.admin')

@section('title', 'Resep, HPP & Margin Menu')
@section('page_title', 'Resep & Analisis HPP Menu')
@section('badge', 'O-03')
@section('page_subtitle', 'Analisis harga pokok produksi (HPP) per cup, margin keuntungan, dan kapasitas stok menu secara otomatis.')

@section('header_actions')
    <a href="{{ route('panel.admin.bahan.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-bold text-xs transition-all shadow-xs">
        <i class="fa-solid fa-boxes-stacked"></i>
        <span>Kelola Persediaan Bahan</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Information Card -->
    <div class="bg-gradient-to-r from-brand-900 to-emerald-950 rounded-3xl p-6 text-white shadow-soft flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-white/10 text-brand-300 flex items-center justify-center font-bold text-xl border border-white/10 shrink-0">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-white">Perhitungan HPP & Kapasitas Real-Time</h3>
                <p class="text-xs text-brand-200/90 mt-0.5 max-w-2xl">
                    HPP bahan dihitung secara dinamis dari takaran resep dikali biaya rata-rata bahan di gudang. Kapasitas stok per cup ditentukan oleh bahan dengan stok paling terbatas.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="px-4 py-2 rounded-2xl bg-white/10 border border-white/10 text-center">
                <span class="text-[10px] text-brand-300 uppercase block font-bold">Total Menu & Varian</span>
                <span class="text-lg font-extrabold text-white">{{ count($menuAnalisis) }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-soft">
        <form method="GET" action="{{ route('panel.admin.resep.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-96 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" 
                       placeholder="Cari menu jus atau varian..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                @if(request('cari'))
                    <a href="{{ route('panel.admin.resep.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs">
                        Reset
                    </a>
                @endif
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-bold text-xs flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Cari Menu</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-6 font-bold">Menu Produk</th>
                        <th class="py-3.5 px-4 font-bold">Varian</th>
                        <th class="py-3.5 px-4 font-bold">Kategori</th>
                        <th class="py-3.5 px-4 font-bold text-right">Harga Jual</th>
                        <th class="py-3.5 px-4 font-bold text-right">HPP Bahan</th>
                        <th class="py-3.5 px-4 font-bold text-right">Laba Kotor</th>
                        <th class="py-3.5 px-4 font-bold text-center">Margin (%)</th>
                        <th class="py-3.5 px-4 font-bold text-center">Kapasitas Stok</th>
                        <th class="py-3.5 px-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($menuAnalisis as $m)
                        <tr class="hover:bg-brand-50/30 transition-colors">
                            <td class="py-4 px-6 font-extrabold text-slate-900">
                                {{ $m['nama_produk'] }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $m['nama_varian'] }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-500">
                                {{ $m['kategori'] }}
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-slate-900">
                                Rp {{ number_format($m['harga_jual'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                @if($m['ada_resep'])
                                    <span class="font-extrabold text-rose-600">
                                        Rp {{ number_format($m['hpp_bahan'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-md">
                                        <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Belum ada resep
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right font-extrabold text-brand-900">
                                Rp {{ number_format($m['laba_kotor'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($m['ada_resep'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $m['margin'] >= 50 ? 'bg-emerald-100 text-emerald-800' : ($m['margin'] >= 30 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $m['margin'] }}%
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($m['ada_resep'])
                                    @if($m['kapasitas_cup'] > 15)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-check text-[10px]"></i> {{ $m['kapasitas_cup'] }} Cup
                                        </span>
                                    @elseif($m['kapasitas_cup'] > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> {{ $m['kapasitas_cup'] }} Cup
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Habis (0 Cup)
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('panel.admin.resep.detail', $m['varian_id']) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-700 text-xs font-bold transition-all shadow-xs">
                                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                                    <span>Detail Resep</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-blender"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Menu Terdaftar</p>
                                <p class="text-xs text-slate-400 mt-1">Daftar produk dan varian akan tampil di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection