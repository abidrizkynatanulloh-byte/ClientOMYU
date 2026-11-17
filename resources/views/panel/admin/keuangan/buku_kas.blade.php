@extends('layouts.admin')

@section('title', 'Buku Mutasi Kas Harian')
@section('page_title', 'Buku Mutasi Kas')
@section('badge', 'Kas Harian')
@section('page_subtitle', 'Pencatatan arus kas masuk operasional dan arus kas keluar harian outlet OMYU.')

@section('header_actions')
    <a href="{{ route('panel.admin.keuangan.laba_rugi') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <span>Laporan Laba Rugi</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter Periode Bulan & Tahun Card -->
    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft">
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

                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs shadow-xs transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Tampilkan</span>
                </button>
            </div>

        </form>
    </div>

    <!-- 3 KPI Cards Kas Mutasi -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner">
                <i class="fa-solid fa-arrow-down-left"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Kas Masuk</span>
                <h3 class="text-2xl font-extrabold text-emerald-700 tracking-tight mt-0.5">
                    Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Penerimaan kas outlet</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner">
                <i class="fa-solid fa-arrow-up-right"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Kas Keluar</span>
                <h3 class="text-2xl font-extrabold text-rose-600 tracking-tight mt-0.5">
                    Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Beban & belanja operasional</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Saldo Kas Bersih</span>
                <h3 class="text-2xl font-extrabold {{ $saldoKasBersih >= 0 ? 'text-brand-900' : 'text-rose-600' }} tracking-tight mt-0.5">
                    Rp {{ number_format($saldoKasBersih, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Kas Masuk dikurangi Keluar</span>
            </div>
        </div>

    </div>

    <!-- Data Table Mutasi Kas -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-6 font-bold">Waktu Transaksi</th>
                        <th class="py-3.5 px-4 font-bold">Jenis Aliran</th>
                        <th class="py-3.5 px-4 font-bold">Keterangan Mutasi</th>
                        <th class="py-3.5 px-4 font-bold text-right">Nominal (Rp)</th>
                        <th class="py-3.5 px-6 font-bold text-right">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mutasiKas as $k)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                {{ $k->created_at->format('d/m/Y') }}
                                <span class="text-xs text-slate-400 block">{{ $k->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($k->jenis === 'masuk')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-arrow-down text-[10px]"></i> Kas Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-arrow-up text-[10px]"></i> Kas Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-800">
                                {{ $k->keterangan }}
                            </td>
                            <td class="py-4 px-4 text-right font-black {{ $k->jenis === 'masuk' ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $k->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($k->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-right text-slate-600 font-medium">
                                {{ $k->pencatat?->nama ?? 'Sistem' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400">
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
    </div>

</div>
@endsection