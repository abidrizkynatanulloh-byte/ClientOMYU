@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif')
@section('page_title', 'Ringkasan Eksekutif')
@section('badge', 'O-01')
@section('page_subtitle', 'Pantau performa penjualan, laba kotor, stok bahan kritis, dan menu terlaris secara real-time.')

@section('header_actions')
    <a href="{{ route('panel.admin.penjualan.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-bold text-xs shadow-sm transition-all hover:shadow-md">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>Lihat Semua Pesanan</span>
    </a>
@endsection

@section('content')
<div class="space-y-3">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

        <!-- Card 1: Penjualan Hari Ini -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-brand-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Penjualan Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm shadow-inner">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    Rp {{ number_format($omzetHariIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200 text-[10px]">
                        <i class="fa-solid fa-receipt text-[9px]"></i>
                        {{ $jumlahPesananHariIni }} Transaksi
                    </span>
                    <span class="text-slate-400 text-[10px]">Hari berjalan</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Omzet Bulan Ini -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Omzet Bulan Ini</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shadow-inner">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 text-[10px]">
                        <i class="fa-solid fa-check-circle text-[9px]"></i>
                        {{ $jumlahPesananBulanIni }} Selesai
                    </span>
                    <span class="text-slate-400 text-[10px]">{{ Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Volume Cup Terjual -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Volume Terjual</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shadow-inner">
                    <i class="fa-solid fa-wine-glass"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    {{ number_format($totalCupBulanIni, 0, ',', '.') }} <span class="text-sm font-bold text-slate-500">Cup</span>
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 text-[10px]">
                        <i class="fa-solid fa-glass-water text-[9px]"></i>
                        Fresh Juices
                    </span>
                    <span class="text-slate-400 text-[10px]">Bulan berjalan</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Estimasi Laba Kotor -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-lime-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Estimasi Laba Kotor</span>
                <div class="w-8 h-8 rounded-xl bg-lime-100 text-lime-800 flex items-center justify-center font-bold text-sm shadow-inner">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-xl font-extrabold text-brand-900 tracking-tight">
                    Rp {{ number_format($labaKotorBulanIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-lime-800 bg-lime-100 px-2 py-0.5 rounded-full border border-lime-300 text-[10px]">
                        <i class="fa-solid fa-percent text-[9px]"></i>
                        Margin: {{ $marginLabaKotor }}%
                    </span>
                    <span class="text-slate-400 text-[10px]">Setelah HPP Bahan</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Alert Banner: Stok -->
    @if($bahanKritis->isNotEmpty())
        <div class="rounded-2xl bg-gradient-to-r from-rose-50 via-red-50 to-amber-50 border border-rose-200 p-3 shadow-soft">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0 animate-pulse">
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
                <a href="{{ route('panel.admin.bahan.index', ['status' => 'kritis']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-xs shrink-0">
                    <span>Buka Persediaan</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    @else
        <div class="rounded-2xl bg-emerald-50/80 border border-emerald-200/80 p-3 px-5 flex items-center justify-between shadow-soft">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-emerald-950">Semua Stok Bahan Baku Aman</h4>
                    <p class="text-[10px] text-emerald-700">Persediaan buah segar, bahan pembantu, dan kemasan berada di atas batas minimum.</p>
                </div>
            </div>
            <a href="{{ route('panel.admin.bahan.index') }}" class="text-xs font-bold text-emerald-800 hover:text-emerald-950 underline shrink-0">Lihat Gudang</a>
        </div>
    @endif

    <!-- 2 Column Section: Top Products & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

        <!-- Top 5 Menu Terlaris -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-4 border border-slate-100 shadow-soft flex flex-col">
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
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-brand-50/50 border border-slate-100 transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-6 h-6 rounded-lg {{ $idx === 0 ? 'bg-amber-400 text-amber-950' : ($idx === 1 ? 'bg-slate-300 text-slate-800' : ($idx === 2 ? 'bg-amber-700/30 text-amber-900' : 'bg-slate-200 text-slate-600')) }} flex items-center justify-center text-[10px] font-black shrink-0">
                                    #{{ $idx + 1 }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 truncate">{{ $menu->nama_produk }}</h4>
                                    <p class="text-[10px] text-slate-400">Omzet: Rp {{ number_format($menu->total_omzet, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-brand-100 text-brand-900 text-[10px] font-extrabold shrink-0">
                                {{ $menu->total_terjual }} Cup
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-3 pt-3 border-t border-slate-100">
                <a href="{{ route('panel.admin.resep.index') }}" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl bg-slate-100 hover:bg-brand-100 hover:text-brand-900 text-slate-700 text-xs font-bold transition-all">
                    <span>Lihat HPP & Margin Semua Menu</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- 5 Pesanan Terbaru -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-4 border border-slate-100 shadow-soft flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Pesanan Terbaru</h3>
                    <p class="text-[10px] text-slate-500">Pantau transaksi kasir & pesanan online yang masuk</p>
                </div>
                <a href="{{ route('panel.admin.penjualan.index') }}" class="text-xs font-bold text-brand-700 hover:text-brand-900 flex items-center gap-1">
                    <span>Semua Pesanan</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
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
                    <table class="w-full text-left text-xs">
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
                                <tr class="hover:bg-slate-50/80 transition-colors">
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
                                        <a href="{{ route('panel.admin.penjualan.detail', $p->id) }}" class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-600 transition-colors">
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
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-slate-400 block text-[10px]">Total Transaksi Bulan Ini</span>
                        <span class="text-sm font-extrabold text-slate-800">{{ $jumlahPesananBulanIni }} Pesanan</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-slate-400 block text-[10px]">Rata-rata Penjualan Harian</span>
                        <span class="text-sm font-extrabold text-brand-800">
                            Rp {{ number_format(Carbon\Carbon::now()->day > 0 ? round($omzetBulanIni / Carbon\Carbon::now()->day) : 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
