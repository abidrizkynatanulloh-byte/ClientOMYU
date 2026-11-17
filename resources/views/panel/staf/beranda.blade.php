@extends('layouts.staf')

@section('title', 'Dashboard Operasional Barista & Kasir')

@section('styles')
<style>
    /* Styling khusus Dashboard Barista Mobile-First */
    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* 1. Shift Card */
    .shift-card {
        background: linear-gradient(135deg, #137333 0%, #0d5a26 100%);
        border-radius: 18px;
        padding: 14px 16px;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 14px rgba(19, 115, 51, 0.2);
    }
    .shift-label-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        font-weight: 800;
        color: #fef08a;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .shift-clock-icon {
        width: 14px;
        height: 14px;
        background: #facc15;
        color: #0d5a26;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 900;
    }
    .shift-title {
        font-size: 15px;
        font-weight: 900;
        line-height: 1.2;
    }
    .shift-barista {
        font-size: 11px;
        color: #bbf7d0;
        margin-top: 2px;
        font-weight: 600;
    }
    .shift-status-pill {
        background: #a7f3d0;
        color: #064e3b;
        border-radius: 20px;
        padding: 8px 12px;
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .pulse-dot-green {
        width: 8px;
        height: 8px;
        background: #059669;
        border-radius: 50%;
        animation: pulse 1.8s infinite;
    }

    /* 2. Section Headers */
    .section-title-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-top: 4px;
        margin-bottom: 8px;
    }
    .section-heading {
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
    }
    .update-time {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }

    /* 3. Ringkasan Antrean 2x2 Grid */
    .queue-summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .queue-card {
        border-radius: 16px;
        padding: 12px 14px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 96px;
        transition: 0.15s transform, 0.15s box-shadow;
        border: 1px solid transparent;
    }
    .queue-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .queue-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }
    .queue-icon-box {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .queue-badge {
        font-size: 9px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #ffffff;
    }
    .queue-number {
        font-size: 26px;
        font-weight: 900;
        line-height: 1;
        margin: 2px 0 3px;
    }
    .queue-label {
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    /* Color Themes for 4 Queue Cards */
    .qc-baru { background: #e8f7ee; border-color: #d1ebd8; }
    .qc-baru .queue-number { color: #166534; }
    .qc-baru .queue-icon-box { background: #dcfce7; color: #166534; }
    .qc-baru .queue-badge { background: #15803d; }

    .qc-live { background: #fff1ec; border-color: #ffe0d6; }
    .qc-live .queue-number { color: #c2410c; }
    .qc-live .queue-icon-box { background: #ffedd5; color: #c2410c; }
    .qc-live .queue-badge { background: #ea580c; }

    .qc-verif { background: #fefde8; border-color: #fef08a; }
    .qc-verif .queue-number { color: #854d0e; }
    .qc-verif .queue-icon-box { background: #fef9c3; color: #854d0e; }
    .qc-verif .queue-badge { background: #65a30d; }

    .qc-pickup { background: #e8f7ee; border-color: #d1ebd8; }
    .qc-pickup .queue-number { color: #166534; }
    .qc-pickup .queue-icon-box { background: #dcfce7; color: #166534; }
    .qc-pickup .queue-badge { background: #15803d; }

    /* 4. Bahan Baku Segar Card */
    .bahan-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }
    .bahan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .bahan-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }
    .bahan-sku-pill {
        background: #e8f5e9;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 10px;
    }
    .bahan-cols {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
    }
    .bahan-box {
        border-radius: 12px;
        padding: 10px 6px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .bahan-box-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 10px;
        color: #ffffff;
        margin-bottom: 4px;
    }
    .bahan-box-num {
        font-size: 22px;
        font-weight: 900;
        line-height: 1.1;
    }
    .bahan-box-name {
        font-size: 10px;
        font-weight: 700;
        margin-top: 2px;
    }

    .bb-tersedia { background: #e8f7ee; border: 1px solid #bbf7d0; }
    .bb-tersedia .bahan-box-badge { background: #14532d; }
    .bb-tersedia .bahan-box-num { color: #166534; }

    .bb-menipis { background: #fff7ed; border: 1px solid #fed7aa; }
    .bb-menipis .bahan-box-badge { background: #ea580c; }
    .bb-menipis .bahan-box-num { color: #c2410c; }
    .bb-menipis .bahan-box-name { color: #9a3412; }

    .bb-habis { background: #fef2f2; border: 1px solid #fecaca; }
    .bb-habis .bahan-box-badge { background: #991b1b; }
    .bb-habis .bahan-box-num { color: #b91c1c; }
    .bb-habis .bahan-box-name { color: #991b1b; }

    /* 5. Antrean Prioritas Cepat Cards */
    .order-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        margin-bottom: 10px;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }
    .order-code-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .order-code {
        font-size: 14px;
        font-weight: 900;
        color: #0f172a;
    }
    .pill-pickup {
        background: #ffedd5;
        color: #c2410c;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 7px;
    }
    .pill-takeaway {
        background: #fef9c3;
        color: #854d0e;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 7px;
    }
    .pill-time-left {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 7px;
    }
    .order-buyer {
        font-size: 11px;
        color: #475569;
        margin-bottom: 8px;
    }
    .order-items-box {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 10px;
    }
    .order-item-row {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 5px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
    }
    .order-item-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .qty-tag {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        background: #0f172a;
        color: #ffffff;
        font-size: 9px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qty-tag-orange { background: #ea580c; }
    .qty-tag-yellow { background: #ca8a04; }

    .order-item-title {
        font-weight: 700;
        color: #1e293b;
    }
    .item-pill-green {
        background: #dcfce7;
        color: #166534;
        border-radius: 10px;
        padding: 2px 7px;
        font-size: 9px;
        font-weight: 700;
    }
    .item-pill-peach {
        background: #ffedd5;
        color: #9a3412;
        border-radius: 10px;
        padding: 2px 7px;
        font-size: 9px;
        font-weight: 700;
    }
    .item-pill-yellow {
        background: #fef9c3;
        color: #854d0e;
        border-radius: 10px;
        padding: 2px 7px;
        font-size: 9px;
        font-weight: 700;
    }

    .order-action-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-racik {
        flex: 1;
        background: #15803d;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 9px;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
        transition: 0.15s;
    }
    .btn-racik:hover {
        background: #166534;
    }
    .btn-racik-peach {
        background: #fee2e2;
        color: #991b1b;
    }
    .btn-racik-peach:hover {
        background: #fecaca;
    }
    .btn-more-dots {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
    }

    /* 6. Aksi Cepat Barista (2x2 Grid) */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .quick-action-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #0f172a;
        transition: 0.15s;
    }
    .quick-action-btn:hover {
        border-color: #198754;
        transform: translateY(-1px);
    }
    .qa-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }
    .qa-text-col {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .qa-title {
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .qa-sub {
        font-size: 9px;
        color: #64748b;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="desktop-split-layout">

    <!-- Kolom 1 (Mobile Utama & Kolom Kiri Desktop) -->
    <div class="dashboard-wrapper">
        
        <!-- 1. Card Shift Berjalan -->
        <div class="shift-card">
            <div>
                <div class="shift-label-row">
                    <span class="shift-clock-icon">🕒</span>
                    <span>SHIFT BERJALAN</span>
                </div>
                <div class="shift-title">{{ $shiftInfo }}</div>
                <div class="shift-barista">Barista: {{ auth()->user()->nama }}</div>
            </div>
            <div>
                <div class="shift-status-pill">
                    <span class="pulse-dot-green"></span>
                    <span>Menerima Pesanan</span>
                </div>
            </div>
        </div>

        <!-- 2. Section Ringkasan Antrean (4 Cards) -->
        <div>
            <div class="section-title-row">
                <h2 class="section-heading">Ringkasan Antrean</h2>
                <span class="update-time">Update: {{ $waktuUpdate }}</span>
            </div>

            <div class="queue-summary-grid">
                <!-- 1. Pesanan Baru Masuk -->
                <a href="{{ route('panel.staf.pesanan.index', ['status' => 'baru']) }}" class="queue-card qc-baru">
                    <div class="queue-card-top">
                        <div class="queue-icon-box">📋</div>
                        <span class="queue-badge">Baru</span>
                    </div>
                    <div>
                        <div class="queue-number">{{ $pesananBaru }}</div>
                        <div class="queue-label">Pesanan Baru Masuk</div>
                    </div>
                </a>

                <!-- 2. Sedang Diracik -->
                <a href="{{ route('panel.staf.pesanan.index', ['status' => 'diproses']) }}" class="queue-card qc-live">
                    <div class="queue-card-top">
                        <div class="queue-icon-box">🍹</div>
                        <span class="queue-badge">Live</span>
                    </div>
                    <div>
                        <div class="queue-number">{{ $pesananDiproses }}</div>
                        <div class="queue-label">Sedang Diracik</div>
                    </div>
                </a>

                <!-- 3. Perlu Konfirmasi -->
                <a href="{{ route('panel.staf.pesanan.index', ['status' => 'dikonfirmasi']) }}" class="queue-card qc-verif">
                    <div class="queue-card-top">
                        <div class="queue-icon-box">⏳</div>
                        <span class="queue-badge">Verif</span>
                    </div>
                    <div>
                        <div class="queue-number">{{ $pesananVerif }}</div>
                        <div class="queue-label">Perlu Konfirmasi</div>
                    </div>
                </a>

                <!-- 4. Siap Diserahkan -->
                <a href="{{ route('panel.staf.pesanan.index', ['status' => 'dikirim']) }}" class="queue-card qc-pickup">
                    <div class="queue-card-top">
                        <div class="queue-icon-box">🛍️</div>
                        <span class="queue-badge">Pick Up</span>
                    </div>
                    <div>
                        <div class="queue-number">{{ $pesananSiap }}</div>
                        <div class="queue-label">Siap Diserahkan</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- 3. Card Bahan Baku Segar (16 SKU Total) -->
        <div class="bahan-card">
            <div class="bahan-header">
                <div class="bahan-header-left">
                    <span>📦</span>
                    <span>Bahan Baku Segar</span>
                </div>
                <span class="bahan-sku-pill">{{ $totalSku }} SKU Total</span>
            </div>

            <div class="bahan-cols">
                <!-- Tersedia -->
                <div class="bahan-box bb-tersedia">
                    <span class="bahan-box-badge">Tersedia</span>
                    <span class="bahan-box-num">{{ $produkTersedia }}</span>
                </div>

                <!-- Menipis -->
                <div class="bahan-box bb-menipis">
                    <span class="bahan-box-badge">Menipis</span>
                    <span class="bahan-box-num">{{ $produkMenipis }}</span>
                    <span class="bahan-box-name">{{ $namaMenipis }}</span>
                </div>

                <!-- Habis -->
                <div class="bahan-box bb-habis">
                    <span class="bahan-box-badge">Habis</span>
                    <span class="bahan-box-num">{{ $produkHabis }}</span>
                    <span class="bahan-box-name">{{ $namaHabis }}</span>
                </div>
            </div>
        </div>

        <!-- 4. Aksi Cepat Barista (2x2 Grid) -->
        <div>
            <div class="section-title-row">
                <h2 class="section-heading">Aksi Cepat Barista</h2>
            </div>

            <div class="quick-actions-grid">
                <!-- 1. Scan Pengambilan -->
                <a href="{{ route('panel.staf.pesanan.index') }}" class="quick-action-btn">
                    <div class="qa-icon" style="background: #dcfce7; color: #166534;">📷</div>
                    <div class="qa-text-col">
                        <span class="qa-title">Scan: Pengambilan</span>
                        <span class="qa-sub">Validasi serah pesanan</span>
                    </div>
                </a>

                <!-- 2. Bahan Masuk / Kasir POS -->
                <a href="{{ route('panel.staf.kasir.index') }}" class="quick-action-btn">
                    <div class="qa-icon" style="background: #fee2e2; color: #b91c1c;">🛒</div>
                    <div class="qa-text-col">
                        <span class="qa-title">Kasir / Beli Bahan</span>
                        <span class="qa-sub">Catat: Penjualan POS</span>
                    </div>
                </a>

                <!-- 3. Catat Waste -->
                <a href="{{ route('panel.staf.stok.index') }}" class="quick-action-btn">
                    <div class="qa-icon" style="background: #fce7f3; color: #9d174d;">📋</div>
                    <div class="qa-text-col">
                        <span class="qa-title">Catat Waste</span>
                        <span class="qa-sub">Buah busuk / susut</span>
                    </div>
                </a>

                <!-- 4. Tutup Shift -->
                <a href="{{ route('panel.staf.aktivitas.index') }}" class="quick-action-btn">
                    <div class="qa-icon" style="background: #fef9c3; color: #854d0e;">📄</div>
                    <div class="qa-text-col">
                        <span class="qa-title">Tutup Shift</span>
                        <span class="qa-sub">Rekap kas & cup</span>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <!-- Kolom 2 (Kolom Kanan Desktop / Bagian Bawah Mobile) -->
    <div class="dashboard-wrapper">
        
        <!-- 5. Antrean Prioritas Cepat -->
        <div>
            <div class="section-title-row">
                <h2 class="section-heading">Antrean Prioritas Cepat</h2>
                <a href="{{ route('panel.staf.pesanan.index') }}" style="font-size: 11px; font-weight: 700; color: #15803d; text-decoration: none;">Lihat Semua &rarr;</a>
            </div>

            @forelse($antreanPesanan as $idx => $pesanan)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-code-group">
                            <span class="order-code">#{{ $pesanan->kode_pesanan }}</span>
                            <span class="{{ str_contains(strtolower($pesanan->alamat_pengiriman), 'takeaway') ? 'pill-takeaway' : 'pill-pickup' }}">
                                {{ str_contains(strtolower($pesanan->alamat_pengiriman), 'takeaway') ? 'Takeaway' : 'Pick-Up ' . $pesanan->created_at->addMinutes(30)->format('H:i') }}
                            </span>
                        </div>
                        <span class="pill-time-left">
                            {{ max(1, 15 - $pesanan->created_at->diffInMinutes(now())) }} mnt lagi
                        </span>
                    </div>

                    <div class="order-buyer">
                        Pemesan: <strong>{{ $pesanan->nama_pembeli }}</strong> &bull; {{ $pesanan->alamat_pengiriman }}
                    </div>

                    <!-- Items List -->
                    <div class="order-items-box">
                        @foreach($pesanan->itemPesanan as $i => $item)
                            <div class="order-item-row">
                                <div class="order-item-left">
                                    <span class="qty-tag {{ $i % 2 == 1 ? 'qty-tag-orange' : '' }}">{{ $item->jumlah }}x</span>
                                    <span class="order-item-title">{{ $item->nama_produk }}</span>
                                </div>
                                <div>
                                    @if(str_contains(strtolower($pesanan->catatan ?? ''), 'less ice'))
                                        <span class="item-pill-green">Less Ice 50%</span>
                                    @elseif(str_contains(strtolower($pesanan->catatan ?? ''), 'no sugar'))
                                        <span class="item-pill-peach">No Sugar</span>
                                    @elseif(str_contains(strtolower($pesanan->catatan ?? ''), 'normal sweet'))
                                        <span class="item-pill-yellow">Normal Sweet</span>
                                    @else
                                        <span class="item-pill-green">Fresh 100%</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Action Button -->
                    <div class="order-action-row">
                        @if($pesanan->status_pesanan === 'dikonfirmasi' || $pesanan->status_pesanan === 'menunggu_verifikasi')
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}" style="flex: 1; display: flex;">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="diproses">
                                <button type="submit" class="btn-racik">
                                    <span>🍹</span>
                                    <span>Mulai Racik</span>
                                </button>
                            </form>
                        @elseif($pesanan->status_pesanan === 'diproses')
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}" style="flex: 1; display: flex;">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="siap_diambil">
                                <button type="submit" class="btn-racik btn-racik-peach">
                                    <span>📦</span>
                                    <span>Lihat Stok & Racik</span>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}" style="flex: 1; display: flex;">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="selesai">
                                <button type="submit" class="btn-racik" style="background: #166534;" onclick="return confirm('Serahkan pesanan ke pembeli?')">
                                    <span>🤝</span>
                                    <span>Serahkan ke Pembeli</span>
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('panel.staf.pesanan.detail', $pesanan->id) }}" class="btn-more-dots" title="Detail Pesanan">
                            ⋮
                        </a>
                    </div>
                </div>
            @empty
                <div class="order-card" style="text-align: center; color: #94a3b8; padding: 24px;">
                    Tidak ada antrean pesanan aktif saat ini.
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
