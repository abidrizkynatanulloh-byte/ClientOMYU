@extends('layouts.staf')

@section('title', 'Riwayat Aktivitas (K-14)')

@push('styles')
<style>
    /* ===== RIWAYAT AKTIVITAS — OMYU THEME ===== */
    :root {
        --omyu-hijau: #198754;
        --omyu-hijau-tua: #0f5132;
        --omyu-hijau-kds: #137333;
        --omyu-hijau-muda: #f0fdf4;
        --omyu-hijau-border: #bbf7d0;
        --omyu-latar: #f4f8f4;
        --omyu-garis: #e2e8f0;
        --omyu-teks: #0f172a;
        --omyu-abu: #64748b;
    }

    .riwayat-wrapper { background: var(--omyu-latar); min-height: 100vh; }

    /* Banner */
    .riwayat-banner {
        background: linear-gradient(135deg, var(--omyu-hijau-kds) 0%, var(--omyu-hijau) 100%);
        color: #fff;
        padding: 20px 16px 56px;
        position: relative;
        overflow: hidden;
    }
    .riwayat-banner::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .riwayat-banner::after {
        content: '';
        position: absolute;
        bottom: -50px; left: 40%;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .banner-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
    .banner-title { font-size: 20px; font-weight: 700; margin: 0 0 4px; }
    .banner-sub   { font-size: 12px; opacity: .82; margin: 0; }
    .banner-badge {
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 10px;
        padding: 8px 14px;
        text-align: center;
        flex-shrink: 0;
    }
    .banner-badge .num { font-size: 22px; font-weight: 800; line-height: 1; }
    .banner-badge .lbl { font-size: 10px; opacity: .85; white-space: nowrap; }

    /* Stat cards */
    .stat-row {
        display: flex;
        gap: 10px;
        padding: 0 12px;
        margin-top: -32px;
        position: relative;
        z-index: 10;
        margin-bottom: 16px;
    }
    .stat-card {
        flex: 1;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
        padding: 12px 10px;
        text-align: center;
        border-top: 3px solid var(--omyu-hijau);
    }
    .stat-card.oranye { border-top-color: #ea580c; }
    .stat-card.biru   { border-top-color: #3b82f6; }
    .stat-card .sc-num { font-size: 20px; font-weight: 800; color: var(--omyu-teks); line-height: 1.1; }
    .stat-card.oranye .sc-num { color: #ea580c; }
    .stat-card.biru   .sc-num { color: #3b82f6; }
    .stat-card .sc-lbl { font-size: 10px; color: var(--omyu-abu); margin-top: 2px; }

    /* Content area */
    .riwayat-content { padding: 0 12px 100px; }

    /* Filter card */
    .filter-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--omyu-garis);
        padding: 14px;
        margin-bottom: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }
    .filter-card form { display: flex; flex-direction: column; gap: 10px; }
    .input-search-wrap { position: relative; }
    .input-search-wrap .ic {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: var(--omyu-abu); font-size: 15px; pointer-events: none;
    }
    .input-search-wrap input[type="text"] {
        width: 100%; padding: 10px 12px 10px 36px;
        border: 1.5px solid var(--omyu-garis);
        border-radius: 10px; font-size: 14px;
        background: var(--omyu-hijau-muda);
        box-sizing: border-box;
        outline: none; transition: border .2s;
    }
    .input-search-wrap input[type="text"]:focus { border-color: var(--omyu-hijau); }
    .filter-row2 { display: flex; gap: 8px; }
    .filter-row2 select,
    .filter-row2 input[type="date"] {
        flex: 1; padding: 10px 10px;
        border: 1.5px solid var(--omyu-garis);
        border-radius: 10px; font-size: 13px;
        background: #fff; outline: none; transition: border .2s;
        min-width: 0;
    }
    .filter-row2 select:focus,
    .filter-row2 input[type="date"]:focus { border-color: var(--omyu-hijau); }
    .filter-actions { display: flex; gap: 8px; align-items: center; justify-content: flex-end; }
    .btn-filter {
        background: var(--omyu-hijau); color: #fff;
        border: none; border-radius: 10px;
        padding: 10px 20px; font-size: 14px; font-weight: 600;
        cursor: pointer; transition: background .2s;
    }
    .btn-filter:hover { background: var(--omyu-hijau-tua); }
    .btn-reset {
        color: var(--omyu-abu); font-size: 13px; text-decoration: none;
        padding: 8px 4px;
    }
    .btn-reset:hover { color: var(--omyu-teks); }
    .active-filter-hint { font-size: 12px; color: var(--omyu-hijau-tua); display: flex; align-items: center; gap: 4px; }

    /* Log list */
    .log-list { display: flex; flex-direction: column; gap: 10px; }

    /* Log card */
    .log-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--omyu-garis);
        padding: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        display: flex;
        flex-direction: column;
        gap: 8px;
        transition: box-shadow .2s;
    }
    .log-card:hover { box-shadow: 0 4px 14px rgba(19,115,51,.12); }

    .log-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
    .log-time-col { flex-shrink: 0; }
    .log-date-pill {
        display: inline-block;
        background: var(--omyu-hijau-muda);
        color: var(--omyu-hijau-tua);
        font-size: 10px; font-weight: 700;
        border-radius: 6px; padding: 3px 8px;
        border: 1px solid var(--omyu-hijau-border);
        margin-bottom: 2px;
    }
    .log-time { font-size: 12px; color: var(--omyu-abu); font-weight: 600; }
    .log-aksi-badge {
        display: inline-block;
        font-size: 10px; font-weight: 700;
        border-radius: 20px; padding: 4px 10px;
        white-space: nowrap; flex-shrink: 0;
    }
    /* Warna badge per kategori aksi */
    .badge-transaksi  { background: #dcfce7; color: #166534; }
    .badge-produksi   { background: #dbeafe; color: #1e40af; }
    .badge-stok       { background: #fff7ed; color: #9a3412; }
    .badge-batal      { background: #fee2e2; color: #991b1b; }
    .badge-sistem     { background: #f1f5f9; color: #475569; }

    .log-card-mid { display: flex; align-items: center; gap: 8px; }
    .log-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, var(--omyu-hijau-kds), var(--omyu-hijau));
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 13px;
        flex-shrink: 0;
    }
    .log-avatar.sistem {
        background: linear-gradient(135deg, #64748b, #94a3b8);
    }
    .log-staf-name { font-size: 14px; font-weight: 700; color: var(--omyu-teks); }
    .log-role-pill {
        display: inline-block; font-size: 10px; font-weight: 600;
        border-radius: 20px; padding: 2px 8px; margin-left: 4px;
    }
    .role-admin    { background: #dcfce7; color: #166534; }
    .role-karyawan { background: #eff6ff; color: #1d4ed8; }
    .role-sistem   { background: #f1f5f9; color: #475569; }

    .log-desc {
        font-size: 13px; color: #334155;
        line-height: 1.5;
        padding: 8px 10px;
        background: var(--omyu-hijau-muda);
        border-radius: 8px;
        border-left: 3px solid var(--omyu-hijau-border);
    }
    .log-desc.batal-desc {
        background: #fff5f5;
        border-left-color: #fca5a5;
    }

    /* Aksi label map */
    .log-aksi-label { font-size: 11px; color: var(--omyu-abu); margin-top: -4px; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 14px;
        border: 1px dashed var(--omyu-garis);
    }
    .empty-state .es-icon { font-size: 48px; margin-bottom: 12px; display: block; }
    .empty-state .es-title { font-size: 16px; font-weight: 700; color: var(--omyu-teks); margin: 0 0 6px; }
    .empty-state .es-sub   { font-size: 13px; color: var(--omyu-abu); margin: 0; }

    /* Pagination */
    .paginasi-wrap {
        margin-top: 16px;
    }
    .paginasi-wrap nav { display: flex; justify-content: center; }
    .paginasi-wrap .pagination {
        display: flex; flex-wrap: wrap; gap: 4px;
        list-style: none; padding: 0; margin: 0; justify-content: center;
    }
    .paginasi-wrap .page-item .page-link {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 8px;
        font-size: 13px; font-weight: 600; text-decoration: none;
        border: 1.5px solid var(--omyu-garis); color: var(--omyu-teks);
        background: #fff; transition: all .15s;
    }
    .paginasi-wrap .page-item.active .page-link {
        background: var(--omyu-hijau); color: #fff; border-color: var(--omyu-hijau);
    }
    .paginasi-wrap .page-item .page-link:hover:not([aria-current]) {
        background: var(--omyu-hijau-muda); border-color: var(--omyu-hijau);
        color: var(--omyu-hijau-tua);
    }
    .paginasi-wrap .page-item.disabled .page-link { opacity: .4; cursor: default; }

    /* ===== DESKTOP overrides ===== */
    @media (min-width: 768px) {
        .riwayat-banner { padding: 32px 32px 72px; }
        .banner-title   { font-size: 26px; }
        .banner-badge .num { font-size: 28px; }
        .stat-row       { padding: 0 24px; gap: 16px; margin-top: -40px; margin-bottom: 24px; }
        .stat-card      { padding: 16px 20px; }
        .stat-card .sc-num { font-size: 26px; }
        .stat-card .sc-lbl { font-size: 12px; }
        .riwayat-content { padding: 0 24px 60px; }

        .filter-card { padding: 20px; }
        .filter-card form {
            flex-direction: row; align-items: center; flex-wrap: wrap; gap: 12px;
        }
        .input-search-wrap { flex: 1 1 240px; }
        .filter-row2 { flex: 0 0 auto; gap: 10px; }
        .filter-row2 select { width: 200px; flex: none; }
        .filter-row2 input[type="date"] { width: 160px; flex: none; }
        .filter-actions { margin-left: auto; }

        /* Log card desktop — horizontal layout */
        .log-card { flex-direction: row; align-items: flex-start; gap: 16px; padding: 18px 20px; }
        .log-time-col { width: 90px; flex-shrink: 0; border-right: 1.5px solid var(--omyu-garis); padding-right: 14px; padding-top: 2px; }
        .log-card-mid { flex-direction: column; align-items: flex-start; min-width: 160px; flex-shrink: 0; }
        .log-card-top { flex: 1; flex-direction: column; gap: 6px; }
        .log-aksi-badge { align-self: flex-start; }
        .log-desc { flex: 1; align-self: stretch; }
    }
</style>
@endpush

@section('content')
<div class="riwayat-wrapper">

    {{-- ===== BANNER ===== --}}
    <div class="riwayat-banner">
        <div class="banner-top">
            <div>
                <div style="font-size:11px;opacity:.8;margin-bottom:6px;">📋 K-14 • Audit Log</div>
                <h1 class="banner-title">Riwayat Aktivitas</h1>
                <p class="banner-sub">Catatan permanen seluruh tindakan operasional staf</p>
            </div>
            <div class="banner-badge">
                <div class="num">{{ $totalHariIni }}</div>
                <div class="lbl">Aktivitas<br>Hari Ini</div>
            </div>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="sc-num">{{ $totalHariIni }}</div>
            <div class="sc-lbl">Total Hari Ini</div>
        </div>
        <div class="stat-card oranye">
            <div class="sc-num">{{ $transaksiHariIni }}</div>
            <div class="sc-lbl">Transaksi &amp; Kasir</div>
        </div>
        <div class="stat-card biru">
            <div class="sc-num">{{ $stokProduksiHariIni }}</div>
            <div class="sc-lbl">Stok &amp; Produksi</div>
        </div>
    </div>

    {{-- ===== CONTENT ===== --}}
    <div class="riwayat-content">

        {{-- Filter Card --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('panel.staf.aktivitas.index') }}">
                <div class="input-search-wrap">
                    <span class="ic">🔍</span>
                    <input type="text" name="cari" value="{{ request('cari') }}"
                        placeholder="Cari keterangan aktivitas...">
                </div>
                <div class="filter-row2">
                    <select name="aksi">
                        <option value="">Semua Jenis Aksi</option>
                        @php
                            $aksiOptions = [
                                'penjualan_langsung'      => '💰 Penjualan Kasir',
                                'verifikasi_pembayaran'   => '✅ Verifikasi Pembayaran',
                                'update_status_pesanan'   => '🔄 Update Status Pesanan',
                                'batalkan_pesanan'        => '❌ Pembatalan Pesanan',
                                'mulai_racik_produksi'    => '🧃 Mulai Racik',
                                'selesai_racik_produksi'  => '✔️ Selesai Racik',
                                'catat_waste_gagal_racik' => '⚠️ Catat Waste/Gagal',
                                'pasokan_bahan_masuk'     => '📦 Pasokan Masuk',
                                'pembuangan_bahan_rusak'  => '🗑️ Pembuangan Bahan',
                                'penyesuaian_stok'        => '⚖️ Penyesuaian Stok',
                                'audit_stok_bahan'        => '🔍 Audit Stok',
                                'toggle_status_bahan'     => '🔁 Toggle Status Bahan',
                                'ubah_password'           => '🔑 Ubah Password',
                                'cetak_struk'             => '🧾 Cetak Struk',
                            ];
                        @endphp
                        @foreach($aksiOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('aksi') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}">
                </div>
                <div class="filter-actions">
                    @if(request('cari') || request('aksi') || request('tanggal'))
                        <span class="active-filter-hint">🟢 Filter aktif</span>
                        <a href="{{ route('panel.staf.aktivitas.index') }}" class="btn-reset">Reset</a>
                    @endif
                    <button type="submit" class="btn-filter">Filter</button>
                </div>
            </form>
        </div>

        {{-- Log List --}}
        <div class="log-list">
            @forelse($daftarAktivitas as $log)
                @php
                    // Kategori aksi → warna badge
                    $aksiTransaksi = ['penjualan_langsung','verifikasi_pembayaran','update_status_pesanan','cetak_struk'];
                    $aksiProduksi  = ['mulai_racik_produksi','selesai_racik_produksi','catat_waste_gagal_racik'];
                    $aksiStok      = ['pasokan_bahan_masuk','pembuangan_bahan_rusak','audit_stok_bahan','penyesuaian_stok','toggle_status_bahan'];
                    $aksiBatal     = ['batalkan_pesanan'];

                    if (in_array($log->aksi, $aksiTransaksi))     { $badgeClass = 'badge-transaksi'; $kategoriIcon = '💰'; }
                    elseif (in_array($log->aksi, $aksiProduksi))  { $badgeClass = 'badge-produksi';  $kategoriIcon = '🧃'; }
                    elseif (in_array($log->aksi, $aksiStok))      { $badgeClass = 'badge-stok';      $kategoriIcon = '📦'; }
                    elseif (in_array($log->aksi, $aksiBatal))     { $badgeClass = 'badge-batal';     $kategoriIcon = '❌'; }
                    else                                           { $badgeClass = 'badge-sistem';    $kategoriIcon = '⚙️'; }

                    // Label aksi yang lebih manusiawi
                    $aksiLabels = [
                        'penjualan_langsung'      => 'Penjualan Kasir',
                        'verifikasi_pembayaran'   => 'Verifikasi Pembayaran',
                        'update_status_pesanan'   => 'Update Status Pesanan',
                        'batalkan_pesanan'        => 'Pembatalan Pesanan',
                        'mulai_racik_produksi'    => 'Mulai Racik Jus',
                        'selesai_racik_produksi'  => 'Selesai Racik Jus',
                        'catat_waste_gagal_racik' => 'Catat Waste / Gagal Racik',
                        'pasokan_bahan_masuk'     => 'Pasokan Bahan Masuk',
                        'pembuangan_bahan_rusak'  => 'Pembuangan Bahan Rusak',
                        'penyesuaian_stok'        => 'Penyesuaian Stok',
                        'audit_stok_bahan'        => 'Audit Stok Bahan',
                        'toggle_status_bahan'     => 'Ubah Status Bahan',
                        'ubah_password'           => 'Ubah Password',
                        'cetak_struk'             => 'Cetak Struk',
                    ];
                    $aksiLabel = $aksiLabels[$log->aksi] ?? str_replace('_', ' ', $log->aksi);

                    $namaStaf  = $log->pengguna ? $log->pengguna->nama : 'Sistem';
                    $peranStaf = $log->pengguna ? $log->pengguna->peran : 'sistem';
                    $inisial   = strtoupper(substr($namaStaf, 0, 1));
                    $roleClass = match($peranStaf) {
                        'admin'    => 'role-admin',
                        'karyawan' => 'role-karyawan',
                        default    => 'role-sistem',
                    };
                    $descClass = in_array($log->aksi, $aksiBatal) ? 'log-desc batal-desc' : 'log-desc';
                @endphp

                <div class="log-card">
                    {{-- Waktu --}}
                    <div class="log-time-col">
                        <div class="log-date-pill">{{ $log->created_at->format('d/m/Y') }}</div>
                        <div class="log-time">{{ $log->created_at->format('H:i:s') }} WIB</div>
                    </div>

                    {{-- Staf & aksi --}}
                    <div class="log-card-mid">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="log-avatar {{ $peranStaf === 'sistem' ? 'sistem' : '' }}">{{ $inisial }}</div>
                            <div>
                                <div class="log-staf-name">{{ $namaStaf }}</div>
                                <span class="log-role-pill {{ $roleClass }}">{{ ucfirst($peranStaf) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Aksi + deskripsi --}}
                    <div class="log-card-top">
                        <span class="log-aksi-badge {{ $badgeClass }}">{{ $kategoriIcon }} {{ $aksiLabel }}</span>
                        <div class="{{ $descClass }}">
                            {{ $log->deskripsi ?: 'Tidak ada keterangan tambahan.' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <span class="es-icon">📋</span>
                    <p class="es-title">Belum Ada Catatan Aktivitas</p>
                    <p class="es-sub">
                        @if(request('cari') || request('aksi') || request('tanggal'))
                            Tidak ada aktivitas yang sesuai filter.<br>Coba ubah atau reset filter pencarian.
                        @else
                            Aktivitas staf akan tercatat secara otomatis di sini.
                        @endif
                    </p>
                    @if(request('cari') || request('aksi') || request('tanggal'))
                        <a href="{{ route('panel.staf.aktivitas.index') }}" class="btn-filter" style="display:inline-block;margin-top:16px;text-decoration:none;">
                            Reset Filter
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($daftarAktivitas->hasPages())
            <div class="paginasi-wrap">
                {{ $daftarAktivitas->links() }}
            </div>
        @endif

    </div>{{-- /content --}}
</div>{{-- /wrapper --}}
@endsection
