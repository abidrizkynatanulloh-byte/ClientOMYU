@extends('layouts.admin')

@section('title', 'Manajemen Karyawan & Staf')
@section('page_title', 'Manajemen Karyawan')
@section('badge', 'O-09')
@section('page_subtitle', 'Kelola hak akses pengguna, peran kasir/admin, status akun, dan izin melihat margin HPP.')

@section('header_actions')
    <a href="{{ route('panel.admin.karyawan.create') }}" class="btn-press inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs hover:shadow-md">
        <i class="fa-solid fa-user-plus"></i>
        <span>Tambah Staf Baru</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Ringkasan Staf KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-1">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Staf Terdaftar</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                    <span class="counter-value" data-counter data-counter-target="{{ $totalStaf }}">{{ $totalStaf }}</span>
                    <span class="text-sm font-semibold text-slate-500">Orang</span>
                </h3>
                <span class="text-[11px] text-slate-400">Semua akun di sistem OMYU</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-2">
            <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Administrator / Owner</span>
                <h3 class="text-2xl font-extrabold text-indigo-700 tracking-tight mt-0.5">
                    <span class="counter-value" data-counter data-counter-target="{{ $totalAdmin }}">{{ $totalAdmin }}</span>
                    <span class="text-sm font-semibold text-slate-500">Akun</span>
                </h3>
                <span class="text-[11px] text-slate-400">Hak akses penuh ke seluruh modul</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-3">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Kasir / Karyawan Aktif</span>
                <h3 class="text-2xl font-extrabold text-emerald-700 tracking-tight mt-0.5">
                    <span class="counter-value" data-counter data-counter-target="{{ $totalKaryawanAktif }}">{{ $totalKaryawanAktif }}</span>
                    <span class="text-sm font-semibold text-slate-500">Orang</span>
                </h3>
                <span class="text-[11px] text-slate-400">Siap melayani kasir outlet</span>
            </div>
        </div>

    </div>

    <!-- Filter Bar Card -->
    <div class="glass-card rounded-3xl p-5 anim-fade-up delay-4">
        <form method="GET" action="{{ route('panel.admin.karyawan.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5">
                
                <!-- Search -->
                <div class="sm:col-span-6">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Cari Karyawan</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="cari" value="{{ request('cari') }}" 
                               placeholder="Nama, email, atau no telepon..."
                               class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                    </div>
                </div>

                <!-- Peran Filter -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Peran Akses</label>
                    <select name="peran" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('peran') == 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                        <option value="karyawan" {{ request('peran') == 'karyawan' ? 'selected' : '' }}>Karyawan / Kasir</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status Akun</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <span class="text-xs text-slate-400">
                    Menampilkan <strong>{{ $daftarKaryawan->total() }}</strong> pengguna
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['cari', 'peran', 'status']))
                        <a href="{{ route('panel.admin.karyawan.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">
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
                        <th class="py-3.5 px-6 font-bold">Pengguna</th>
                        <th class="py-3.5 px-4 font-bold">Email</th>
                        <th class="py-3.5 px-4 font-bold">Nomor Telepon</th>
                        <th class="py-3.5 px-4 font-bold">Peran Akses</th>
                        <th class="py-3.5 px-4 font-bold text-center">Izin Lihat HPP</th>
                        <th class="py-3.5 px-4 font-bold text-center">Status Akun</th>
                        <th class="py-3.5 px-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($daftarKaryawan as $k)
                        <tr class="hover:bg-brand-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl {{ $k->peran === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-brand-100 text-brand-800' }} flex items-center justify-center font-black text-sm shrink-0">
                                        {{ strtoupper(substr($k->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-slate-900 block">{{ $k->nama }}</span>
                                        @if(auth()->id() === $k->id)
                                            <span class="inline-flex items-center text-[10px] text-brand-800 font-bold bg-brand-50 px-1.5 py-0.2 rounded">Anda Saat Ini</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-600 font-medium">
                                {{ $k->email }}
                            </td>
                            <td class="py-4 px-4 text-slate-600">
                                {{ $k->telepon ?? '-' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $k->peran === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    <i class="fa-solid {{ $k->peran === 'admin' ? 'fa-user-shield' : 'fa-cash-register' }} text-[10px]"></i>
                                    {{ $k->peran }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($k->peran === 'admin')
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                        Otomatis (Admin)
                                    </span>
                                @else
                                    @if($k->bisa_lihat_hpp)
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                            <i class="fa-solid fa-check text-[10px]"></i> Diizinkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Dilarang
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($k->aktif)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-ban text-[10px]"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('panel.admin.karyawan.edit', $k->id) }}" 
                                       title="Edit Akun"
                                       class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-600 flex items-center justify-center transition-colors">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>

                                    @if(auth()->id() !== $k->id)
                                        <form method="POST" action="{{ route('panel.admin.karyawan.toggle_status', $k->id) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin {{ $k->aktif ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $k->nama }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="{{ $k->aktif ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                                    class="w-8 h-8 rounded-xl {{ $k->aktif ? 'bg-slate-100 hover:bg-rose-500 hover:text-white text-rose-600' : 'bg-emerald-100 hover:bg-emerald-600 hover:text-white text-emerald-700' }} flex items-center justify-center transition-colors">
                                                <i class="fa-solid {{ $k->aktif ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($daftarKaryawan->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $daftarKaryawan->links() }}
            </div>
        @endif
    </div>

</div>
@endsection