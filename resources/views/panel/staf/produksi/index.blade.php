@extends('layouts.staf')

@section('title', 'Antrean Racik Jus & KDS Mode')

@section('styles')
<style>
    /* Antrean Header */
    .queue-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .queue-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
    }

    .queue-dot {
        width: 10px;
        height: 10px;
        background-color: #22c55e;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
    }

    .queue-pill {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border-radius: 20px;
        text-transform: uppercase;
    }

    /* Segmented Control Switcher */
    .segmented-control {
        background-color: #e2e8f0;
        padding: 4px;
        border-radius: 14px;
        display: flex;
        gap: 4px;
        margin-bottom: 14px;
    }

    .segment-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 12px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 10px;
        text-decoration: none;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .segment-btn.active {
        background-color: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 5px rgba(0,0,0,0.06);
    }

    /* Blender Station Status Banner */
    .station-banner {
        background: linear-gradient(135deg, #137333 0%, #0f5132 100%);
        border-radius: 16px;
        padding: 13px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(19, 115, 51, 0.2);
        color: #ffffff;
    }

    .station-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .station-icon-wrap {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
    }

    .station-title {
        font-size: 14px;
        font-weight: 800;
        line-height: 1.2;
    }

    .station-sub {
        font-size: 11px;
        opacity: 0.85;
        margin-top: 2px;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
    }
    .badge-merah { background-color: #ffe4e6; color: #e11d48; }
    .badge-oranye { background-color: #ffedd5; color: #ea580c; }
    .badge-hijau { background-color: #dcfce7; color: #166534; }

    /* ===== KARTU 1: SEDANG DIBUAT (ACTIVE BLENDER) ===== */
    .active-card-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #137333;
        box-shadow: 0 6px 16px rgba(19, 115, 51, 0.12);
        margin-bottom: 14px;
        background-color: #ffffff;
    }

    .active-card-header {
        background-color: #137333;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #ffffff;
    }

    .active-card-pill {
        background-color: #fee2e2;
        color: #dc2626;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 16px;
    }

    .active-card-id {
        font-size: 16px;
        font-weight: 900;
        letter-spacing: -0.3px;
    }

    .active-card-timer {
        background-color: #fee2e2;
        color: #dc2626;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .active-card-body {
        padding: 14px;
    }

    .customer-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .customer-info {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .customer-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
    }

    .type-pill {
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 14px;
    }
    .pill-dinein { background-color: #dcfce7; color: #15803d; }
    .pill-takeaway { background-color: #ffedd5; color: #ea580c; border: 1px solid #fed7aa; }

    .items-box {
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 14px;
    }

    .item-primary {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .item-notes {
        font-size: 12px;
        font-weight: 700;
        color: #059669;
    }

    .btn-finish-racik {
        width: 100%;
        background-color: #198754;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 13px 16px;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: background-color 0.15s;
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.25);
    }

    .btn-finish-racik:hover {
        background-color: #157347;
    }

    /* ===== KARTU 2: BERIKUTNYA ===== */
    .next-card {
        background-color: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .next-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .badge-next {
        background-color: #ffedd5;
        color: #c2410c;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 14px;
    }

    .card-id-text {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .badge-queue-num {
        background-color: #fed7aa;
        color: #9a3412;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-start-now {
        width: 100%;
        background-color: #ea580c;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: background-color 0.15s;
        margin-top: 10px;
        box-shadow: 0 4px 10px rgba(234, 88, 12, 0.25);
    }

    .btn-start-now:hover {
        background-color: #c2410c;
    }

    /* ===== KARTU 3: MENUNGGU ===== */
    .waiting-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    .badge-waiting {
        background-color: #dcfce7;
        color: #166534;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 14px;
    }

    .badge-waiting-num {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 6px;
    }

    /* Bottom Quick Action: Catat Bahan Tumpah / Gagal */
    .spill-report-btn {
        background-color: #fee2e2;
        border: 1px solid #fecdd3;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #991b1b;
        font-size: 13px;
        font-weight: 800;
        width: 100%;
        cursor: pointer;
        transition: all 0.15s;
        margin-top: 8px;
        margin-bottom: 18px;
        text-align: left;
    }

    .spill-report-btn:hover {
        background-color: #fecaca;
    }

    /* KDS Grid Layout */
    .kds-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 14px;
    }

    @media (min-width: 640px) {
        .kds-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 992px) {
        .kds-grid {
            grid-template-columns: repeat(3, 1fr);
        }
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
    }
</style>
@endsection

@section('content')
<div class="produksi-container">

    <!-- Top Header: Antrean Racik Jus & Counter -->
    <div class="queue-header">
        <div class="queue-title">
            <span class="queue-dot"></span>
            <span>Antrean Racik Jus</span>
        </div>
        <div class="queue-pill">
            {{ $totalAktifCount }} PESANAN AKTIF
        </div>
    </div>

    <!-- Segmented Control: Tampilan Kartu vs KDS Mode -->
    <div class="segmented-control">
        <a href="{{ route('panel.staf.produksi.index', ['mode' => 'kartu']) }}" 
           class="segment-btn {{ $mode === 'kartu' ? 'active' : '' }}">
            <!-- List Icon -->
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            <span>Tampilan Kartu</span>
        </a>
        <a href="{{ route('panel.staf.produksi.index', ['mode' => 'kds']) }}" 
           class="segment-btn {{ $mode === 'kds' ? 'active' : '' }}">
            <!-- Grid KDS Icon -->
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
            </svg>
            <span>KDS Mode</span>
        </a>
    </div>

    <!-- Blender Station Status Banner -->
    <div class="station-banner">
        <div class="station-info">
            <div class="station-icon-wrap">
                <!-- Blender SVG -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 2h10l-2 11a4 4 0 0 1-4 3h0a4 4 0 0 1-4-3L5 2z"></path>
                    <path d="M10 16v4"></path>
                    <path d="M7 20h10"></path>
                </svg>
            </div>
            <div>
                <div class="station-title">Blender Station 01 Aktif</div>
                <div class="station-sub">Rata-rata racik: 2.8 mnt / porsi</div>
            </div>
        </div>
        <div class="status-badge badge-{{ $bebanWarna }}">
            {{ $statusBeban }}
        </div>
    </div>

    @if($mode === 'kartu')
        <!-- ============================================== -->
        <!-- MODE 1: TAMPILAN KARTU (MOBILE-FIRST)        -->
        <!-- ============================================== -->

        @if(!$sedangDibuat && !$berikutnya && $antreanMenunggu->isEmpty())
            <div style="background: #ffffff; border-radius: 16px; padding: 32px 20px; text-align: center; border: 1px dashed #cbd5e1; margin-bottom: 16px;">
                <div style="font-size: 36px; margin-bottom: 8px;">🥤</div>
                <div style="font-weight: 800; font-size: 15px; color: #1e293b;">Belum Ada Antrean Racik Jus</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Semua pesanan jus sudah selesai atau sedang menunggu pembayaran.</div>
            </div>
        @endif

        <!-- KARTU 1: SEDANG DIBUAT (BLENDER UTAMA) -->
        @if($sedangDibuat)
            @php
                $itemPertama = $sedangDibuat->itemPesanan->first();
                $isTakeaway = str_contains(strtolower($sedangDibuat->alamat_pengiriman ?? ''), 'takeaway') || str_contains(strtolower($sedangDibuat->catatan ?? ''), 'takeaway');
                $tipeLabel = $isTakeaway ? 'takeaway' : 'Dine-in';
            @endphp
            <div class="active-card-container">
                <div class="active-card-header">
                    <div class="active-card-pill">Sedang Dibuat</div>
                    <div class="active-card-id">#{{ $sedangDibuat->kode_pesanan }}</div>
                    <div class="active-card-timer" id="countdownTimer">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span id="countdownText">04:12 mnt tersisa</span>
                    </div>
                </div>

                <div class="active-card-body">
                    <!-- Customer Row -->
                    <div class="customer-row">
                        <div class="customer-info">
                            <div class="customer-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <span>{{ $sedangDibuat->nama_pembeli }}</span>
                        </div>
                        <div class="type-pill {{ $isTakeaway ? 'pill-takeaway' : 'pill-dinein' }}">
                            {{ $tipeLabel }}
                        </div>
                    </div>

                    <!-- Items Box -->
                    <div class="items-box">
                        @foreach($sedangDibuat->itemPesanan as $item)
                            <div style="{{ !$loop->first ? 'margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e2e8f0;' : '' }}">
                                <div class="item-primary">
                                    {{ $item->jumlah }}x {{ $item->nama_produk }}
                                </div>
                                <div class="item-notes">
                                    {{ $sedangDibuat->catatan ?: 'Standard SOP OMYU' }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Action Button: Tandai Selesai Racik -->
                    <form action="{{ route('panel.staf.produksi.selesai-racik', $sedangDibuat->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-finish-racik">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>Tandai Selesai Racik (Pindah ke Siap Diambil)</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- KARTU 2: BERIKUTNYA -->
        @if($berikutnya)
            @php
                $itemBerikutnya = $berikutnya->itemPesanan->first();
                $isTakeaway2 = str_contains(strtolower($berikutnya->alamat_pengiriman ?? ''), 'takeaway') || str_contains(strtolower($berikutnya->catatan ?? ''), 'takeaway');
                $tipeLabel2 = $isTakeaway2 ? 'takeaway' : 'Dine-in';
            @endphp
            <div class="next-card">
                <div class="next-card-header">
                    <div class="badge-next">Berikutnya</div>
                    <div class="card-id-text">#{{ $berikutnya->kode_pesanan }}</div>
                    <div class="badge-queue-num">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Antrean #2</span>
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-info">
                        <div class="customer-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <span>{{ $berikutnya->nama_pembeli }}</span>
                    </div>
                    <div class="type-pill {{ $isTakeaway2 ? 'pill-takeaway' : 'pill-dinein' }}">
                        {{ $tipeLabel2 }}
                    </div>
                </div>

                <div class="items-box" style="margin-bottom: 0;">
                    @foreach($berikutnya->itemPesanan as $item)
                        <div style="{{ !$loop->first ? 'margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e2e8f0;' : '' }}">
                            <div class="item-primary" style="margin-bottom: 2px;">
                                {{ $item->jumlah }}x {{ $item->nama_produk }}
                            </div>
                            @if($berikutnya->catatan)
                                <div class="item-notes">{{ $berikutnya->catatan }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Action Button: Mulai Buat Sekarang -->
                <form action="{{ route('panel.staf.produksi.mulai-racik', $berikutnya->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-start-now">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        <span>Mulai Buat Sekarang</span>
                    </button>
                </form>
            </div>
        @endif

        <!-- KARTU 3 DST: MENUNGGU -->
        @foreach($antreanMenunggu as $idx => $menunggu)
            @php
                $isTakeaway3 = str_contains(strtolower($menunggu->alamat_pengiriman ?? ''), 'takeaway') || str_contains(strtolower($menunggu->catatan ?? ''), 'takeaway');
                $queueNumber = $idx + 3;
            @endphp
            <div class="waiting-card">
                <div class="next-card-header">
                    <div class="badge-waiting">Menunggu</div>
                    <div class="card-id-text">#{{ $menunggu->kode_pesanan }}</div>
                    <div class="badge-waiting-num">Antrean #{{ $queueNumber }}</div>
                </div>

                <div class="customer-row" style="margin-bottom: 8px;">
                    <div class="customer-info" style="font-size: 14px;">
                        <span>👤 {{ $menunggu->nama_pembeli }}</span>
                    </div>
                    <div class="type-pill {{ $isTakeaway3 ? 'pill-takeaway' : 'pill-dinein' }}" style="font-size: 10px; padding: 2px 8px;">
                        {{ $isTakeaway3 ? 'takeaway' : 'Dine-in' }}
                    </div>
                </div>

                <div class="items-box" style="margin-bottom: 8px; padding: 10px;">
                    @foreach($menunggu->itemPesanan as $item)
                        <div class="item-primary" style="font-size: 13px; margin-bottom: 2px;">
                            {{ $item->jumlah }}x {{ $item->nama_produk }}
                        </div>
                        @if($menunggu->catatan)
                            <div class="item-notes" style="font-size: 11px;">{{ $menunggu->catatan }}</div>
                        @endif
                    @endforeach
                </div>

                <form action="{{ route('panel.staf.produksi.mulai-racik', $menunggu->id) }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px; font-size: 12px; font-weight: 700; color: #475569; cursor: pointer;">
                        ▶ Ambil ke Blender
                    </button>
                </form>
            </div>
        @endforeach

    @else
        <!-- ============================================== -->
        <!-- MODE 2: KDS MODE (KITCHEN DISPLAY SYSTEM GRID)-->
        <!-- ============================================== -->
        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 13px; font-weight: 700; color: #475569;">
                🖥️ Kitchen Display System (Auto-sync setiap 15 detik)
            </div>
            <button type="button" onclick="window.location.reload()" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                🔄 Refresh Layar
            </button>
        </div>

        <div class="kds-grid">
            @forelse($pesananAktif as $order)
                @php
                    $isSedangDiproses = ($order->status_pesanan === 'diproses');
                    $isTakeawayKds = str_contains(strtolower($order->alamat_pengiriman ?? ''), 'takeaway');
                @endphp
                <div style="background: #ffffff; border-radius: 14px; border: {{ $isSedangDiproses ? '2px solid #137333' : '1px solid #cbd5e1' }}; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
                    <!-- KDS Header -->
                    <div style="background: {{ $isSedangDiproses ? '#137333' : '#f8fafc' }}; color: {{ $isSedangDiproses ? '#ffffff' : '#0f172a' }}; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0;">
                        <div>
                            <span style="font-weight: 900; font-size: 15px;">#{{ $order->kode_pesanan }}</span>
                            <div style="font-size: 11px; opacity: 0.85;">{{ $order->created_at->format('H:i') }} ({{ $order->created_at->diffForHumans() }})</div>
                        </div>
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 800; background: {{ $isSedangDiproses ? '#fee2e2' : '#f1f5f9' }}; color: {{ $isSedangDiproses ? '#dc2626' : '#475569' }};">
                            {{ $isSedangDiproses ? 'Sedang Dibuat' : 'Antrean' }}
                        </span>
                    </div>

                    <!-- KDS Body -->
                    <div style="padding: 12px; flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-weight: 800; font-size: 14px; color: #0f172a;">👤 {{ $order->nama_pembeli }}</span>
                            <span class="type-pill {{ $isTakeawayKds ? 'pill-takeaway' : 'pill-dinein' }}" style="font-size: 10px; padding: 2px 8px;">
                                {{ $isTakeawayKds ? 'takeaway' : 'Dine-in' }}
                            </span>
                        </div>

                        <div style="background: #f8fafc; border-radius: 8px; padding: 8px; margin-bottom: 8px;">
                            @foreach($order->itemPesanan as $it)
                                <div style="font-weight: 800; font-size: 13px; color: #1e293b;">
                                    {{ $it->jumlah }}x {{ $it->nama_produk }}
                                </div>
                                @if($order->catatan)
                                    <div style="font-size: 11px; color: #059669; font-weight: 700;">{{ $order->catatan }}</div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- KDS Footer Action -->
                    <div style="padding: 10px 12px; background: #ffffff; border-top: 1px solid #f1f5f9;">
                        @if($isSedangDiproses)
                            <form action="{{ route('panel.staf.produksi.selesai-racik', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-finish-racik" style="padding: 10px; font-size: 12px;">
                                    ✓ Selesai Racik (Pick-Up)
                                </button>
                            </form>
                        @else
                            <form action="{{ route('panel.staf.produksi.mulai-racik', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-start-now" style="padding: 10px; font-size: 12px; margin-top: 0;">
                                    ▶ Mulai Buat
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; background: #ffffff; border-radius: 14px; padding: 30px; text-align: center; color: #64748b;">
                    Tidak ada pesanan aktif di dapur.
                </div>
            @endforelse
        </div>
    @endif

    <!-- Quick Action Bar: Catat Bahan Tumpah / Gagal Racik (K-11) -->
    <button type="button" class="spill-report-btn" onclick="openModalSpill()">
        <!-- Note / Document SVG Icon -->
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Catat Bahan Tumpah / Gagal / gagal racik, dll</span>
    </button>

</div>

<!-- Modal Pencatatan Bahan Tumpah / Gagal Racik (K-11) -->
<div class="modal-overlay" id="modalSpill">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <div style="font-weight: 800; font-size: 16px; color: #991b1b; display: flex; align-items: center; gap: 8px;">
                <span>⚠️</span>
                <span>Catat Bahan Rusak / Gagal Racik</span>
            </div>
            <button type="button" onclick="closeModalSpill()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <p style="font-size: 12px; color: #64748b; margin-bottom: 14px;">
            SOP OMYU Bab 11: Setiap bahan buah tumpah, gagal racik, atau gelas pecah wajib dicatat agar pengurangan stok dan HPP akurat di laporan harian.
        </p>

        <form action="{{ route('panel.staf.produksi.catat-gagal') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Pilih Produk / Bahan Jus</label>
                <input type="hidden" name="tipe_item" value="produk">
                <select name="item_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    <option value="">-- Pilih Minuman yang Gagal --</option>
                    @foreach($daftarProduk as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->nama }} (Sisa Stok: {{ $prod->stok }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Jumlah Cup / Porsi Rusak</label>
                <input type="number" name="jumlah" value="1" min="1" max="50" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 4px; color: #334155;">Alasan Kerusakan / Insiden</label>
                <select name="alasan" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; margin-bottom: 6px;">
                    <option value="Blender tumpah saat penuangan">Blender tumpah saat penuangan</option>
                    <option value="Kelebihan gula / salah takaran resep">Kelebihan gula / salah takaran resep</option>
                    <option value="Gelas cup pecah / retak saat sealing">Gelas cup pecah / retak saat sealing</option>
                    <option value="Buah asam / tidak layak konsumsi">Buah asam / tidak layak konsumsi</option>
                    <option value="Lainnya">Lainnya...</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="closeModalSpill()" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="flex: 1; padding: 10px; border-radius: 8px; border: none; background: #dc2626; color: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Modal toggle
    function openModalSpill() {
        document.getElementById('modalSpill').classList.add('active');
    }

    function closeModalSpill() {
        document.getElementById('modalSpill').classList.remove('active');
    }

    // Close modal on click outside
    document.getElementById('modalSpill').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModalSpill();
        }
    });

    // Countdown Timer Simulator untuk 'Sedang Dibuat'
    (function() {
        let totalSeconds = 252; // 04:12 mnt
        const textEl = document.getElementById('countdownText');
        if (!textEl) return;

        setInterval(function() {
            if (totalSeconds > 0) {
                totalSeconds--;
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                textEl.innerText = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0') + ' mnt tersisa';
            } else {
                textEl.innerText = '00:00 Waktu habis';
            }
        }, 1000);
    })();

    // Auto-refresh di KDS Mode setiap 20 detik
    @if($mode === 'kds')
    setTimeout(function() {
        window.location.reload();
    }, 20000);
    @endif
</script>
@endsection

