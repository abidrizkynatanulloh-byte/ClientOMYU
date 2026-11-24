@extends('layouts.staf')

@section('title', 'Riwayat Aktivitas')

@section('styles')
<style>
    .akt-page { display: grid; gap: 14px; }
    .akt-hero { background: linear-gradient(135deg,#137333,#198754); color: #fff; border-radius: 18px; padding: 19px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .akt-hero h1 { font-size: 20px; font-weight: 900; }
    .akt-hero p { font-size: 12px; opacity: .85; margin-top: 4px; }
    .akt-hero-count { background: rgba(255,255,255,.17); border: 1px solid rgba(255,255,255,.28); border-radius: 13px; padding: 10px 14px; text-align: center; flex-shrink: 0; }
    .akt-hero-count strong { display: block; font-size: 23px; font-weight: 900; line-height: 1; }
    .akt-hero-count span { display: block; font-size: 10px; font-weight: 800; margin-top: 4px; }
    .stat-row { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 8px; }
    .stat-card { background: #fff; border: 1px solid #e2e8f0; border-top: 3px solid #198754; border-radius: 13px; padding: 12px 7px; text-align: center; }
    .stat-card.oranye { border-top-color: #ea580c; }
    .stat-card.biru { border-top-color: #3b82f6; }
    .stat-card strong { display: block; font-size: 20px; font-weight: 900; color: #0f172a; line-height: 1; }
    .stat-card span { display: block; font-size: 10px; font-weight: 800; color: #64748b; margin-top: 5px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 15px; }
    .filter-form { display: grid; gap: 9px; }
    .filter-form input, .filter-form select { width: 100%; border: 1px solid #d7e4dc; border-radius: 10px; padding: 10px 11px; font-size: 13px; outline: none; font-family: inherit; background: #fff; }
    .filter-form input:focus, .filter-form select:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.12); }
    .filter-dua { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; }
    .filter-aksi { display: flex; justify-content: flex-end; align-items: center; gap: 12px; }
    .filter-aksi span { font-size: 11px; font-weight: 800; color: #15803d; }
    .filter-aksi a { font-size: 12px; font-weight: 800; color: #64748b; }
    .filter-aksi button { border: 0; border-radius: 10px; padding: 10px 18px; background: #198754; color: #fff; font-size: 13px; font-weight: 900; cursor: pointer; }
    .log-list { display: grid; gap: 10px; }
    .log-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 15px; padding: 13px; display: grid; gap: 9px; }
    .log-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
    .log-waktu { font-size: 10px; font-weight: 900; color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 4px 8px; border-radius: 8px; white-space: nowrap; }
    .log-waktu small { display: block; color: #64748b; font-weight: 700; margin-top: 2px; background: none; border: 0; padding: 0; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 10px; font-weight: 900; background: #e2e8f0; color: #334155; }
    .badge.transaksi { background: #dcfce7; color: #166534; }
    .badge.produksi { background: #dbeafe; color: #1e40af; }
    .badge.stok { background: #fff7ed; color: #9a3412; }
    .badge.batal { background: #fee2e2; color: #991b1b; }
    .log-staf { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: #0f172a; }
    .log-avatar { width: 28px; height: 28px; border-radius: 50%; background: #137333; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900; flex-shrink: 0; }
    .log-staf small { font-size: 10px; font-weight: 700; color: #64748b; }
    .log-desc { background: #f8fafc; border-left: 3px solid #86efac; border-radius: 8px; padding: 9px 10px; font-size: 12px; color: #334155; line-height: 1.5; }
    .log-desc.batal { background: #fff5f5; border-left-color: #fca5a5; }
    .log-meta { font-size: 10px; color: #94a3b8; }
    .empty { text-align: center; color: #64748b; padding: 30px 12px; font-size: 13px; }
    .paginasi { display: flex; flex-wrap: wrap; gap: 5px; justify-content: center; }
    .paginasi nav { display: flex; justify-content: center; }
    .paginasi ul { display: flex; flex-wrap: wrap; gap: 4px; list-style: none; padding: 0; margin: 0; }
    .paginasi a, .paginasi span { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 9px; border: 1px solid #e2e8f0; border-radius: 9px; background: #fff; color: #334155; font-size: 12px; font-weight: 800; text-decoration: none; }
    .paginasi li.active span { background: #198754; border-color: #198754; color: #fff; }
    @media (min-width: 768px) {
        .filter-form { grid-template-columns: 1fr 220px 165px auto; align-items: end; }
        .filter-dua { display: contents; }
        .log-card { grid-template-columns: 110px 170px 1fr; align-items: start; }
        .log-top { grid-column: 1 / -1; }
    }
</style>
@endsection

@section('content')
@php
    $aksiOptions = [
        'penjualan_langsung' => 'Penjualan Kasir',
        'verifikasi_pembayaran' => 'Verifikasi Pembayaran',
        'update_status_pesanan' => 'Update Status Pesanan',
        'penyelesaian_pesanan' => 'Penyelesaian Pesanan',
        'konfirmasi_pengambilan' => 'Konfirmasi Pengambilan',
        'batalkan_pesanan' => 'Pembatalan Pesanan',
        'mulai_racik_produksi' => 'Mulai Racik',
        'selesai_racik_produksi' => 'Selesai Racik',
        'catat_waste_gagal_racik' => 'Catat Waste / Gagal Racik',
        'pasokan_bahan_masuk' => 'Pasokan Bahan Masuk',
        'pembuangan_bahan_rusak' => 'Pembuangan Bahan Rusak',
        'audit_stok_bahan' => 'Audit Stok Bahan',
        'penyesuaian_stok' => 'Penyesuaian Stok',
        'toggle_status_bahan' => 'Ubah Status Bahan',
        'ubah_password' => 'Ubah Password',
    ];

    $aksiTransaksi = ['penjualan_langsung', 'verifikasi_pembayaran', 'update_status_pesanan', 'penyelesaian_pesanan', 'konfirmasi_pengambilan'];
    $aksiProduksi = ['mulai_racik_produksi', 'selesai_racik_produksi', 'catat_waste_gagal_racik'];
    $aksiStok = ['pasokan_bahan_masuk', 'pembuangan_bahan_rusak', 'audit_stok_bahan', 'penyesuaian_stok', 'toggle_status_bahan'];
    $aksiBatal = ['batalkan_pesanan'];
@endphp
<div class="akt-page">
    <section class="akt-hero">
        <div>
            <h1>Riwayat Aktivitas</h1>
            <p>Catatan audit permanen seluruh tindakan operasional staf</p>
        </div>
        <div class="akt-hero-count">
            <strong>{{ $totalHariIni }}</strong>
            <span>Aktivitas Hari Ini</span>
        </div>
    </section>

    <div class="stat-row">
        <div class="stat-card"><strong>{{ $totalHariIni }}</strong><span>Total Hari Ini</span></div>
        <div class="stat-card oranye"><strong>{{ $transaksiHariIni }}</strong><span>Transaksi & Kasir</span></div>
        <div class="stat-card biru"><strong>{{ $stokProduksiHariIni }}</strong><span>Stok & Produksi</span></div>
    </div>

    <section class="card">
        <form method="GET" action="{{ route('panel.staf.aktivitas.index') }}" class="filter-form">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari deskripsi atau alasan..." aria-label="Cari aktivitas">
            <div class="filter-dua">
                <select name="aksi" aria-label="Jenis aksi">
                    <option value="">Semua Jenis Aksi</option>
                    @foreach($aksiOptions as $nilai => $label)
                        <option value="{{ $nilai }}" {{ request('aksi') === $nilai ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" aria-label="Tanggal">
            </div>
            <div class="filter-aksi">
                @if(request()->filled('cari') || request()->filled('aksi') || request()->filled('tanggal'))
                    <span>Filter aktif</span>
                    <a href="{{ route('panel.staf.aktivitas.index') }}">Reset</a>
                @endif
                <button type="submit">Filter</button>
            </div>
        </form>
    </section>

    <div class="log-list">
        @forelse($daftarAktivitas as $log)
            @php
                if (in_array($log->aksi, $aksiTransaksi, true)) {
                    $kelas = 'transaksi';
                } elseif (in_array($log->aksi, $aksiProduksi, true)) {
                    $kelas = 'produksi';
                } elseif (in_array($log->aksi, $aksiStok, true)) {
                    $kelas = 'stok';
                } elseif (in_array($log->aksi, $aksiBatal, true)) {
                    $kelas = 'batal';
                } else {
                    $kelas = '';
                }

                $labelAksi = $aksiOptions[$log->aksi] ?? ucfirst(str_replace('_', ' ', $log->aksi));
                $namaStaf = $log->pengguna?->nama ?? 'Sistem';
                $peran = $log->pengguna?->peran ?? 'sistem';
            @endphp
            <article class="log-card">
                <div class="log-top">
                    <span class="log-waktu">{{ $log->created_at->format('d/m/Y') }}<small>{{ $log->created_at->format('H:i:s') }} WIB</small></span>
                    <span class="badge {{ $kelas }}">{{ $labelAksi }}</span>
                </div>
                <div class="log-staf">
                    <span class="log-avatar">{{ strtoupper(substr($namaStaf, 0, 1)) }}</span>
                    <span>{{ $namaStaf }}<br><small>{{ ucfirst($peran) }} &middot; {{ ucfirst(str_replace('_', ' ', $log->tipe_objek ?: 'sistem')) }}</small></span>
                </div>
                <div>
                    <div class="log-desc {{ in_array($log->aksi, $aksiBatal, true) ? 'batal' : '' }}">{{ $log->deskripsi ?: 'Tidak ada keterangan tambahan.' }}</div>
                    @if($log->alasan)
                        <div class="log-meta" style="margin-top:6px;">Alasan: {{ $log->alasan }}</div>
                    @endif
                </div>
            </article>
        @empty
            <div class="card empty">
                @if(request()->filled('cari') || request()->filled('aksi') || request()->filled('tanggal'))
                    Tidak ada aktivitas yang sesuai filter. Coba ubah atau reset filter.
                @else
                    Belum ada aktivitas staf yang tercatat.
                @endif
            </div>
        @endforelse
    </div>

    @if($daftarAktivitas->hasPages())
        <div class="paginasi">{{ $daftarAktivitas->links() }}</div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function() {
        const inputCari = document.querySelector('.filter-form input[name="cari"]');
        if (inputCari) {
            inputCari.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.log-card').forEach(function(card) {
                    const desc = card.textContent.toLowerCase();
                    card.style.display = (!q || desc.includes(q)) ? '' : 'none';
                });
            });
        }
    })();
</script>
@endsection
