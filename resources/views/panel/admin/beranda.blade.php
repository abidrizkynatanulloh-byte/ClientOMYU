@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif')
@section('page_title', 'Ringkasan Eksekutif')
@section('badge', 'O-01')
@section('page_subtitle', 'Pantau performa penjualan, laba kotor, stok bahan kritis, dan menu terlaris secara real-time.')

@section('header_actions')
    <a href="{{ route('panel.admin.penjualan.index') }}" class="btn-press inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-bold text-xs shadow-sm hover:shadow-md whitespace-nowrap">
        <i class="fa-solid fa-cart-shopping text-xs"></i>
        <span><span class="hidden sm:inline">Semua </span>Pesanan</span>
    </a>
@endsection

@section('content')
<div class="space-y-4">

    <!-- Welcome Header Card (Inspired by Dashboard V35) -->
    <div class="anim-fade-up delay-1 glass-card rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 relative overflow-hidden">
        <div class="flex items-center gap-3.5 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-500 to-lime-300 text-brand-950 flex items-center justify-center font-black text-xl shadow-md shrink-0">
                <i class="fa-solid fa-lemon"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                        Selamat Datang Kembali, {{ auth()->user()->nama ?? 'Owner' }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        Sistem Aktif
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Pantau kinerja penjualan, laba kotor, dan ketersediaan stok OMYU Fresh Juice secara real-time.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold self-start md:self-auto relative z-10">
            <span class="px-3 py-1.5 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center gap-1.5">
                <i class="fa-regular fa-clock text-brand-600"></i>
                <span>Diperbarui: {{ Carbon\Carbon::now()->format('H:i') }} WIB</span>
            </span>
        </div>
    </div>

    <!-- KPI Metric Cards Grid (Staggered Animation + whileHover: y:-5 + Number Counters) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

        <!-- Card 1: Penjualan Hari Ini -->
        <div class="glass-card rounded-2xl p-4 relative overflow-hidden group anim-fade-up delay-2">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-brand-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Penjualan Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm shadow-inner group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight counter-value"
                    data-counter
                    data-counter-target="{{ $omzetHariIni }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($omzetHariIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center justify-between gap-2 mt-2 min-w-0">
                    <span class="inline-flex items-center gap-1 font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200 text-[10px] whitespace-nowrap shrink-0">
                        <i class="fa-solid fa-receipt text-[9px] shrink-0"></i>
                        <span>{{ $jumlahPesananHariIni }} Transaksi</span>
                    </span>
                    <!-- Sparkline SVG (Trend Visual) -->
                    <svg class="w-14 sm:w-16 h-5 text-brand-500 overflow-visible shrink-0" viewBox="0 0 60 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="sparkline-path" d="M2 16L15 12L30 14L45 6L58 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Omzet Bulan Ini -->
        <div class="glass-card rounded-2xl p-4 relative overflow-hidden group anim-fade-up delay-3">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Omzet Bulan Ini</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shadow-inner group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight counter-value"
                    data-counter
                    data-counter-target="{{ $omzetBulanIni }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center justify-between gap-2 mt-2 min-w-0">
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 text-[10px] whitespace-nowrap shrink-0">
                        <i class="fa-solid fa-check-circle text-[9px] shrink-0"></i>
                        <span>{{ $jumlahPesananBulanIni }} Selesai</span>
                    </span>
                    <!-- Sparkline SVG -->
                    <svg class="w-14 sm:w-16 h-5 text-emerald-500 overflow-visible shrink-0" viewBox="0 0 60 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="sparkline-path" d="M2 18L14 13L28 15L42 7L58 3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Volume Cup Terjual -->
        <div class="glass-card rounded-2xl p-4 relative overflow-hidden group anim-fade-up delay-4">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Volume Terjual</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shadow-inner group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-wine-glass"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    <span class="counter-value"
                          data-counter
                          data-counter-target="{{ $totalCupBulanIni }}">
                        {{ number_format($totalCupBulanIni, 0, ',', '.') }}
                    </span>
                    <span class="text-sm font-bold text-slate-500">Cup</span>
                </h3>
                <div class="flex items-center justify-between gap-2 mt-2 min-w-0">
                    <span class="inline-flex items-center gap-1 font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 text-[10px] whitespace-nowrap shrink-0">
                        <i class="fa-solid fa-glass-water text-[9px] shrink-0"></i>
                        <span>Fresh Juices</span>
                    </span>
                    <!-- Sparkline SVG -->
                    <svg class="w-14 sm:w-16 h-5 text-amber-500 overflow-visible shrink-0" viewBox="0 0 60 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="sparkline-path" d="M2 15L15 10L30 12L45 5L58 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 4: Estimasi Laba Kotor -->
        <div class="glass-card rounded-2xl p-4 relative overflow-hidden group anim-fade-up delay-5">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-lime-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Estimasi Laba Kotor</span>
                <div class="w-8 h-8 rounded-xl bg-lime-100 text-lime-800 flex items-center justify-center font-bold text-sm shadow-inner group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-brand-900 tracking-tight counter-value"
                    data-counter
                    data-counter-target="{{ $labaKotorBulanIni }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($labaKotorBulanIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center justify-between gap-2 mt-2 min-w-0">
                    <span class="inline-flex items-center gap-1 font-bold text-lime-800 bg-lime-100 px-2 py-0.5 rounded-full border border-lime-300 text-[10px] whitespace-nowrap shrink-0">
                        <i class="fa-solid fa-percent text-[9px] shrink-0"></i>
                        <span>Margin: {{ $marginLabaKotor }}%</span>
                    </span>
                    <!-- Sparkline SVG -->
                    <svg class="w-14 sm:w-16 h-5 text-lime-600 overflow-visible shrink-0" viewBox="0 0 60 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="sparkline-path" d="M2 16L18 14L32 8L44 10L58 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Alert Banner: Stok -->
    @if($bahanKritis->isNotEmpty())
        <div class="anim-fade-up delay-6 rounded-2xl bg-gradient-to-r from-rose-50/90 via-red-50/90 to-amber-50/90 border border-rose-200 p-3.5 shadow-soft hover-lift transition-all">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0 pulse-badge">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs font-extrabold text-rose-950">Peringatan: Stok Bahan Kritis!</h3>
                            <span class="px-2 py-0.5 rounded-full bg-rose-200 text-rose-800 text-[10px] font-bold">Perlu Restock</span>
                        </div>
                        <p class="text-[10px] text-rose-700 mt-0.5">Terdapat <strong>{{ $bahanKritis->count() }} jenis bahan</strong> dengan sisa stok di bawah batas aman.</p>
                    </div>
                </div>
                <a href="{{ route('panel.admin.bahan.index', ['status' => 'kritis']) }}" class="btn-press inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-xs shrink-0">
                    <span>Buka Persediaan</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    @else
        <div class="anim-fade-up delay-6 glass-card rounded-2xl border border-emerald-200/80 p-3.5 px-5 flex items-center justify-between shadow-soft hover-lift">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-emerald-950">Semua Stok Bahan Baku Aman</h4>
                    <p class="text-[10px] text-emerald-700">Persediaan buah segar, bahan pembantu, dan kemasan berada di atas batas minimum.</p>
                </div>
            </div>
            <a href="{{ route('panel.admin.bahan.index') }}" class="btn-press text-xs font-bold text-emerald-800 hover:text-emerald-950 underline shrink-0">Lihat Gudang</a>
        </div>
    @endif

    <!-- 2 Column Section: Top Products & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

        <!-- Top 5 Menu Terlaris -->
        <div class="lg:col-span-5 glass-card rounded-2xl p-4 flex flex-col anim-fade-up delay-7">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">5 Menu Terlaris</h3>
                    <p class="text-[10px] text-slate-500">Berdasarkan volume transaksi pesanan selesai</p>
                </div>
                <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-xs"></i>
                </div>
            </div>

            @if($menuTerlaris->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center py-6 text-center text-slate-400">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-xl mb-2">
                        <i class="fa-solid fa-lemon"></i>
                    </div>
                    <p class="text-xs font-semibold">Belum Ada Data Penjualan</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Transaksi selesai bulan ini akan otomatis dirangkum di sini.</p>
                </div>
            @else
                <div class="space-y-2 flex-1">
                    @foreach($menuTerlaris as $idx => $menu)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/80 hover:bg-brand-50/60 border border-slate-100 hover:border-brand-200 transition-all hover:translate-x-1 duration-200">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-6 h-6 rounded-lg {{ $idx === 0 ? 'bg-amber-400 text-amber-950' : ($idx === 1 ? 'bg-slate-300 text-slate-800' : ($idx === 2 ? 'bg-amber-700/30 text-amber-900' : 'bg-slate-200 text-slate-600')) }} flex items-center justify-center text-[10px] font-black shrink-0 shadow-xs">
                                    #{{ $idx + 1 }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 truncate">{{ $menu->nama_produk }}</h4>
                                    <p class="text-[10px] text-slate-400">Omzet: Rp {{ number_format($menu->total_omzet, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-brand-100 text-brand-900 text-[10px] font-extrabold shrink-0 border border-brand-200/60">
                                {{ $menu->total_terjual }} Cup
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-3 pt-3 border-t border-slate-100">
                <a href="{{ route('panel.admin.resep.index') }}" class="btn-press w-full flex items-center justify-center gap-2 py-2 rounded-xl bg-slate-100 hover:bg-brand-100 hover:text-brand-900 text-slate-700 text-xs font-bold transition-all">
                    <span>Lihat HPP & Margin Semua Menu</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- 5 Pesanan Terbaru -->
        <div class="lg:col-span-7 glass-card rounded-2xl p-4 flex flex-col anim-fade-up delay-8">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Pesanan Terbaru</h3>
                    <p class="text-[10px] text-slate-500">Pantau transaksi kasir & pesanan online yang masuk</p>
                </div>
                <a href="{{ route('panel.admin.penjualan.index') }}" class="text-xs font-bold text-brand-700 hover:text-brand-900 flex items-center gap-1 group">
                    <span>Semua Pesanan</span>
                    <i class="fa-solid fa-chevron-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>

            @if($pesananTerbaru->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center py-6 text-center text-slate-400">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-xl mb-2">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <p class="text-xs font-semibold">Belum Ada Transaksi</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Belum ada pesanan yang tercatat dalam sistem.</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-4 px-4">
                    <table class="w-full text-left text-xs admin-table">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                                <th class="pb-2 font-bold">Kode</th>
                                <th class="pb-2 font-bold">Waktu</th>
                                <th class="pb-2 font-bold">Jenis</th>
                                <th class="pb-2 font-bold">Total</th>
                                <th class="pb-2 font-bold">Status</th>
                                <th class="pb-2 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($pesananTerbaru as $p)
                                <tr class="hover:bg-brand-50/40 transition-colors">
                                    <td class="py-2">
                                        <span class="font-extrabold text-slate-800">#{{ $p->kode_pesanan }}</span>
                                    </td>
                                    <td class="py-2 text-slate-500">
                                        {{ $p->created_at->format('H:i') }} <span class="text-[10px] text-slate-400">({{ $p->created_at->format('d/m') }})</span>
                                    </td>
                                    <td class="py-2">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $p->jenis === 'online' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                            <i class="fa-solid {{ $p->jenis === 'online' ? 'fa-globe' : 'fa-store' }} text-[9px]"></i>
                                            {{ ucfirst($p->jenis) }}
                                        </span>
                                    </td>
                                    <td class="py-2 font-bold text-slate-900">
                                        Rp {{ number_format($p->total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2">
                                        @if($p->status_pesanan === 'selesai')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-check text-[9px]"></i> Selesai
                                            </span>
                                        @elseif($p->status_pesanan === 'dibatalkan')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-xmark text-[9px]"></i> Batal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-clock text-[9px]"></i> {{ str_replace('_', ' ', $p->status_pesanan) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('panel.admin.penjualan.detail', $p->id) }}" class="btn-press inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-600 transition-colors">
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-3 pt-3 border-t border-slate-100">
                <div class="grid grid-cols-2 gap-2 text-center text-xs">
                    <div class="p-2 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] leading-tight">Total Transaksi Bulan Ini</span>
                        <span class="text-sm font-extrabold text-slate-800 whitespace-nowrap">{{ $jumlahPesananBulanIni }} <span class="text-[11px] font-bold text-slate-500">Pesanan</span></span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] leading-tight">Rata-rata Penjualan Harian</span>
                        <span class="text-sm font-extrabold text-brand-800 whitespace-nowrap">
                            Rp {{ number_format(Carbon\Carbon::now()->day > 0 ? round($omzetBulanIni / Carbon\Carbon::now()->day) : 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
