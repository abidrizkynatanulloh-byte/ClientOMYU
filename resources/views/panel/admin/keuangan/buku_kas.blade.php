@extends('layouts.admin')

@section('title', 'Buku Mutasi Kas Harian')
@section('page_title', 'Buku Mutasi Kas')
@section('badge', 'Kas Harian')
@section('page_subtitle', 'Kas masuk dari pembayaran lunas dan kas keluar dari pasokan bahan yang dicatat staf — jelas pihak, nominal, dan keperluannya.')

@section('header_actions')
    <a href="{{ route('panel.admin.keuangan.laba_rugi') }}" class="btn-press inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <span>Laporan Laba Rugi</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter Periode Bulan & Tahun Card -->
    <div class="glass-card rounded-3xl p-5 anim-fade-up delay-1">
        <form method="GET" action="{{ route('panel.admin.keuangan.buku_kas') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-800 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Periode Mutasi Buku Kas</h3>
                    <p class="text-xs text-slate-400">Pilih bulan dan tahun rekonsiliasi kas</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <select name="bulan" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    @for($y = Carbon\Carbon::now()->year; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="btn-press px-5 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs shadow-xs flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Tampilkan</span>
                </button>
            </div>

        </form>
    </div>

    <!-- 3 KPI Cards Kas Mutasi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        
        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-2">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Kas Masuk</span>
                <h3 class="text-xl xl:text-2xl font-extrabold text-emerald-700 tracking-tight mt-0.5 counter-value leading-tight break-words"
                    data-counter
                    data-counter-target="{{ $totalKasMasuk }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400 block">Penerimaan kas outlet</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-3">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Kas Keluar</span>
                <h3 class="text-xl xl:text-2xl font-extrabold text-rose-600 tracking-tight mt-0.5 counter-value leading-tight break-words"
                    data-counter
                    data-counter-target="{{ $totalKasKeluar }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400 block">Beban & belanja operasional</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-4">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Saldo Kas Bersih</span>
                <h3 class="text-xl xl:text-2xl font-extrabold {{ $saldoKasBersih >= 0 ? 'text-brand-900' : 'text-rose-600' }} tracking-tight mt-0.5 counter-value leading-tight break-words"
                    data-counter
                    data-counter-target="{{ $saldoKasBersih }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($saldoKasBersih, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400 block">Kas Masuk dikurangi Keluar</span>
            </div>
        </div>

    </div>

    <!-- Data Table Mutasi Kas -->
    <div class="glass-card rounded-3xl border border-slate-100 shadow-soft overflow-hidden anim-fade-up delay-5"
         x-data="{ detailOpen: false, selected: null, openDetail(data) { this.selected = data; this.detailOpen = true; }, closeDetail() { this.detailOpen = false; } }"
         @keydown.escape.window="closeDetail()">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] md:min-w-full text-left text-xs sm:text-sm admin-table">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-5 font-bold whitespace-nowrap">Waktu Transaksi</th>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">Jenis Aliran</th>
                        <th class="py-3.5 px-4 font-bold hidden md:table-cell">Dari / Untuk</th>
                        <th class="py-3.5 px-4 font-bold hidden md:table-cell">Keterangan Mutasi</th>
                        <th class="py-3.5 px-4 font-bold text-right whitespace-nowrap">Nominal (Rp)</th>
                        <th class="py-3.5 px-5 font-bold text-right hidden md:table-cell whitespace-nowrap">Dicatat Oleh</th>
                        <th class="py-3.5 px-5 font-bold text-center md:hidden">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mutasiKas as $k)
                        @php
                            $isMasuk = $k->jenis === 'masuk';
                            $jenisLabel = $isMasuk ? 'Kas Masuk' : 'Kas Keluar';
                            $jenisClass = $isMasuk ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200';
                            $jenisIcon = $isMasuk ? 'fa-arrow-down' : 'fa-arrow-up';
                            $nominalText = ($isMasuk ? '+ ' : '- ') . 'Rp ' . number_format($k->jumlah, 0, ',', '.');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-5 text-slate-600 font-medium">
                                {{ $k->created_at->format('d/m/Y') }}
                                <span class="text-xs text-slate-400 block">{{ $k->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $jenisClass }}">
                                    <i class="fa-solid {{ $jenisIcon }} text-[10px]"></i> {{ $jenisLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-semibold text-brand-800 hidden md:table-cell max-w-[180px] break-words">
                                {{ $k->sumber ?? '-' }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-800 hidden md:table-cell max-w-[280px] break-words">
                                {{ $k->keterangan }}
                            </td>
                            <td class="py-4 px-4 text-right font-black whitespace-nowrap {{ $isMasuk ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $nominalText }}
                            </td>
                            <td class="py-4 px-5 text-right text-slate-600 font-medium hidden md:table-cell whitespace-nowrap">
                                {{ $k->pencatat?->nama ?? 'Sistem' }}
                            </td>
                            <td class="py-4 px-5 text-center md:hidden">
                                <button type="button"
                                        @click="openDetail(@js([
                                            'tanggal' => $k->created_at->format('d/m/Y'),
                                            'waktu' => $k->created_at->format('H:i') . ' WIB',
                                            'jenis' => $jenisLabel,
                                            'jenis_class' => $jenisClass,
                                            'jenis_icon' => $jenisIcon,
                                            'sumber' => $k->sumber ?? '-',
                                            'keterangan' => $k->keterangan,
                                            'nominal' => $nominalText,
                                            'nominal_class' => $isMasuk ? 'text-emerald-700' : 'text-rose-600',
                                            'dicatat_oleh' => $k->pencatat?->nama ?? 'Sistem',
                                        ]))"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-700 text-xs font-bold transition-all">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Catatan Mutasi Kas</p>
                                <p class="text-xs text-slate-400 mt-1">Belum ada transaksi kas masuk atau keluar pada periode yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mutasiKas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $mutasiKas->links() }}
            </div>
        @endif

        <!-- Detail Mutasi Kas Modal -->
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
                <div class="sm:hidden pt-3 flex justify-center">
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
                </div>
                <div class="sticky top-0 bg-white/95 backdrop-blur px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3 sm:rounded-t-3xl">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Detail Mutasi Kas</p>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5" x-text="selected ? selected.tanggal : '-'"></h3>
                    </div>
                    <button type="button" @click="closeDetail()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4" x-show="selected">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Waktu</p>
                            <p class="font-semibold text-slate-800 mt-1" x-text="selected?.tanggal"></p>
                            <p class="text-xs text-slate-500" x-text="selected?.waktu"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jenis Aliran</p>
                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border" :class="selected?.jenis_class">
                                    <i class="fa-solid text-[10px]" :class="selected?.jenis_icon"></i>
                                    <span x-text="selected?.jenis"></span>
                                </span>
                            </div>
                        </div>
                        <div class="p-3 rounded-2xl bg-brand-50 border border-brand-100 col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Dari / Untuk</p>
                            <p class="font-semibold text-brand-900 mt-1" x-text="selected?.sumber"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Keterangan Mutasi</p>
                            <p class="font-semibold text-slate-800 mt-1" x-text="selected?.keterangan"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nominal</p>
                            <p class="font-extrabold mt-1" :class="selected?.nominal_class" x-text="selected?.nominal"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Dicatat Oleh</p>
                            <p class="font-semibold text-slate-800 mt-1" x-text="selected?.dicatat_oleh"></p>
                        </div>
                    </div>

                    <div class="pt-1">
                        <button type="button" @click="closeDetail()" class="w-full px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition-colors">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection