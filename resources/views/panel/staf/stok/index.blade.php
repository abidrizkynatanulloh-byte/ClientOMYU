@extends('layouts.staf')

@section('title', 'Manajemen Stok & Bahan Baku Segar')

@section('styles')
<style>
    /* Top Banner */
    .stok-banner {
        background: linear-gradient(135deg, #137333 0%, #0f5132 100%);
        border-radius: 16px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #ffffff;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(19, 115, 51, 0.2);
    }

    .stok-banner-title {
        font-size: 16px;
        font-weight: 900;
        letter-spacing: -0.2px;
        line-height: 1.2;
    }

    .stok-banner-sub {
        font-size: 11px;
        opacity: 0.9;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stok-banner-icon {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* 3 Summary Metric Pill Cards */
    .metric-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }

    .metric-card {
        border-radius: 14px;
        padding: 10px 8px;
        text-align: center;
        text-decoration: none;
        transition: transform 0.15s;
    }

    .metric-card:active {
        transform: scale(0.97);
    }

    .metric-card-total {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .metric-card-kritis {
        background-color: #fff7ed;
        border: 1px solid #fed7aa;
        color: #c2410c;
    }

    .metric-card-habis {
        background-color: #fff1f2;
        border: 1px solid #fecdd3;
        color: #9f1239;
    }

    .metric-num {
        font-size: 20px;
        font-weight: 900;
        line-height: 1.1;
    }

    .metric-label {
        font-size: 10px;
        font-weight: 800;
        margin-top: 3px;
        text-transform: capitalize;
    }

    /* Main Action Buttons */
    .btn-action-primary {
        width: 100%;
        background-color: #137333;
        color: #ffffff;
        border: none;
        border-radius: 14px;
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(19, 115, 51, 0.2);
        margin-bottom: 8px;
        transition: background-color 0.15s;
    }

    .btn-action-primary:hover {
        background-color: #0f5132;
    }

    .btn-action-secondary {
        width: 100%;
        background-color: #ffffff;
        color: #991b1b;
        border: 1px solid #fca5a5;
        border-radius: 14px;
        padding: 10px 16px;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        margin-bottom: 18px;
        transition: background-color 0.15s;
    }

    .btn-action-secondary:hover {
        background-color: #fff1f2;
    }

    /* Section Header */
    .section-header-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 10px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 900;
        color: #0f172a;
    }

    .section-sub {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }

    /* Filter Pills */
    .filter-pills-row {
        display: flex;
        gap: 6px;
        margin-bottom: 14px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .filter-pill {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        color: #475569;
        transition: all 0.15s;
    }

    .filter-pill.active {
        background-color: #137333;
        border-color: #137333;
        color: #ffffff;
    }

    /* 2-Column Grid for Fresh Ingredients */
    .bahan-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    @media (min-width: 768px) {
        .bahan-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
    }

    .bahan-card {
        border-radius: 16px;
        padding: 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: transform 0.15s;
    }

    /* Dynamic Card Palettes */
    .card-mangga {
        background: linear-gradient(145deg, #fff7ed 0%, #ffedd5 100%);
        border: 1px solid #fed7aa;
    }
    .card-alpukat {
        background: linear-gradient(145deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #bbf7d0;
    }
    .card-jeruk {
        background: linear-gradient(145deg, #fff1f2 0%, #ffe4e6 100%);
        border: 1px solid #fecdd3;
    }
    .card-naga {
        background: linear-gradient(145deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #bbf7d0;
    }
    .card-default {
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }

    .card-top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .pill-nama {
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 8px;
        color: #ffffff;
    }
    .pill-mangga { background-color: #f97316; }
    .pill-alpukat { background-color: #15803d; }
    .pill-jeruk { background-color: #9f1239; }
    .pill-naga { background-color: #166534; }
    .pill-default { background-color: #475569; }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .dot-aman { background-color: #22c55e; box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2); }
    .dot-kritis { background-color: #f97316; box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2); }
    .dot-habis { background-color: #881337; box-shadow: 0 0 0 2px rgba(136, 19, 55, 0.2); }

    .stock-amount-row {
        margin-bottom: 8px;
    }

    .stock-amount-val {
        font-size: 16px;
        font-weight: 900;
        color: #0f172a;
    }
    .stock-amount-val.text-habis {
        color: #881337;
    }

    .stock-amount-unit {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
    }

    /* Progress bar */
    .capacity-bar {
        width: 100%;
        height: 6px;
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .capacity-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s;
    }
    .fill-mangga { background-color: #f97316; }
    .fill-alpukat { background-color: #15803d; }
    .fill-jeruk { background-color: #881337; }
    .fill-naga { background-color: #166534; }
    .fill-default { background-color: #3b82f6; }

    /* Bottom Row Actions */
    .card-bottom-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 6px;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 22px;
        cursor: pointer;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .2s;
        border-radius: 22px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .2s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + .toggle-slider {
        background-color: #15803d;
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(16px);
    }

    .btn-card-action {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 800;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-card-action:hover {
        background-color: #f1f5f9;
    }

    .btn-card-restock {
        background-color: #881337;
        border: 1px solid #881337;
        color: #ffffff;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
    }

    /* Section: Katalog Resep & Menu POS */
    .pos-sync-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 18px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    .pos-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .pos-title-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 900;
        color: #0f172a;
    }

    .badge-sync {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .search-box-wrap {
        position: relative;
        margin-bottom: 12px;
    }

    .search-input {
        width: 100%;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 9px 12px 9px 36px;
        font-size: 12px;
        font-weight: 600;
        color: #0f172a;
        outline: none;
    }

    .search-input:focus {
        border-color: #137333;
        background-color: #ffffff;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    /* Item Warning in Catalog (Nanas) */
    .catalog-item-warn {
        background-color: #fef9c3;
        border: 1px solid #fef08a;
        border-radius: 12px;
        padding: 10px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .btn-warn-restock {
        background-color: #fde047;
        border: 1px solid #facc15;
        color: #854d0e;
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 440px;
        padding: 20px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-height: 90vh;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="stok-container">

    <!-- Top Banner: Manajemen Stok -->
    <div class="stok-banner">
        <div>
            <div class="stok-banner-title">Manajemen Stok</div>
            <div class="stok-banner-sub">
                <!-- Clock Icon -->
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Audit Rutin: Pukul 14:00 WIB</span>
            </div>
        </div>
        <div class="stok-banner-icon">
            <!-- Shopping Bag Outline SVG -->
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
        </div>
    </div>

    <!-- 3 Summary Metric Pill Cards -->
    <div class="metric-row">
        <a href="{{ route('panel.staf.stok.index', ['tab' => 'semua']) }}" class="metric-card metric-card-total">
            <div class="metric-num">{{ $totalItem }}</div>
            <div class="metric-label">Semua Item</div>
        </a>

        <a href="{{ route('panel.staf.stok.index', ['tab' => 'kritis']) }}" class="metric-card metric-card-kritis">
            <div class="metric-num">{{ $stokKritisCount }}</div>
            <div class="metric-label">Stok Menipis</div>
        </a>

        <a href="{{ route('panel.staf.stok.index', ['tab' => 'habis']) }}" class="metric-card metric-card-habis">
            <div class="metric-num">{{ $habisCount }}</div>
            <div class="metric-label">Habis</div>
        </a>
    </div>

    <!-- Two Main Action Buttons -->
    <button type="button" class="btn-action-primary" onclick="openModalPasokan()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Input Pasokan / Masuk</span>
    </button>

    <button type="button" class="btn-action-secondary" onclick="openModalPembuangan()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
        <span>Catat Pembuangan (Rusak/Busuk)</span>
    </button>

    <!-- Section Header: Bahan Baku Segar -->
    <div class="section-header-row">
        <div class="section-title">Bahan Baku Segar</div>
        <div class="section-sub">Berdasarkan Sisa Kapasitas</div>
    </div>

    <!-- Filter Pills -->
    <div class="filter-pills-row">
        <a href="{{ route('panel.staf.stok.index', ['tab' => 'semua']) }}" 
           class="filter-pill {{ $statusFilter === 'semua' ? 'active' : '' }}">
            Semua Bahan ({{ $totalItem }})
        </a>
        <a href="{{ route('panel.staf.stok.index', ['tab' => 'kritis']) }}" 
           class="filter-pill {{ $statusFilter === 'kritis' ? 'active' : '' }}">
            Stok Kritis ({{ $stokKritisCount }})
        </a>
        <a href="{{ route('panel.staf.stok.index', ['tab' => 'habis']) }}" 
           class="filter-pill {{ $statusFilter === 'habis' ? 'active' : '' }}">
            Habis ({{ $habisCount }})
        </a>
    </div>

    <!-- 2-Column Grid for Fresh Ingredients -->
    <div class="bahan-grid">
        @forelse($daftarBahan as $b)
            @php
                $namaLower = strtolower($b->nama);
                $stokKg = ($b->satuan_dasar === 'g') ? ((float)$b->stok / 1000) : (float)$b->stok;
                $satuanTampil = ($b->satuan_dasar === 'g') ? 'kg' : $b->satuan_dasar;
                $isHabis = ($stokKg <= 0);
                $isKritis = (!$isHabis && $b->is_kritis);

                // Styling Class
                if (str_contains($namaLower, 'mangga')) {
                    $cardClass = 'card-mangga';
                    $pillClass = 'pill-mangga';
                    $fillClass = 'fill-mangga';
                } elseif (str_contains($namaLower, 'alpukat')) {
                    $cardClass = 'card-alpukat';
                    $pillClass = 'pill-alpukat';
                    $fillClass = 'fill-alpukat';
                } elseif (str_contains($namaLower, 'jeruk') || $isHabis) {
                    $cardClass = 'card-jeruk';
                    $pillClass = 'pill-jeruk';
                    $fillClass = 'fill-jeruk';
                } elseif (str_contains($namaLower, 'naga')) {
                    $cardClass = 'card-naga';
                    $pillClass = 'pill-naga';
                    $fillClass = 'fill-naga';
                } else {
                    $cardClass = 'card-default';
                    $pillClass = 'pill-default';
                    $fillClass = 'fill-default';
                }

                // Dot class
                if ($isHabis) {
                    $dotClass = 'dot-habis';
                } elseif ($isKritis) {
                    $dotClass = 'dot-kritis';
                } else {
                    $dotClass = 'dot-aman';
                }

                // Progress Bar width calculation (asumsi kapasitas max ~20kg)
                $maxKapasitas = 20.0;
                $pct = min(100, max(0, ($stokKg / $maxKapasitas) * 100));
                if ($isHabis) $pct = 0;
            @endphp

            <div class="bahan-card {{ $cardClass }}">
                <div>
                    <!-- Card Top: Name Pill & Status Dot -->
                    <div class="card-top-row">
                        <span class="pill-nama {{ $pillClass }}">{{ $b->nama }}</span>
                        <span class="status-dot {{ $dotClass }}" title="{{ $isHabis ? 'Habis' : ($isKritis ? 'Menipis' : 'Aman') }}"></span>
                    </div>

                    <!-- Stock Amount -->
                    <div class="stock-amount-row">
                        <span class="stock-amount-val {{ $isHabis ? 'text-habis' : '' }}">
                            {{ number_format($stokKg, 1, '.', '') }}
                        </span>
                        <span class="stock-amount-unit">{{ $satuanTampil }} tersisa</span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="capacity-bar">
                        <div class="capacity-bar-fill {{ $fillClass }}" style="width: {{ $pct }}%;"></div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="card-bottom-actions">
                    @if($isHabis)
                        <span style="font-size: 11px; font-weight: 800; color: #881337;">Tutup</span>
                        <button type="button" class="btn-card-restock" onclick="openModalPasokan({{ $b->id }}, '{{ $b->nama }}')">
                            Restock
                        </button>
                    @else
                        <!-- Toggle Switch POS Availability -->
                        <form action="{{ route('panel.staf.stok.toggle-aktif', $b->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <label class="toggle-switch" title="Aktif / Tutup di POS Menu">
                                <input type="checkbox" name="aktif" value="1" {{ $b->aktif ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="toggle-slider"></span>
                            </label>
                        </form>

                        <button type="button" class="btn-card-action" onclick="openModalAudit({{ $b->id }}, '{{ $b->nama }}', {{ $stokKg }}, '{{ $satuanTampil }}')">
                            {{ $isKritis ? 'Ubah' : 'Audit' }}
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: #ffffff; border-radius: 14px; padding: 24px; text-align: center; color: #64748b;">
                Tidak ada data bahan pada filter ini.
            </div>
        @endforelse
    </div>

    <!-- Section: Katalog Resep & Menu POS -->
    <div class="pos-sync-card">
        <div class="pos-header-row">
            <div class="pos-title-wrap">
                <!-- Recipe List Icon -->
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Katalog Resep & Menu POS</span>
            </div>
            <span class="badge-sync">Tersinkronisasi</span>
        </div>

        <!-- Search Bar with Live Filter -->
        <div class="search-box-wrap">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="katalogSearchInput" class="search-input" placeholder="Cari nama jus atau ketersediaan bahan..." onkeyup="filterKatalog()">
        </div>

        <!-- Sample Warning Alert for Low Stock (e.g., Nanas) -->
        <div class="catalog-item-warn">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 18px;">🍍</span>
                <span style="font-weight: 800; font-size: 13px; color: #854d0e;">Nanas (Stok Kritis)</span>
            </div>
            <button type="button" class="btn-warn-restock" onclick="openModalPasokanByNama('Nanas')">
                Restock
            </button>
        </div>

        <!-- Live Products Grid / List -->
        <div id="katalogListContainer">
            @foreach($katalogProduk as $prod)
                <div class="katalog-item-row" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-bottom: 1px dashed #f1f5f9;">
                    <div style="font-weight: 700; font-size: 13px; color: #1e293b;">
                        🥤 {{ $prod->nama }}
                    </div>
                    <div style="font-size: 11px; font-weight: 800; color: {{ $prod->stok > 0 ? '#15803d' : '#991b1b' }};">
                        {{ $prod->stok > 0 ? 'Siap Jual (' . $prod->stok . ' cup)' : 'Habis / Tutup' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Action Button: Penggunaan & Kerusakan Bahan -->
    <button type="button" class="btn-action-primary" style="margin-bottom: 24px;" onclick="openModalRiwayat()">
        <!-- Clipboard History Icon -->
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
        </svg>
        <span>Penggunaan & Kerusakan Bahan</span>
    </button>

</div>

<!-- MODAL 1: INPUT PASOKAN / RESTOCK MASUK -->
<div class="modal-overlay" id="modalPasokan">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div style="font-weight: 900; font-size: 16px; color: #137333; display: flex; align-items: center; gap: 8px;">
                <span>📦</span>
                <span>Input Pasokan / Masuk</span>
            </div>
            <button type="button" onclick="closeModalPasokan()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form action="{{ route('panel.staf.stok.pasokan') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Pilih Bahan Baku</label>
                <select name="bahan_id" id="pasokanBahanSelect" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($semuaBahanList as $sb)
                        <option value="{{ $sb->id }}">{{ $sb->nama }} (Sisa: {{ number_format(($sb->satuan_dasar === 'g' ? $sb->stok/1000 : $sb->stok), 1) }} {{ $sb->satuan_dasar === 'g' ? 'kg' : $sb->satuan_dasar }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 8px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Jumlah Masuk</label>
                    <input type="number" step="0.1" name="jumlah_masuk" placeholder="Contoh: 10.5" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Satuan</label>
                    <select name="satuan_input" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                        <option value="kg">kg</option>
                        <option value="g">gram</option>
                        <option value="pcs">pcs</option>
                        <option value="pack">pack</option>
                        <option value="ml">ml</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Catatan / Nama Supplier (Opsional)</label>
                <input type="text" name="catatan" placeholder="Contoh: Pasokan Petani Lokal Lembang" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="closeModalPasokan()" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="flex: 1; padding: 10px; border-radius: 8px; border: none; background: #137333; color: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Simpan Pasokan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: CATAT PEMBUANGAN (RUSAK / BUSUK) -->
<div class="modal-overlay" id="modalPembuangan">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div style="font-weight: 900; font-size: 16px; color: #991b1b; display: flex; align-items: center; gap: 8px;">
                <span>🗑️</span>
                <span>Catat Pembuangan (Rusak/Busuk)</span>
            </div>
            <button type="button" onclick="closeModalPembuangan()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <p style="font-size: 12px; color: #64748b; margin-bottom: 14px;">
            Sesuai Aturan Bisnis Bab 11, setiap buah sortiran busuk atau rusak wajib dicatat alasannya agar laporan HPP dan selisih stok tetap transparan.
        </p>

        <form action="{{ route('panel.staf.stok.pembuangan') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Pilih Bahan Rusak</label>
                <select name="bahan_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($semuaBahanList as $sb)
                        <option value="{{ $sb->id }}">{{ $sb->nama }} (Stok fisik: {{ number_format(($sb->satuan_dasar === 'g' ? $sb->stok/1000 : $sb->stok), 1) }} {{ $sb->satuan_dasar === 'g' ? 'kg' : $sb->satuan_dasar }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 8px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Jumlah Dibuang</label>
                    <input type="number" step="0.1" name="jumlah_buang" placeholder="Contoh: 1.2" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Satuan</label>
                    <select name="satuan_input" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                        <option value="kg">kg</option>
                        <option value="g">gram</option>
                        <option value="pcs">pcs</option>
                        <option value="ml">ml</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Alasan Pembuangan</label>
                <select name="alasan" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    <option value="Buah busuk / berjamur saat sortiran pagi">Buah busuk / berjamur saat sortiran pagi</option>
                    <option value="Buah lecet / memar tebal dari ekspedisi">Buah lecet / memar tebal dari ekspedisi</option>
                    <option value="Melewati batas simpan chiller (>3 hari)">Melewati batas simpan chiller (>3 hari)</option>
                    <option value="Lainnya">Lainnya...</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="closeModalPembuangan()" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="flex: 1; padding: 10px; border-radius: 8px; border: none; background: #dc2626; color: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Catat Pembuangan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 3: AUDIT STOK OPNAME 14:00 WIB -->
<div class="modal-overlay" id="modalAudit">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div style="font-weight: 900; font-size: 16px; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <span>⚖️</span>
                <span>Audit Stok Rutin 14:00 WIB</span>
            </div>
            <button type="button" onclick="closeModalAudit()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <p style="font-size: 12px; color: #64748b; margin-bottom: 14px;">
            Masukkan angka timbangan fisik riil saat ini untuk memperbarui kapasitas bahan di sistem secara akurat.
        </p>

        <form id="formAudit" action="" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Nama Bahan</label>
                <input type="text" id="auditNamaBahan" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 8px; font-size: 13px; font-weight: 800;">
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 8px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Stok Timbangan Fisik</label>
                    <input type="number" step="0.1" name="stok_fisik" id="auditStokFisik" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Satuan</label>
                    <input type="text" name="satuan_input" id="auditSatuanInput" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 8px; font-size: 13px;">
                </div>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="closeModalAudit()" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="flex: 1; padding: 10px; border-radius: 8px; border: none; background: #15803d; color: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Simpan Hasil Audit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 4: RIWAYAT PENGGUNAAN & KERUSAKAN BAHAN -->
<div class="modal-overlay" id="modalRiwayat">
    <div class="modal-card" style="max-width: 520px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <div style="font-weight: 900; font-size: 16px; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <span>📋</span>
                <span>Log Penggunaan & Kerusakan Bahan</span>
            </div>
            <button type="button" onclick="closeModalRiwayat()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <div style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
            Jejak aktivitas pasokan masuk, pembuangan bahan rusak, dan audit opname staf.
        </div>

        <div style="max-height: 380px; overflow-y: auto;">
            @forelse($logAktivitasStok as $log)
                <div style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                        <span style="font-weight: 800; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $log->aksi)) }}</span>
                        <span style="color: #94a3b8; font-size: 10px;">{{ $log->created_at->format('d M H:i') }}</span>
                    </div>
                    <div style="color: #475569;">{{ $log->deskripsi }}</div>
                </div>
            @empty
                <div style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada riwayat aktivitas stok tercatat.</div>
            @endforelse
        </div>

        <button type="button" onclick="closeModalRiwayat()" style="width: 100%; margin-top: 14px; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
            Tutup
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Modal Pasokan
    function openModalPasokan(bahanId = null, bahanNama = '') {
        const select = document.getElementById('pasokanBahanSelect');
        if (bahanId && select) {
            select.value = bahanId;
        }
        document.getElementById('modalPasokan').classList.add('active');
    }

    function openModalPasokanByNama(nama) {
        const select = document.getElementById('pasokanBahanSelect');
        if (select) {
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].text.includes(nama)) {
                    select.selectedIndex = i;
                    break;
                }
            }
        }
        document.getElementById('modalPasokan').classList.add('active');
    }

    function closeModalPasokan() {
        document.getElementById('modalPasokan').classList.remove('active');
    }

    // Modal Pembuangan
    function openModalPembuangan() {
        document.getElementById('modalPembuangan').classList.add('active');
    }

    function closeModalPembuangan() {
        document.getElementById('modalPembuangan').classList.remove('active');
    }

    // Modal Audit
    function openModalAudit(id, nama, stokKg, satuan) {
        document.getElementById('formAudit').action = '/panel/stok/' + id + '/audit';
        document.getElementById('auditNamaBahan').value = nama;
        document.getElementById('auditStokFisik').value = stokKg;
        document.getElementById('auditSatuanInput').value = satuan;
        document.getElementById('modalAudit').classList.add('active');
    }

    function closeModalAudit() {
        document.getElementById('modalAudit').classList.remove('active');
    }

    // Modal Riwayat
    function openModalRiwayat() {
        document.getElementById('modalRiwayat').classList.add('active');
    }

    function closeModalRiwayat() {
        document.getElementById('modalRiwayat').classList.remove('active');
    }

    // Close Modals on click outside
    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    // Live search filter in Katalog Resep & Menu POS
    function filterKatalog() {
        const input = document.getElementById('katalogSearchInput');
        const filter = input.value.toLowerCase();
        const container = document.getElementById('katalogListContainer');
        const rows = container.getElementsByClassName('katalog-item-row');

        for (let i = 0; i < rows.length; i++) {
            const txt = rows[i].textContent || rows[i].innerText;
            if (txt.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>
@endsection
