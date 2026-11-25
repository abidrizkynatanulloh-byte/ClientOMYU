@extends('layouts.admin')

@section('title', 'Persediaan Bahan Baku')
@section('page_title', 'Persediaan Bahan Baku')
@section('badge', 'O-04')
@section('page_subtitle', 'Monitor aset stok buah segar, bahan pembantu, kemasan, batas minimum, dan rendemen.')

@section('header_actions')
    <a href="{{ route('panel.admin.bahan.create') }}" class="btn-press inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs hover:shadow-md">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Bahan Baru</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Ringkasan Aset Persediaan KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-1">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Jenis Bahan</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                    <span class="counter-value" data-counter data-counter-target="{{ $totalJenisBahan }}">{{ $totalJenisBahan }}</span>
                    <span class="text-sm font-semibold text-slate-500">Jenis</span>
                </h3>
                <span class="text-[11px] text-slate-400">Buah, pelengkap & kemasan</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-2">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Nilai Persediaan</span>
                <h3 class="text-2xl font-extrabold text-emerald-700 tracking-tight mt-0.5 counter-value"
                    data-counter
                    data-counter-target="{{ $totalNilaiPersediaan }}"
                    data-counter-prefix="Rp ">
                    Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Estimasi nilai aset gudang & kulkas</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-3">
            <div class="w-12 h-12 rounded-2xl {{ $jumlahBahanKritis > 0 ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Stok Kritis</span>
                <h3 class="text-2xl font-extrabold {{ $jumlahBahanKritis > 0 ? 'text-rose-600' : 'text-slate-800' }} tracking-tight mt-0.5">
                    <span class="counter-value" data-counter data-counter-target="{{ $jumlahBahanKritis }}">{{ $jumlahBahanKritis }}</span>
                    <span class="text-sm font-semibold text-slate-500">Bahan</span>
                </h3>
                <span class="text-[11px] text-slate-400">Perlu segera dilakukan restock</span>
            </div>
        </div>

    </div>

    <!-- Filter Bar Card -->
    <div class="glass-card rounded-3xl p-5 anim-fade-up delay-4">
        <form method="GET" action="{{ route('panel.admin.bahan.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5">
                
                <!-- Search -->
                <div class="sm:col-span-5">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Cari Nama Bahan</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="cari" value="{{ request('cari') }}" 
                               placeholder="Contoh: Belimbing, Melon, Gula..."
                               class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                    </div>
                </div>

                <!-- Jenis Filter -->
                <div class="sm:col-span-4">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Kategori Jenis</label>
                    <select name="jenis" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="">Semua Jenis</option>
                        <option value="buah" {{ request('jenis') == 'buah' ? 'selected' : '' }}>Buah Segar</option>
                        <option value="bahan_lain" {{ request('jenis') == 'bahan_lain' ? 'selected' : '' }}>Bahan Lain (Gula, Susu, Es)</option>
                        <option value="kemasan" {{ request('jenis') == 'kemasan' ? 'selected' : '' }}>Kemasan (Cup, Tutup, Sedotan)</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status Ketersediaan</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="">Semua Status</option>
                        <option value="kritis" {{ request('status') == 'kritis' ? 'selected' : '' }}>Stok Kritis (Perlu Beli)</option>
                        <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                    </select>
                </div>

            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <span class="text-xs text-slate-400">
                    Menampilkan <strong>{{ $daftarBahan->total() }}</strong> bahan terdaftar
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['cari', 'jenis', 'status']))
                        <a href="{{ route('panel.admin.bahan.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">
                            Reset
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
                        <th class="py-3.5 px-6 font-bold">Nama Bahan Baku</th>
                        <th class="py-3.5 px-4 font-bold">Jenis</th>
                        <th class="py-3.5 px-4 font-bold">Satuan Beli & Konversi</th>
                        <th class="py-3.5 px-4 font-bold text-center">Rendemen</th>
                        <th class="py-3.5 px-4 font-bold text-center">Stok Siap Pakai</th>
                        <th class="py-3.5 px-4 font-bold text-center">Batas Minimum</th>
                        <th class="py-3.5 px-4 font-bold text-right">Biaya per Satuan Beli</th>
                        <th class="py-3.5 px-4 font-bold text-right">Nilai Persediaan</th>
                        <th class="py-3.5 px-4 font-bold text-center">Status</th>
                        <th class="py-3.5 px-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($daftarBahan as $b)
                        <tr class="hover:bg-brand-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-extrabold text-slate-900 block">{{ $b->nama }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 uppercase">
                                    {{ $b->jenis }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-600 text-xs">
                                1 {{ $b->satuan_beli }} = <strong>{{ (float) $b->konversi }} {{ $b->satuan_dasar }}</strong>
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-slate-800">
                                {{ (float) $b->rendemen_standar }}%
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="font-extrabold text-slate-900">
                                    {{ (float) $b->stok_tersedia }}
                                </span> 
                                <span class="text-xs text-slate-500">{{ $b->satuan_dasar }}</span>
                                @if((float) $b->stok_dicadangkan > 0)
                                    <span class="block text-[10px] text-amber-600 font-medium">
                                        (Cadangan: {{ (float) $b->stok_dicadangkan }})
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center text-slate-600 text-xs">
                                {{ (float) $b->batas_minimum }} {{ $b->satuan_dasar }}
                            </td>
                            <td class="py-4 px-4 text-right text-slate-600 text-xs">
                                Rp {{ number_format($b->harga_beli, 2, ',', '.') }} <span class="text-[10px] text-slate-400">/{{ $b->satuan_beli }}</span>
                                <span class="block text-[10px] text-slate-400 mt-0.5">
                                    {{ number_format($b->biaya_rata_rata, 2, ',', '.') }}/{{ $b->satuan_dasar }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-extrabold text-slate-900">
                                Rp {{ number_format((float) $b->stok * (float) $b->biaya_rata_rata, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($b->is_kritis)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Kritis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Aman
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('panel.admin.bahan.edit', $b->id) }}" 
                                       title="Edit Bahan"
                                       class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-600 flex items-center justify-center transition-colors">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>

                                    <form method="POST" action="{{ route('panel.admin.bahan.destroy', $b->id) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan bahan {{ $b->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus / Nonaktifkan"
                                                class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-600 flex items-center justify-center transition-colors">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Bahan Baku</p>
                                <p class="text-xs text-slate-400 mt-1">Daftarkan bahan baku baru melalui tombol tambah bahan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarBahan->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $daftarBahan->links() }}
            </div>
        @endif
    </div>

</div>
@endsection