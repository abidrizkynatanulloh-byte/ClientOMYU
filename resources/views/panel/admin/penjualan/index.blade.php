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
    <div class="glass-card rounded-3xl border border-slate-100 shadow-soft overflow-hidden anim-fade-up delay-5">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm admin-table">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-5 font-bold">Kode Pesanan</th>
                        <th class="py-3.5 px-4 font-bold">Waktu Transaksi</th>
                        <th class="py-3.5 px-4 font-bold">Jenis</th>
                        <th class="py-3.5 px-4 font-bold">Pembeli</th>
                        <th class="py-3.5 px-4 font-bold">Total Pembayaran</th>
                        <th class="py-3.5 px-4 font-bold">Metode</th>
                        <th class="py-3.5 px-4 font-bold">Status Pesanan</th>
                        <th class="py-3.5 px-4 font-bold">Kasir / Penangan</th>
                        <th class="py-3.5 px-5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($daftarPesanan as $p)
                        <tr class="hover:bg-brand-50/30 transition-colors">
                            <td class="py-3.5 px-5 font-extrabold text-slate-900">
                                #{{ $p->kode_pesanan }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
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
                                    <span class="text-xs text-slate-400">{{ $p->telepon_pembeli }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-extrabold text-slate-900">
                                Rp {{ number_format($p->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-slate-100 text-slate-700 uppercase">
                                    {{ $p->metode_bayar ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->status_pesanan === 'selesai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                                    </span>
                                @elseif($p->status_pesanan === 'dibatalkan')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-clock text-[10px]"></i> {{ str_replace('_', ' ', $p->status_pesanan) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $p->penangan?->nama ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <a href="{{ route('panel.admin.penjualan.detail', $p->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-700 text-xs font-bold transition-all">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>Detail</span>
                                </a>
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

    </div>

</div>
@endsection