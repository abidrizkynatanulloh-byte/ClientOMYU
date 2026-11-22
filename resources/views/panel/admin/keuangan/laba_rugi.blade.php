@extends('layouts.admin')

@section('title', 'Laporan Laba Rugi SAK EMKM')
@section('page_title', 'Laporan Laba Rugi Usaha')
@section('badge', 'O-05')
@section('page_subtitle', 'Laporan kinerja keuangan entitas mikro kecil dan menengah (SAK EMKM) OMYU Fresh Juice.')

@section('header_actions')
    <a href="{{ route('panel.admin.keuangan.buku_kas') }}" class="btn-press inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-book-bookmark"></i>
        <span>Buku Mutasi Kas</span>
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Filter Periode Bulan & Tahun Card -->
    <div class="glass-card rounded-3xl p-5 anim-fade-up delay-1">
        <form method="GET" action="{{ route('panel.admin.keuangan.laba_rugi') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-800 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Pilih Periode Laporan</h3>
                    <p class="text-xs text-slate-400">Pilih bulan dan tahun buku akuntansi</p>
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
                    <i class="fa-solid fa-chart-column text-xs"></i>
                    <span>Tampilkan</span>
                </button>
            </div>

        </form>
    </div>

    <!-- 3 Quick KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <div class="glass-card hover-lift rounded-3xl p-5 anim-fade-up delay-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Pendapatan (Omzet)</span>
            <p class="text-2xl font-extrabold text-slate-900 tracking-tight counter-value"
               data-counter
               data-counter-target="{{ $penjualan }}"
               data-counter-prefix="Rp ">
               Rp {{ number_format($penjualan, 0, ',', '.') }}
            </p>
            <span class="text-[11px] text-slate-400">Total pesanan selesai</span>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 anim-fade-up delay-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Laba Kotor Usaha</span>
            <p class="text-2xl font-extrabold text-brand-900 tracking-tight counter-value"
               data-counter
               data-counter-target="{{ $labaKotor }}"
               data-counter-prefix="Rp ">
               Rp {{ number_format($labaKotor, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1.5 mt-1 text-[11px]">
                <span class="font-bold text-brand-700 bg-brand-50 px-2 py-0.2 rounded-full border border-brand-200">Margin: {{ $marginLabaKotor }}%</span>
                <span class="text-slate-400">Food Cost: {{ $foodCostRatio }}%</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 anim-fade-up delay-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Laba Bersih Usaha</span>
            <p class="text-2xl font-extrabold {{ $labaBersih >= 0 ? 'text-emerald-700' : 'text-rose-600' }} tracking-tight counter-value"
               data-counter
               data-counter-target="{{ $labaBersih }}"
               data-counter-prefix="Rp ">
                Rp {{ number_format($labaBersih, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1.5 mt-1 text-[11px]">
                <span class="font-bold {{ $labaBersih >= 0 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-rose-700 bg-rose-50 border-rose-200' }} px-2 py-0.2 rounded-full border">
                    Net Margin: {{ $marginLabaBersih }}%
                </span>
            </div>
        </div>

    </div>

    <!-- Formal Financial Statement Card (SAK EMKM) -->
    <div class="glass-card rounded-3xl border border-slate-100 shadow-card overflow-hidden anim-fade-up delay-5">
        
        <div class="p-6 sm:p-8 bg-gradient-to-b from-brand-50/50 to-transparent border-b border-slate-100 text-center">
            <div class="w-12 h-12 rounded-2xl bg-brand-900 text-white flex items-center justify-center font-black text-xl mx-auto mb-2 shadow-sm">
                <i class="fa-solid fa-lemon"></i>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">OMYU FRESH JUICE</h2>
            <h3 class="text-sm font-bold text-brand-900 mt-0.5">LAPORAN LABA RUGI OPERASIONAL</h3>
            <p class="text-xs text-slate-500 mt-1">
                Untuk Periode yang Berakhir pada {{ Carbon\Carbon::create($tahun, $bulan)->endOfMonth()->translatedFormat('d F Y') }}
            </p>
            <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-semibold mt-3">
                <i class="fa-solid fa-shield-check text-brand-600"></i>
                Standar Akuntansi Keuangan Entitas Mikro, Kecil, dan Menengah (SAK EMKM)
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <table class="w-full text-xs sm:text-sm">
                <tbody class="divide-y divide-slate-100">
                    
                    <!-- 1. Pendapatan Usaha -->
                    <tr class="bg-slate-50/80">
                        <td class="py-3.5 px-4 font-black text-slate-900 uppercase tracking-wide">
                            I. Pendapatan Usaha (Penjualan Bersih)
                        </td>
                        <td class="py-3.5 px-4 text-right font-black text-slate-900 text-base">
                            Rp {{ number_format($penjualan, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-8 text-slate-600">
                            • Penjualan Menu Jus & Minuman Selesai
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-slate-700">
                            Rp {{ number_format($penjualan, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- 2. HPP -->
                    <tr>
                        <td class="py-3 px-8 text-rose-600">
                            • Harga Pokok Penjualan (HPP Bahan Baku & Kemasan Terpakai)
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-rose-600">
                            (Rp {{ number_format($hpp, 0, ',', '.') }})
                        </td>
                    </tr>

                    <!-- Subtotal Laba Kotor -->
                    <tr class="bg-brand-50/60 border-t-2 border-b-2 border-brand-200">
                        <td class="py-3.5 px-4 font-black text-brand-950">
                            LABA KOTOR (GROSS PROFIT)
                            <span class="text-xs font-semibold text-brand-800 ml-2">
                                [Margin: {{ $marginLabaKotor }}% | Food Cost: {{ $foodCostRatio }}%]
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right font-black text-brand-950 text-base">
                            Rp {{ number_format($labaKotor, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- Spacer -->
                    <tr><td colspan="2" class="py-2 border-none"></td></tr>

                    <!-- 3. Beban Operasional -->
                    <tr class="bg-slate-50/80">
                        <td colspan="2" class="py-3.5 px-4 font-black text-slate-900 uppercase tracking-wide">
                            II. Beban Operasional Usaha
                        </td>
                    </tr>
                    @forelse($daftarBeban as $beban)
                        <tr>
                            <td class="py-2.5 px-8 text-slate-700">
                                • {{ $beban->keterangan }} 
                                <span class="text-[11px] text-slate-400">({{ $beban->created_at->format('d/m/Y') }})</span>
                            </td>
                            <td class="py-2.5 px-4 text-right font-semibold text-rose-600">
                                (Rp {{ number_format($beban->jumlah, 0, ',', '.') }})
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 px-8 text-slate-400 italic">
                                Belum ada catatan beban operasional kas keluar pada periode ini.
                            </td>
                        </tr>
                    @endforelse

                    <!-- Subtotal Beban -->
                    <tr class="bg-rose-50/50 border-t border-rose-200">
                        <td class="py-3 px-4 font-bold text-rose-900">
                            TOTAL BEBAN OPERASIONAL
                        </td>
                        <td class="py-3 px-4 text-right font-black text-rose-600">
                            (Rp {{ number_format($totalBebanOperasional, 0, ',', '.') }})
                        </td>
                    </tr>

                    <!-- Spacer -->
                    <tr><td colspan="2" class="py-2 border-none"></td></tr>

                    <!-- 4. Laba Bersih Usaha -->
                    <tr class="bg-gradient-to-r from-emerald-100/80 via-brand-100/70 to-emerald-100/80 border-t-2 border-b-4 border-emerald-600">
                        <td class="py-4 px-4">
                            <span class="text-sm sm:text-base font-black text-emerald-950 block">
                                LABA BERSIH USAHA (NET PROFIT)
                            </span>
                            <span class="text-xs font-bold text-emerald-800">
                                Net Profit Margin: {{ $marginLabaBersih }}% dari Omzet
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right font-black text-emerald-950 text-xl sm:text-2xl">
                            Rp {{ number_format($labaBersih, 0, ',', '.') }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection