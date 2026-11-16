@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif')
@section('page_title', 'Ringkasan Eksekutif')
@section('badge', 'O-01')
@section('page_subtitle', 'Pantau performa penjualan, laba kotor, stok bahan kritis, dan menu terlaris secara real-time.')

@section('header_actions')
    <a href="{{ route('panel.admin.penjualan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-bold text-xs sm:text-sm shadow-sm transition-all hover:shadow-md">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>Lihat Semua Pesanan</span>
    </a>
@endsection

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Omzet Hari Ini -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brand-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Penjualan Hari Ini</span>
                <div class="w-11 h-11 rounded-2xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-base shadow-inner">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Rp {{ number_format($omzetHariIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-2 mt-3 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                        <i class="fa-solid fa-receipt text-[10px]"></i>
                        {{ $jumlahPesananHariIni }} Transaksi
                    </span>
                    <span class="text-slate-400">Hari berjalan</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Omzet Bulan Ini -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Omzet Bulan Ini</span>
                <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base shadow-inner">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-2 mt-3 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        <i class="fa-solid fa-check-circle text-[10px]"></i>
                        {{ $jumlahPesananBulanIni }} Selesai
                    </span>
                    <span class="text-slate-400">{{ Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Volume Cup Terjual -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Volume Terjual</span>
                <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-base shadow-inner">
                    <i class="fa-solid fa-wine-glass"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ number_format($totalCupBulanIni, 0, ',', '.') }} <span class="text-lg font-bold text-slate-500">Cup</span>
                </h3>
                <div class="flex items-center gap-2 mt-3 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                        <i class="fa-solid fa-glass-water text-[10px]"></i>
                        Fresh Juices
                    </span>
                    <span class="text-slate-400">Bulan berjalan</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Estimasi Laba Kotor & Margin -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft hover:shadow-card transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-lime-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Estimasi Laba Kotor</span>
                <div class="w-11 h-11 rounded-2xl bg-lime-100 text-lime-800 flex items-center justify-center font-bold text-base shadow-inner">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div class="relative">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-900 tracking-tight">
                    Rp {{ number_format($labaKotorBulanIni, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-2 mt-3 text-xs">
                    <span class="inline-flex items-center gap-1 font-bold text-lime-800 bg-lime-100 px-2 py-0.5 rounded-full border border-lime-300">
                        <i class="fa-solid fa-percent text-[10px]"></i>
                        Margin: {{ $marginLabaKotor }}%
                    </span>
                    <span class="text-slate-400">Setelah HPP Bahan</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Alert Banner: Peringatan Stok Kritis Bahan Baku -->
    @if($bahanKritis->isNotEmpty())
        <div class="rounded-3xl bg-gradient-to-r from-rose-50 via-red-50 to-amber-50 border border-rose-200 p-6 shadow-soft">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0 animate-pulse">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-extrabold text-rose-950">Peringatan: Stok Bahan Kritis!</h3>
                            <span class="px-2 py-0.5 rounded-full bg-rose-200 text-rose-800 text-[10px] font-bold">Perlu Restock</span>
                        </div>
                        <p class="text-xs text-rose-700 mt-0.5">
                            Terdapat <strong>{{ $bahanKritis->count() }} jenis bahan</strong> dengan sisa stok di bawah batas aman operasional.
                        </p>
                    </div>
                </div>
                <a href="{{ route('panel.admin.bahan.index', ['status' => 'kritis']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-xs self-start md:self-auto">
                    <span>Buka Manajemen Persediaan</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                @foreach($bahanKritis as $b)
                    <div class="bg-white/90 backdrop-blur-xs rounded-2xl p-3.5 border border-rose-100 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-bold text-slate-800 truncate">{{ $b->nama }}</span>
                                <span class="text-[10px] font-bold uppercase text-slate-400">{{ $b->jenis }}</span>
                            </div>
                            <div class="text-lg font-extrabold text-rose-600">
                                {{ (float) $b->stok_tersedia }} <span class="text-xs font-medium text-slate-500">{{ $b->satuan_dasar }}</span>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Batas min: {{ (float) $b->batas_minimum }} {{ $b->satuan_dasar }}</span>
                            <span class="text-rose-600 font-bold">Beli Segera</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-3xl bg-emerald-50/80 border border-emerald-200/80 p-4 px-6 flex items-center justify-between shadow-soft">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-950">Semua Stok Bahan Baku Aman</h4>
                    <p class="text-xs text-emerald-700">Persediaan buah segar, bahan pembantu, dan kemasan berada di atas batas minimum.</p>
                </div>
            </div>
            <a href="{{ route('panel.admin.bahan.index') }}" class="text-xs font-bold text-emerald-800 hover:text-emerald-950 underline">Lihat Gudang</a>
        </div>
    @endif

    <!-- 2 Column Section: Top Products & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Top 5 Menu Terlaris (5 Columns) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-100 shadow-soft flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">5 Menu Terlaris</h3>
                    <p class="text-xs text-slate-500">Berdasarkan volume transaksi pesanan selesai</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-sm"></i>
                </div>
            </div>

            @if($menuTerlaris->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center py-12 text-center text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mb-3">
                        <i class="fa-solid fa-lemon"></i>
                    </div>
                    <p class="text-sm font-semibold">Belum Ada Data Penjualan</p>
                    <p class="text-xs text-slate-400 mt-1">Transaksi selesai bulan ini akan otomatis dirangkum di sini.</p>
                </div>
            @else
                <div class="space-y-3.5 flex-1">
                    @foreach($menuTerlaris as $idx => $menu)
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 hover:bg-brand-50/50 border border-slate-100 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-xl {{ $idx === 0 ? 'bg-amber-400 text-amber-950' : ($idx === 1 ? 'bg-slate-300 text-slate-800' : ($idx === 2 ? 'bg-amber-700/30 text-amber-900' : 'bg-slate-200 text-slate-600')) }} flex items-center justify-center text-xs font-black shrink-0">
                                    #{{ $idx + 1 }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $menu->nama_produk }}</h4>
                                    <p class="text-[11px] text-slate-400">Total Omzet: Rp {{ number_format($menu->total_omzet, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-brand-100 text-brand-900 text-xs font-extrabold">
                                    {{ $menu->total_terjual }} Cup
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-5 pt-4 border-t border-slate-100">
                <a href="{{ route('panel.admin.resep.index') }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-100 hover:bg-brand-100 hover:text-brand-900 text-slate-700 text-xs font-bold transition-all">
                    <span>Lihat HPP & Margin Semua Menu</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- 5 Pesanan Terbaru (7 Columns) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-100 shadow-soft flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Pesanan Terbaru</h3>
                    <p class="text-xs text-slate-500">Pantau transaksi kasir & pesanan online yang masuk</p>
                </div>
                <a href="{{ route('panel.admin.penjualan.index') }}" class="text-xs font-bold text-brand-700 hover:text-brand-900 flex items-center gap-1">
                    <span>Semua Pesanan</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            @if($pesananTerbaru->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center py-12 text-center text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mb-3">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <p class="text-sm font-semibold">Belum Ada Transaksi</p>
                    <p class="text-xs text-slate-400 mt-1">Belum ada pesanan yang tercatat dalam sistem.</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-6 px-6">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                                <th class="pb-3 font-bold">Kode</th>
                                <th class="pb-3 font-bold">Waktu</th>
                                <th class="pb-3 font-bold">Jenis</th>
                                <th class="pb-3 font-bold">Total</th>
                                <th class="pb-3 font-bold">Status</th>
                                <th class="pb-3 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($pesananTerbaru as $p)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3">
                                        <span class="font-extrabold text-slate-800">#{{ $p->kode_pesanan }}</span>
                                    </td>
                                    <td class="py-3 text-slate-500">
                                        {{ $p->created_at->format('H:i') }} <span class="text-[10px] text-slate-400">({{ $p->created_at->format('d/m') }})</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p->jenis === 'online' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                            <i class="fa-solid {{ $p->jenis === 'online' ? 'fa-globe' : 'fa-store' }} text-[9px]"></i>
                                            {{ ucfirst($p->jenis) }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-bold text-slate-900">
                                        Rp {{ number_format($p->total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3">
                                        @if($p->status_pesanan === 'selesai')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-check text-[9px]"></i> Selesai
                                            </span>
                                        @elseif($p->status_pesanan === 'dibatalkan')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-xmark text-[9px]"></i> Batal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-clock text-[9px]"></i> {{ str_replace('_', ' ', $p->status_pesanan) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('panel.admin.penjualan.detail', $p->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-600 transition-colors">
                                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-5 pt-4 border-t border-slate-100">
                <div class="grid grid-cols-2 gap-3 text-center text-xs">
                    <div class="p-3 rounded-2xl bg-slate-50">
                        <span class="text-slate-400 block text-[11px]">Total Transaksi Bulan Ini</span>
                        <span class="text-base font-extrabold text-slate-800">{{ $jumlahPesananBulanIni }} Pesanan</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50">
                        <span class="text-slate-400 block text-[11px]">Rata-rata Penjualan Harian</span>
                        <span class="text-base font-extrabold text-brand-800">
                            Rp {{ number_format(Carbon\Carbon::now()->day > 0 ? round($omzetBulanIni / Carbon\Carbon::now()->day) : 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection