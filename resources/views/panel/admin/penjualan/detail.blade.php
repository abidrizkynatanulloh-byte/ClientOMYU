@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->kode_pesanan)
@section('page_title', 'Detail Pesanan')
@section('badge', '#' . $pesanan->kode_pesanan)
@section('page_subtitle', 'Rincian transaksi, status pembayaran, kasir penangan, dan item pesanan.')

@section('header_actions')
    <a href="{{ route('panel.admin.penjualan.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Penjualan</span>
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Status Summary -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <span>Waktu Transaksi</span>
                <span>•</span>
                <span>{{ $pesanan->created_at->format('d F Y, H:i:s') }} WIB</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Pesanan #{{ $pesanan->kode_pesanan }}
            </h2>
        </div>

        <div class="flex items-center gap-2.5">
            @if($pesanan->status_pesanan === 'selesai')
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    <i class="fa-solid fa-circle-check"></i> Pesanan Selesai
                </span>
            @elseif($pesanan->status_pesanan === 'dibatalkan')
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                    <i class="fa-solid fa-circle-xmark"></i> Dibatalkan
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-clock"></i> {{ str_replace('_', ' ', $pesanan->status_pesanan) }}
                </span>
            @endif

            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $pesanan->jenis === 'online' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-slate-100 text-slate-800 border border-slate-200' }}">
                <i class="fa-solid {{ $pesanan->jenis === 'online' ? 'fa-globe' : 'fa-store' }}"></i>
                {{ ucfirst($pesanan->jenis) }}
            </span>
        </div>
    </div>

    <!-- 2 Column Details: Pelanggan & Pembayaran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- Informasi Pelanggan & Kasir -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Informasi Pelanggan</h3>
                    <p class="text-xs text-slate-400">Data pemesan & kasir pelayan</p>
                </div>
            </div>

            <div class="space-y-3 text-xs sm:text-sm">
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Nama Pembeli</span>
                    <span class="font-bold text-slate-800">{{ $pesanan->nama_pembeli ?? 'Pelanggan Langsung (Tamu)' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Nomor Telepon</span>
                    <span class="font-bold text-slate-800">{{ $pesanan->telepon_pembeli ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Kasir / Staf Penangan</span>
                    <span class="font-bold text-brand-900">{{ $pesanan->penangan?->nama ?? 'Sistem Otomatis' }}</span>
                </div>
                @if($pesanan->catatan)
                    <div class="pt-2">
                        <span class="text-slate-400 block mb-1">Catatan Pesanan:</span>
                        <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200/60 text-xs text-amber-900 font-medium italic">
                            "{{ $pesanan->catatan }}"
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Status Pembayaran</h3>
                    <p class="text-xs text-slate-400">Rincian metode & verifikasi pembayaran</p>
                </div>
            </div>

            <div class="space-y-3 text-xs sm:text-sm">
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Metode Bayar</span>
                    <span class="font-bold text-slate-800 uppercase px-2 py-0.5 rounded-md bg-slate-100">
                        {{ $pesanan->metode_bayar ?? '-' }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Status Pembayaran</span>
                    <span class="font-bold {{ $pesanan->status_pembayaran === 'lunas' ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ ucfirst($pesanan->status_pembayaran) }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Total Tagihan</span>
                    <span class="text-base font-extrabold text-slate-900">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Items Pesanan Receipt Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-blender"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Rincian Menu Dipesan</h3>
                    <p class="text-xs text-slate-400">Daftar cup jus dan varian yang dibeli pelanggan</p>
                </div>
            </div>
            <span class="text-xs font-bold text-brand-800 bg-brand-100 px-3 py-1 rounded-full">
                {{ $pesanan->itemPesanan->sum('jumlah') }} Cup
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3 px-6 font-bold">Menu Produk</th>
                        <th class="py-3 px-4 font-bold">Varian</th>
                        <th class="py-3 px-4 font-bold text-right">Harga Satuan</th>
                        <th class="py-3 px-4 font-bold text-center">Jumlah</th>
                        <th class="py-3 px-6 font-bold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pesanan->itemPesanan as $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6">
                                <div class="font-extrabold text-slate-900">{{ $item->nama_produk }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $item->nama_varian ?? 'Reguler' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-medium text-slate-600">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-slate-800">
                                {{ $item->jumlah }}
                            </td>
                            <td class="py-4 px-6 text-right font-extrabold text-slate-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-brand-50/50 border-t-2 border-brand-200">
                        <td colspan="4" class="py-4 px-6 text-right font-bold text-slate-700 text-sm">
                            Total Bayar:
                        </td>
                        <td class="py-4 px-6 text-right font-black text-brand-950 text-lg">
                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection