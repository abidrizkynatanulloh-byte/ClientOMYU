@extends('layouts.admin')

@section('title', 'Daftar Menu OMYU')
@section('page_title', 'Daftar Menu OMYU')
@section('badge', 'O-03A')
@section('page_subtitle', 'Kelola menu jualan: resep per cup, upload gambar, harga, dan stok yang dihitung otomatis dari ketersediaan bahan.')

@section('header_actions')
    <form method="POST" action="{{ route('panel.admin.menu.hitung-ulang') }}">
        @csrf
        <button type="submit" class="btn-press inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200">
            <i class="fa-solid fa-rotate"></i>
            <span>Hitung Ulang Stok</span>
        </button>
    </form>
    <a href="{{ route('panel.admin.menu.create') }}" class="btn-press inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs hover:shadow-md">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Menu Baru</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-1">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-glass-water"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Menu</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">{{ $daftarProduk->total() }} <span class="text-sm font-semibold text-slate-500">Menu</span></h3>
                <span class="text-[11px] text-slate-400">Terdaftar di sistem</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-2">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-eye"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Tampil di Katalog</span>
                <h3 class="text-2xl font-extrabold text-emerald-700 tracking-tight mt-0.5">{{ $jumlahAktif }} <span class="text-sm font-semibold text-slate-500">Menu</span></h3>
                <span class="text-[11px] text-slate-400">Dapat dipesan pelanggan</span>
            </div>
        </div>

        <div class="glass-card hover-lift rounded-3xl p-5 flex items-center gap-4 anim-fade-up delay-3">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-800 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Stok Habis</span>
                <h3 class="text-2xl font-extrabold text-rose-600 tracking-tight mt-0.5">{{ $jumlahStokHabis }} <span class="text-sm font-semibold text-slate-500">Menu</span></h3>
                <span class="text-[11px] text-slate-400">Perlu segera ditambah stoknya</span>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-3xl p-5 anim-fade-up delay-4">
        <form method="GET" action="{{ route('panel.admin.menu.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-600 mb-1">Cari Nama Menu</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Contoh: Jus Melon..."
                           class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                </div>
            </div>
            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-slate-600 mb-1">Kategori</label>
                <select name="kategori_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <option value="">Semua Kategori</option>
                    @foreach($daftarKategori as $id => $nama)
                        <option value="{{ $id }}" {{ request('kategori_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="sm:col-span-12 flex items-center justify-between pt-2 border-t border-slate-100">
                <span class="text-xs text-slate-400">Menampilkan <strong>{{ $daftarProduk->total() }}</strong> menu</span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['cari', 'kategori_id', 'status']))
                        <a href="{{ route('panel.admin.menu.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">Reset</a>
                    @endif
                    <button type="submit" class="btn-press px-5 py-2 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs shadow-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if(session('sukses'))
        <div class="glass-card rounded-2xl px-5 py-3 text-sm font-bold text-emerald-700 border border-emerald-200 bg-emerald-50/70 anim-fade-up">
            <i class="fa-solid fa-circle-check mr-1.5"></i>{{ session('sukses') }}
        </div>
    @endif

    <div class="glass-card rounded-3xl border border-slate-100 shadow-soft overflow-hidden anim-fade-up delay-5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm admin-table">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-6 font-bold">Menu</th>
                        <th class="py-3.5 px-4 font-bold">Kategori</th>
                        <th class="py-3.5 px-4 font-bold">Harga</th>
                        <th class="py-3.5 px-4 font-bold text-center">Stok</th>
                        <th class="py-3.5 px-4 font-bold text-center">Varian</th>
                        <th class="py-3.5 px-4 font-bold text-center">Status</th>
                        <th class="py-3.5 px-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($daftarProduk as $p)
                        <tr class="hover:bg-brand-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if($p->gambar)
                                        <img src="{{ str_starts_with($p->gambar, 'http') ? $p->gambar : asset('storage/'.$p->gambar) }}"
                                             alt="{{ $p->nama }}"
                                             class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-300 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-extrabold text-slate-900 block">{{ $p->nama }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $p->deskripsi ? \Illuminate\Support\Str::limit($p->deskripsi, 48) : 'Tanpa deskripsi' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-600 text-xs">{{ $p->kategori?->nama ?? '-' }}</td>
                            <td class="py-4 px-4 font-extrabold text-slate-900">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            <td class="py-4 px-4 text-center">
                                <span class="font-extrabold {{ $p->stok > 0 ? 'text-slate-900' : 'text-rose-600' }}">{{ $p->stok }}</span>
                            </td>
                            <td class="py-4 px-4 text-center text-slate-600 text-xs">{{ $p->varian->count() }}</td>
                            <td class="py-4 px-4 text-center">
                                @if($p->aktif)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fa-solid fa-eye-slash text-[10px]"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('panel.admin.menu.edit', $p->id) }}"
                                       title="Edit Menu"
                                       class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-brand-400 hover:text-brand-950 text-slate-600 flex items-center justify-center transition-colors">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('panel.admin.menu.destroy', $p->id) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan menu {{ $p->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus / Nonaktifkan"
                                                class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-600 flex items-center justify-center transition-colors">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-glass-water"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Menu</p>
                                <p class="text-xs text-slate-400 mt-1">Tambahkan menu baru lewat tombol "Tambah Menu Baru".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarProduk->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $daftarProduk->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
