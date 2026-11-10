@extends('layouts.staf')

@section('title', 'Riwayat Aktivitas Staf (K-14)')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 22px;">Riwayat Aktivitas & Audit Log (K-14)</h1>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Catatan permanen (append-only) seluruh tindakan operasional staf untuk pertanggungjawaban.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form method="GET" action="{{ route('panel.staf.aktivitas.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px; width: 100%;">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari keterangan aktivitas..." style="flex: 1; min-width: 220px;">

            <select name="aksi">
                <option value="">Semua Jenis Aksi</option>
                <option value="penjualan_langsung" {{ request('aksi') == 'penjualan_langsung' ? 'selected' : '' }}>Penjualan Langsung (Kasir)</option>
                <option value="verifikasi_pembayaran" {{ request('aksi') == 'verifikasi_pembayaran' ? 'selected' : '' }}>Verifikasi Pembayaran</option>
                <option value="update_status_pesanan" {{ request('aksi') == 'update_status_pesanan' ? 'selected' : '' }}>Update Status Pesanan</option>
                <option value="penyesuaian_stok" {{ request('aksi') == 'penyesuaian_stok' ? 'selected' : '' }}>Penyesuaian Stok</option>
                <option value="batalkan_pesanan" {{ request('aksi') == 'batalkan_pesanan' ? 'selected' : '' }}>Pembatalan Pesanan</option>
                <option value="ubah_password" {{ request('aksi') == 'ubah_password' ? 'selected' : '' }}>Ubah Password</option>
            </select>

            <input type="date" name="tanggal" value="{{ request('tanggal') }}">

            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('panel.staf.aktivitas.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Tabel Audit Log -->
    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 20px;">
        <table>
            <thead>
                <tr>
                    <th style="width: 160px;">Waktu Kejadian</th>
                    <th style="width: 140px;">Pelaku / Staf</th>
                    <th style="width: 180px;">Jenis Aksi</th>
                    <th>Rincian Keterangan & Alasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daftarAktivitas as $log)
                    <tr>
                        <td style="font-size: 12px; color: #64748b;">
                            <strong>{{ $log->created_at->format('d/m/Y') }}</strong>
                            <div>{{ $log->created_at->format('H:i:s') }} WIB</div>
                        </td>
                        <td>
                            <strong style="color: #0f172a;">{{ $log->pengguna ? $log->pengguna->nama : 'Sistem' }}</strong>
                            <div style="font-size: 11px; color: #64748b;">{{ $log->pengguna ? ucfirst($log->pengguna->peran) : '-' }}</div>
                        </td>
                        <td>
                            <span class="badge" style="background: #f1f5f9; color: #334155; font-family: monospace;">
                                {{ $log->aksi }}
                            </span>
                        </td>
                        <td style="font-size: 13px; color: #334155;">
                            {{ $log->deskripsi ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 40px;">
                            Belum ada catatan aktivitas yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $daftarAktivitas->links() }}
    </div>
@endsection

