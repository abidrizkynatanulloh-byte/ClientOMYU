@extends('layouts.admin')

@section('title', 'Rekap Penjualan & Pesanan')
@section('page_title', 'Penjualan & Pesanan')
@section('badge', 'O-02')
@section('page_subtitle', 'Pantau seluruh transaksi pesanan kasir outlet dan pesanan online.')

@section('header_actions')
    <a href="{{ route('panel.admin.penjualan.index') }}" 
       title="Perbarui Data Pesanan"
       class="btn-press group inline-flex items-center justify-center gap-2 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl bg-white hover:bg-brand-50 text-slate-700 hover:text-brand-800 border border-slate-200/90 hover:border-brand-300 shadow-xs text-xs font-bold transition-all">
        <i class="fa-solid fa-arrows-rotate text-brand-600 text-xs sm:text-sm transition-transform duration-300 group-hover:rotate-180 group-active:rotate-180"></i>
        <span class="hidden sm:inline">Refresh</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Ringkasan Nilai Terfilter KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-1">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Transaksi</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5 counter-value"
                    data-counter
                    data-counter-target="{{ $totalTransaksi }}">
                    {{ number_format($totalTransaksi, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Termasuk semua status pesanan</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-2">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Omzet Terfilter</span>
                <h3 class="text-2xl font-extrabold text-emerald-700 tracking-tight mt-0.5 counter-value"
                    data-counter
                    data-counter-target="{{ $omzetTerfilter }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($omzetTerfilter, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Dari pesanan berstatus selesai</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-3">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Rata-rata Belanja (AOV)</span>
                <h3 class="text-2xl font-extrabold text-amber-700 tracking-tight mt-0.5 counter-value"
                    data-counter
                    data-counter-target="{{ $rataRataTransaksi }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Rata-rata omzet per pelanggan</span>
            </div>
        </div>

    </div>

    <!-- Breakdown Metode Pembayaran Chips -->
    @if($rekapMetode->isNotEmpty())
        <div class="glass-card rounded-2xl p-4 flex flex-wrap items-center gap-3 anim-fade-up delay-3">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5 mr-2">
                <i class="fa-solid fa-wallet text-brand-600"></i> Rekap Metode:
            </span>
            @foreach($rekapMetode as $metode)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50/90 border border-slate-200/80 text-xs hover:border-brand-300 transition-colors">
                    <span class="font-bold uppercase text-slate-700">{{ $metode->metode_bayar ?? 'Lainnya' }}</span>
                    <span class="text-slate-400">|</span>
                    <span class="font-extrabold text-brand-800">Rp {{ number_format($metode->total_nominal, 0, ',', '.') }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-md bg-white text-slate-500 font-semibold border border-slate-200">
                        {{ $metode->total_transaksi }}x
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Filter Bar Card -->
    <div class="glass-card rounded-3xl p-5 anim-fade-up delay-4">
        <form method="GET" action="{{ route('panel.admin.penjualan.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
                
                <!-- Search -->
                <div class="lg:col-span-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Cari Pesanan</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="cari" value="{{ request('cari') }}" 
                               placeholder="Kode pesanan / nama pembeli..."
                               class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status Pesanan</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="">Semua Status</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Bayar</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Metode Bayar Filter -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Metode Bayar</label>
                    <select name="metode_bayar" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="">Semua Metode</option>
                        <option value="qris" {{ request('metode_bayar') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="tunai" {{ request('metode_bayar') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ request('metode_bayar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>

                <!-- Jenis Filter -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Jenis</label>
                    <select name="jenis" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="">Semua</option>
                        <option value="online" {{ request('jenis') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="langsung" {{ request('jenis') == 'langsung' ? 'selected' : '' }}>Kasir</option>
                    </select>
                </div>

                <!-- Dari Tanggal -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                </div>

                <!-- Sampai Tanggal -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                </div>

            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <span class="text-xs text-slate-400">
                    Menampilkan <strong>{{ $daftarPesanan->total() }}</strong> hasil transaksi
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['cari', 'status', 'metode_bayar', 'jenis', 'dari_tanggal', 'sampai_tanggal']))
                        <a href="{{ route('panel.admin.penjualan.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit" class="btn-press px-5 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs shadow-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Data Table Card -->
    <div class="glass-card rounded-3xl border border-slate-100 shadow-soft overflow-hidden anim-fade-up delay-5"
         x-data="{ detailOpen: false, selected: null, openDetail(data) { this.selected = data; this.detailOpen = true; }, closeDetail() { this.detailOpen = false; } }"
         @keydown.escape.window="closeDetail()">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm admin-table">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-5 font-bold hidden md:table-cell">Kode Pesanan</th>
                        <th class="py-3.5 px-4 font-bold hidden md:table-cell">Waktu Transaksi</th>
                        <th class="py-3.5 px-4 font-bold">Jenis</th>
                        <th class="py-3.5 px-4 font-bold">Pembeli</th>
                        <th class="py-3.5 px-4 font-bold hidden md:table-cell">Total Pembayaran</th>
                        <th class="py-3.5 px-4 font-bold hidden md:table-cell">Metode</th>
                        <th class="py-3.5 px-4 font-bold">Status Pesanan</th>
                        <th class="py-3.5 px-4 font-bold hidden md:table-cell">Kasir / Penangan</th>
                        <th class="py-3.5 px-5 font-bold text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($daftarPesanan as $p)
                        @php
                            $statusLabel = $p->status_pesanan === 'selesai'
                                ? 'Selesai'
                                : ($p->status_pesanan === 'dibatalkan' ? 'Dibatalkan' : str_replace('_', ' ', $p->status_pesanan));
                            $statusClass = $p->status_pesanan === 'selesai'
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : ($p->status_pesanan === 'dibatalkan' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200');
                            $statusIcon = $p->status_pesanan === 'selesai'
                                ? 'fa-circle-check'
                                : ($p->status_pesanan === 'dibatalkan' ? 'fa-circle-xmark' : 'fa-clock');
                        @endphp
                        <tr class="hover:bg-brand-50/30 transition-colors">
                            <td class="py-3.5 px-5 font-extrabold text-slate-900 hidden md:table-cell">
                                #{{ $p->kode_pesanan }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 hidden md:table-cell">
                                <span class="font-medium">{{ $p->created_at->format('d/m/Y') }}</span>
                                <span class="text-xs text-slate-400 block">{{ $p->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $p->jenis === 'online' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    <i class="fa-solid {{ $p->jenis === 'online' ? 'fa-globe' : 'fa-store' }} text-[10px]"></i>
                                    {{ ucfirst($p->jenis) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $p->nama_pembeli ?? 'Pelanggan Langsung' }}</span>
                                @if($p->telepon_pembeli)
                                    <span class="text-xs text-slate-400 hidden md:block">{{ $p->telepon_pembeli }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-extrabold text-slate-900 hidden md:table-cell">
                                Rp {{ number_format($p->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 hidden md:table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-slate-100 text-slate-700 uppercase">
                                    {{ $p->metode_bayar ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                    <i class="fa-solid {{ $statusIcon }} text-[10px]"></i> {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 hidden md:table-cell">
                                {{ $p->penangan?->nama ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <button type="button"
                                        @click="openDetail(@js([
                                            'kode' => $p->kode_pesanan,
                                            'tanggal' => $p->created_at->format('d/m/Y'),
                                            'waktu' => $p->created_at->format('H:i:s'),
                                            'jenis' => ucfirst($p->jenis),
                                            'jenis_icon' => $p->jenis === 'online' ? 'fa-globe' : 'fa-store',
                                            'pembeli' => $p->nama_pembeli ?? 'Pelanggan Langsung',
                                            'telepon' => $p->telepon_pembeli ?? '-',
                                            'total' => 'Rp ' . number_format($p->total, 0, ',', '.'),
                                            'metode' => strtoupper($p->metode_bayar ?? '-'),
                                            'status' => $statusLabel,
                                            'status_class' => $statusClass,
                                            'status_icon' => $statusIcon,
                                            'kasir' => $p->penangan?->nama ?? '-',
                                            'url' => route('panel.admin.penjualan.detail', $p->id),
                                        ]))"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-700 text-xs font-bold transition-all">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Tidak ada transaksi yang cocok</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau tanggal filter Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($daftarPesanan->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $daftarPesanan->links() }}
            </div>
        @endif

        <!-- Detail Pesanan Modal -->
        <div x-cloak x-show="detailOpen" class="fixed inset-0 z-[70] flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div x-show="detailOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeDetail()"
                 class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm"></div>

            <div x-show="detailOpen"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 @click.outside="closeDetail()"
                 class="relative w-full sm:max-w-lg bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl border border-slate-200 max-h-[88vh] overflow-y-auto">
                <div class="sticky top-0 bg-white/95 backdrop-blur px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3 rounded-t-3xl">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Detail Pesanan</p>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5" x-text="selected ? '#' + selected.kode : '-'"></h3>
                    </div>
                    <button type="button" @click="closeDetail()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4" x-show="selected">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jenis</p>
                            <p class="font-bold text-slate-800 mt-1 flex items-center gap-1.5">
                                <i class="fa-solid text-brand-700" :class="selected?.jenis_icon"></i>
                                <span x-text="selected?.jenis"></span>
                            </p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border" :class="selected?.status_class">
                                    <i class="fa-solid text-[10px]" :class="selected?.status_icon"></i>
                                    <span x-text="selected?.status"></span>
                                </span>
                            </div>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pembeli</p>
                            <p class="font-bold text-slate-800 mt-1" x-text="selected?.pembeli"></p>
                            <p class="text-xs text-slate-500 mt-0.5" x-text="selected?.telepon"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Waktu</p>
                            <p class="font-semibold text-slate-800 mt-1" x-text="selected?.tanggal"></p>
                            <p class="text-xs text-slate-500" x-text="selected?.waktu"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kasir</p>
                            <p class="font-semibold text-slate-800 mt-1" x-text="selected?.kasir"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Metode</p>
                            <p class="font-bold text-slate-800 mt-1" x-text="selected?.metode"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-brand-50 border border-brand-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-brand-700">Total</p>
                            <p class="font-extrabold text-brand-900 mt-1" x-text="selected?.total"></p>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="button" @click="closeDetail()" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition-colors">Tutup</button>
                        <a :href="selected?.url" class="flex-1 px-4 py-2.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-sm text-center transition-colors">Lihat Halaman Detail</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection