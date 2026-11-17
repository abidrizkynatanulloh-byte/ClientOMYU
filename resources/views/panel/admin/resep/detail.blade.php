@extends('layouts.admin')

@section('title', 'Rincian Resep ' . $varian->produk->nama . ' (' . $varian->nama . ')')
@section('page_title', 'Rincian Resep & HPP')
@section('badge', $varian->nama)
@section('page_subtitle', 'Breakdown takaran bahan baku, biaya rata-rata, estimasi laba kotor, dan faktor pembatas stok.')

@section('header_actions')
    <a href="{{ route('panel.admin.resep.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Daftar Resep</span>
    </a>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Menu Info -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center text-2xl font-black shadow-inner">
                <i class="fa-solid fa-blender"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    {{ $varian->produk->kategori?->nama ?? 'Minuman Jus Segar' }}
                </span>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    {{ $varian->produk->nama }} <span class="text-brand-600 font-semibold">({{ $varian->nama }})</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Versi Resep Aktif: {{ $resep ? 'Standar Outlet OMYU' : 'Belum Ditentukan' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-100 text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Kapasitas Produksi</span>
                <span class="text-xl font-extrabold {{ $kapasitasFinal > 10 ? 'text-emerald-700' : 'text-rose-600' }}">
                    {{ $kapasitasFinal }} Cup
                </span>
            </div>
        </div>
    </div>

    <!-- 5 KPI Cards Financial Breakdown -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Harga Jual</span>
            <p class="text-xl font-extrabold text-slate-900">Rp {{ number_format($hargaJual, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400">Per cup</span>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total HPP Bahan</span>
            <p class="text-xl font-extrabold text-rose-600">Rp {{ number_format($totalHpp, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400">Modal bahan baku</span>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Laba Kotor</span>
            <p class="text-xl font-extrabold text-brand-900">Rp {{ number_format($labaKotor, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400">Keuntungan per cup</span>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Margin Laba</span>
            <p class="text-xl font-extrabold text-emerald-600">{{ $margin }}%</p>
            <span class="text-[11px] text-slate-400">Persentase margin</span>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft col-span-2 sm:col-span-1">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Faktor Pembatas</span>
            <p class="text-sm font-extrabold text-amber-700 truncate" title="{{ $bahanPembatas ?? 'Semua Bahan Cukup' }}">
                {{ $bahanPembatas ?? '-' }}
            </p>
            <span class="text-[11px] text-slate-400">Stok paling minim</span>
        </div>

    </div>

    <!-- Table Rincian Komposisi Resep -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Komposisi Takaran & Biaya Bahan Baku</h3>
                <p class="text-xs text-slate-500">Kalkulasi biaya per cup berdasarkan metode biaya rata-rata bergerak</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                {{ count($rincianBahan) }} Bahan Terpakai
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-6 font-bold">Nama Bahan</th>
                        <th class="py-3.5 px-4 font-bold">Jenis</th>
                        <th class="py-3.5 px-4 font-bold text-center">Takaran per Cup</th>
                        <th class="py-3.5 px-4 font-bold text-right">Biaya Rata-rata / Satuan</th>
                        <th class="py-3.5 px-4 font-bold text-right">Biaya per Cup</th>
                        <th class="py-3.5 px-4 font-bold text-center">Stok Siap Pakai</th>
                        <th class="py-3.5 px-6 font-bold text-center">Bisa Jadi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rincianBahan as $b)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6">
                                <span class="font-extrabold text-slate-900 block">{{ $b['nama_bahan'] }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 uppercase">
                                    {{ $b['jenis'] }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-slate-800">
                                {{ $b['takaran'] }} {{ $b['satuan'] }}
                            </td>
                            <td class="py-4 px-4 text-right text-slate-600">
                                Rp {{ number_format($b['biaya_satuan'], 2, ',', '.') }} / {{ $b['satuan'] }}
                            </td>
                            <td class="py-4 px-4 text-right font-extrabold text-rose-600">
                                Rp {{ number_format($b['biaya_per_cup'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-slate-700">
                                {{ $b['stok_tersedia'] }} {{ $b['satuan'] }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $b['kapasitas_cup'] === $kapasitasFinal ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-800' }}">
                                    {{ $b['kapasitas_cup'] }} Cup
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-cookie-bite"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Komposisi Bahan</p>
                                <p class="text-xs text-slate-400 mt-1">Resep untuk varian ini belum dikonfigurasi bahan bakunya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-rose-50/50 border-t-2 border-rose-200">
                        <td colspan="4" class="py-4 px-6 text-right font-bold text-slate-700 text-sm">
                            Total HPP Bahan Baku per Cup:
                        </td>
                        <td class="py-4 px-4 text-right font-black text-rose-700 text-base">
                            Rp {{ number_format($totalHpp, 0, ',', '.') }}
                        </td>
                        <td colspan="2" class="py-4 px-6 text-center text-xs text-slate-500 font-medium">
                            Kapasitas Menu: <strong>{{ $kapasitasFinal }} Cup</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection