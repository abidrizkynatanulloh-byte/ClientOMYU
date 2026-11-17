@extends('layouts.staf')

@section('title', 'Menu Pesanan Barista & Kasir')

@section('styles')
<style>
    /* Styling Khusus Halaman Pesanan Mobile-First */
    .pesanan-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* 1. Tiga Metrik Atas (Antrean, Verifikasi, Proses) */
    .metrics-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
    }
    .metric-card {
        border-radius: 16px;
        padding: 10px 8px;
        text-align: center;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: 0.15s;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .metric-top-label {
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 2px;
    }
    .metric-num {
        font-size: 26px;
        font-weight: 900;
        line-height: 1.1;
    }
    .metric-sub {
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        margin-top: 2px;
    }

    .m-antrean { background: #e8f7ee; border: 1px solid #bbf7d0; color: #166534; }
    .m-antrean .metric-num { color: #15803d; }
    .m-antrean .metric-sub { color: #166534; }

    .m-verif { background: #fff1ec; border: 1px solid #fed7aa; color: #c2410c; }
    .m-verif .metric-num { color: #b91c1c; }
    .m-verif .metric-sub { color: #9a3412; }

    .m-proses { background: #fefde8; border: 1px solid #fef08a; color: #854d0e; }
    .m-proses .metric-num { color: #854d0e; }
    .m-proses .metric-sub { color: #854d0e; }

    /* 2. Search Bar */
    .search-pill-box {
        position: relative;
        width: 100%;
    }
    .search-pill-input {
        width: 100%;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 25px;
        padding: 10px 16px 10px 38px;
        font-size: 13px;
        font-family: inherit;
        outline: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .search-pill-input:focus {
        border-color: #198754;
    }
    .search-pill-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 14px;
    }

    /* 3. Filter Tabs */
    .filter-tabs-row {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }
    .filter-tabs-row::-webkit-scrollbar { display: none; }
    
    .tab-pill {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        transition: 0.15s;
    }
    .tab-pill.active {
        background: #15803d;
        color: #ffffff;
        border-color: #15803d;
    }

    /* 4. Order Cards */
    .card-pesanan {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 12px;
        position: relative;
    }
    .card-pesanan.border-orange {
        border-left: 6px solid #f97316;
    }
    .card-pesanan.border-green {
        border-left: 6px solid #16a34a;
    }
    .card-pesanan.border-gray {
        border-left: 6px solid #94a3b8;
    }

    .cp-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }
    .cp-code-name {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }
    .cp-code {
        font-weight: 900;
        color: #15803d;
    }
    .cp-buyer {
        font-weight: 800;
        color: #0f172a;
    }
    .cp-buyer.strikethrough {
        text-decoration: line-through;
        color: #64748b;
    }

    .badge-menunggu-cek {
        background: #fef9c3;
        color: #713f12;
        border-radius: 14px;
        padding: 3px 9px;
        font-size: 10px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-di-produksi {
        background: #dcfce7;
        color: #14532d;
        border-radius: 14px;
        padding: 3px 9px;
        font-size: 10px;
        font-weight: 800;
    }
    .badge-sudah-diambil {
        background: #e2e8f0;
        color: #334155;
        border-radius: 14px;
        padding: 3px 9px;
        font-size: 10px;
        font-weight: 800;
    }

    .cp-time-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #475569;
        margin-bottom: 10px;
    }
    .pill-countdown {
        background: #fef9c3;
        color: #854d0e;
        border-radius: 10px;
        padding: 2px 8px;
        font-size: 10px;
        font-weight: 800;
    }

    /* Items Container inside Card */
    .cp-items-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 10px;
    }
    .cp-item-line {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 12px;
        padding: 4px 0;
    }
    .cp-item-line:not(:last-child) {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 6px;
        margin-bottom: 4px;
    }
    .cp-item-title {
        font-weight: 800;
        color: #1e293b;
    }
    .cp-item-sub {
        font-size: 10px;
        color: #16a34a;
        font-weight: 700;
        margin-top: 1px;
    }
    .cp-item-price {
        font-weight: 800;
        color: #334155;
    }

    /* Payment summary line */
    .cp-payment-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        margin-bottom: 10px;
    }
    .badge-method {
        background: #ffedd5;
        color: #9a3412;
        border-radius: 10px;
        padding: 2px 8px;
        font-size: 10px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .cp-total {
        font-size: 16px;
        font-weight: 900;
        color: #0f172a;
    }

    /* Proof of Payment Box */
    .cp-proof-box {
        background: #f1f5f9;
        border-radius: 10px;
        padding: 8px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .cp-proof-left {
        font-size: 11px;
    }
    .cp-proof-file {
        font-weight: 800;
        color: #0f172a;
        text-decoration: underline;
    }
    .cp-proof-meta {
        font-size: 10px;
        color: #64748b;
        margin-top: 2px;
    }
    .btn-view-proof {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
    }

    /* Buttons Row */
    .cp-actions-row {
        display: flex;
        gap: 8px;
    }
    .btn-reject {
        flex: 1;
        background: #fff1ec;
        border: 1px solid #fecaca;
        color: #b91c1c;
        border-radius: 10px;
        padding: 9px;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        cursor: pointer;
    }
    .btn-confirm {
        flex: 1.3;
        background: #15803d;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 9px;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        cursor: pointer;
        transition: 0.15s;
    }
    .btn-confirm:hover { background: #166534; }

    .btn-wa-notif {
        width: 100%;
        background: #fff1ec;
        border: 1px solid #fed7aa;
        color: #9a3412;
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        transition: 0.15s;
    }
    .btn-wa-notif:hover { background: #ffedd5; }

    .btn-struk-fisik {
        background: #dcfce7;
        color: #166534;
        border-radius: 12px;
        padding: 3px 8px;
        font-size: 10px;
        font-weight: 800;
        text-decoration: none;
    }

    /* Modal Bukti Transfer */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #ffffff;
        border-radius: 18px;
        max-width: 360px;
        width: 100%;
        padding: 18px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    /* Desktop View Adaptations */
    @media (min-width: 768px) {
        .pesanan-page-wrapper {
            max-width: 850px;
            margin: 0 auto;
        }
    }
</style>
@endsection

@section('content')
<div class="pesanan-page-wrapper">

    <!-- 1. Tiga Metrik Atas (Antrean, Verifikasi, Proses) -->
    <div class="metrics-row">
        <!-- Antrean -->
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'semua']) }}" class="metric-card m-antrean">
            <div class="metric-top-label">
                <span>🖥️</span>
                <span>Antrean</span>
            </div>
            <div class="metric-num">{{ $antreanOrderMasuk }}</div>
            <div class="metric-sub">Order Masuk</div>
        </a>

        <!-- Verifikasi -->
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'menunggu_cek']) }}" class="metric-card m-verif">
            <div class="metric-top-label">
                <span>!</span>
                <span>Verifikasi</span>
            </div>
            <div class="metric-num">{{ $perluVerifikasiCount }}</div>
            <div class="metric-sub">PERLU CEK</div>
        </a>

        <!-- Proses -->
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'diproses']) }}" class="metric-card m-proses">
            <div class="metric-top-label">
                <span>🍹</span>
                <span>Proses</span>
            </div>
            <div class="metric-num">{{ $prosesDiBlenderCount }}</div>
            <div class="metric-sub">Di Blender</div>
        </a>
    </div>

    <!-- 2. Search Bar Pill Input -->
    <form method="GET" action="{{ route('panel.staf.pesanan.index') }}" class="search-pill-box">
        @if(request('tab'))
            <input type="hidden" name="tab" value="{{ request('tab') }}">
        @endif
        <span class="search-pill-icon">🔍</span>
        <input type="text" name="cari" value="{{ request('cari') }}" class="search-pill-input" placeholder="Cari nomor pesanan #OMY atau nama....">
    </form>

    <!-- 3. Horizontal Filter Tabs -->
    <div class="filter-tabs-row">
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'semua']) }}" class="tab-pill {{ !request('tab') || request('tab') === 'semua' ? 'active' : '' }}">
            Semua ({{ $semuaCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'menunggu_cek']) }}" class="tab-pill {{ request('tab') === 'menunggu_cek' ? 'active' : '' }}">
            Menunggu Cek ({{ $menungguCekCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'sudah_bayar']) }}" class="tab-pill {{ request('tab') === 'sudah_bayar' ? 'active' : '' }}">
            Sudah Bayar ({{ $sudahBayarCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'diproses']) }}" class="tab-pill {{ request('tab') === 'diproses' ? 'active' : '' }}">
            Di Blender ({{ $prosesDiBlenderCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'siap']) }}" class="tab-pill {{ request('tab') === 'siap' ? 'active' : '' }}">
            Siap Ambil ({{ $siapAmbilCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'selesai']) }}" class="tab-pill {{ request('tab') === 'selesai' ? 'active' : '' }}">
            Selesai ({{ $selesaiCount }})
        </a>
    </div>

    <!-- 4. Daftar Pesanan -->
    <div class="orders-list">
        @forelse($daftarPesanan as $pesanan)
            @php
                $isMenungguCek = in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi']) || $pesanan->status_pembayaran === 'menunggu';
                $isDiproses = $pesanan->status_pesanan === 'diproses';
                $isSelesai = $pesanan->status_pesanan === 'selesai';
                $pembayaran = $pesanan->pembayaran->first();
                $rawBayar = $pembayaran ? $pembayaran->data_mentah : [];
                $namaFile = $rawBayar['nama_file'] ?? 'Bukti_Transfer_' . Str::slug($pesanan->nama_pembeli) . '.jpg';
                $idRef = $rawBayar['id_ref'] ?? 'ID ' . substr($pesanan->id * 187 + 2341, -4);
                $jamBukti = $rawBayar['jam'] ?? $pesanan->created_at->format('H:i') . ' WIB';
                $borderClass = $isMenungguCek ? 'border-orange' : ($isDiproses ? 'border-green' : 'border-gray');
            @endphp

            <div class="card-pesanan {{ $borderClass }}">
                <!-- Card Top: Code, Name, Status Badge -->
                <div class="cp-top">
                    <div class="cp-code-name">
                        <span class="cp-code">#{{ $pesanan->kode_pesanan }}</span>
                        <span>•</span>
                        <span class="cp-buyer {{ $isSelesai ? 'strikethrough' : '' }}">{{ $pesanan->nama_pembeli }}</span>
                    </div>

                    <div>
                        @if($isMenungguCek)
                            <span class="badge-menunggu-cek">
                                <span style="font-size: 8px;">●</span> Menunggu Cek
                            </span>
                        @elseif($isDiproses)
                            <span class="badge-di-produksi">Di Produksi</span>
                        @elseif($isSelesai)
                            <span class="badge-sudah-diambil">Sudah Diambil</span>
                        @else
                            <span class="badge-di-produksi">{{ str_replace('_', ' ', ucfirst($pesanan->status_pesanan)) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Time info & Countdown -->
                <div class="cp-time-row">
                    @if($isSelesai)
                        <span>Selesai diserahkan {{ $pesanan->updated_at->format('H:i') }} WIB</span>
                        <a href="{{ route('panel.staf.kasir.struk', $pesanan->id) }}" class="btn-struk-fisik">Struk Fisik</a>
                    @else
                        <span>🕒 Ambil {{ $pesanan->created_at->addMinutes(30)->format('H:i') }}</span>
                        @if($isMenungguCek)
                            <span class="pill-countdown">{{ max(1, 15 - $pesanan->created_at->diffInMinutes(now())) }} mnt lagi</span>
                        @elseif($isDiproses)
                            <span style="color: #16a34a; font-weight: 700;">(tepat waktu)</span>
                        @endif
                    @endif
                </div>

                <!-- Items Container -->
                <div class="cp-items-box">
                    @foreach($pesanan->itemPesanan as $item)
                        <div class="cp-item-line">
                            <div>
                                <div class="cp-item-title">{{ $item->jumlah }}x {{ $item->nama_produk }}</div>
                                <div class="cp-item-sub">
                                    @if(str_contains(strtolower($pesanan->catatan ?? ''), 'no ice'))
                                        No Ice
                                    @elseif(str_contains(strtolower($pesanan->catatan ?? ''), 'no sugar'))
                                        No Sugar
                                    @elseif(str_contains(strtolower($pesanan->catatan ?? ''), 'less ice'))
                                        Less Ice 50%
                                    @else
                                        Normal Sweet
                                    @endif
                                </div>
                            </div>
                            <div class="cp-item-price">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(!$isSelesai)
                    <!-- Payment Row -->
                    <div class="cp-payment-row">
                        <div>
                            <span style="color: #64748b; font-size: 11px;">Metode:</span>
                            <span class="badge-method">
                                <span>📱</span>
                                <span>{{ $pembayaran ? $pembayaran->metode : 'QRIS BCA' }}</span>
                            </span>
                        </div>
                        <div class="cp-total">
                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                        </div>
                    </div>
                @endif

                <!-- Attachment Proof of Payment (Khusus Menunggu Cek) -->
                @if($isMenungguCek)
                    <div class="cp-proof-box">
                        <div class="cp-proof-left">
                            <a href="javascript:void(0)" onclick="lihatBukti('{{ $pesanan->kode_pesanan }}', '{{ $pesanan->nama_pembeli }}', '{{ $namaFile }}', 'Rp {{ number_format($pesanan->total, 0, ',', '.') }}', '{{ $idRef }}', '{{ $jamBukti }}')" class="cp-proof-file">
                                {{ $namaFile }}
                            </a>
                            <div class="cp-proof-meta">{{ $jamBukti }} &bull; {{ $idRef }}</div>
                        </div>
                        <button type="button" class="btn-view-proof" onclick="lihatBukti('{{ $pesanan->kode_pesanan }}', '{{ $pesanan->nama_pembeli }}', '{{ $namaFile }}', 'Rp {{ number_format($pesanan->total, 0, ',', '.') }}', '{{ $idRef }}', '{{ $jamBukti }}')">
                            <span>👁</span>
                            <span>Lihat</span>
                        </button>
                    </div>

                    <!-- Action Buttons: Tolak & Konfirmasi -->
                    <div class="cp-actions-row">
                        <form method="POST" action="{{ route('panel.staf.pesanan.batal', $pesanan->id) }}" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="alasan_batal" value="Pembayaran / nominal tidak sesuai (Tolak / Ulang oleh kasir)">
                            <button type="submit" class="btn-reject" style="width: 100%;" onclick="return confirm('Tolak atau minta pembeli mengulang transfer untuk pesanan #{{ $pesanan->kode_pesanan }}?')">
                                <span>✕</span>
                                <span>Tolak / Ulang</span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('panel.staf.pesanan.verifikasi-bayar', $pesanan->id) }}" style="flex: 1.3;">
                            @csrf
                            <button type="submit" class="btn-confirm" style="width: 100%;">
                                <span>✓</span>
                                <span>Konfirmasi</span>
                            </button>
                        </form>
                    </div>
                @elseif($isDiproses)
                    <!-- Baris KDS & Tombol WhatsApp -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 11px;">
                        <span style="color: #166534; font-weight: 800; display: flex; align-items: center; gap: 4px;">
                            <span>✓</span> Lunas QRIS Diterima
                        </span>
                        <span style="background: #f1f5f9; color: #475569; font-weight: 700; padding: 2px 8px; border-radius: 8px;">
                            KDS Blender #2
                        </span>
                    </div>

                    @php
                        $nomorWa = preg_replace('/[^0-9]/', '', $pesanan->telepon_pembeli);
                        if (str_starts_with($nomorWa, '0')) {
                            $nomorWa = '62' . substr($nomorWa, 1);
                        }
                        $pesanWa = urlencode("Halo {$pesanan->nama_pembeli}, pesanan jus kamu #{$pesanan->kode_pesanan} sedang diracik segar di outlet OMYU dan segera siap diambil ya! 🍓🥤");
                    @endphp

                    <a href="https://wa.me/{{ $nomorWa }}?text={{ $pesanWa }}" target="_blank" class="btn-wa-notif">
                        <span>💬</span>
                        <span>Kirim Notif WhatsApp ke Pembeli</span>
                    </a>
                @endif
            </div>
        @empty
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 40px; text-align: center; color: #94a3b8;">
                <div style="font-size: 32px; margin-bottom: 8px;">📋</div>
                <div style="font-size: 14px; font-weight: 800; color: #0f172a;">Tidak ada pesanan</div>
                <div style="font-size: 12px; margin-top: 2px;">Semua pesanan pada tab ini sudah selesai atau belum ada order masuk.</div>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 10px;">
        {{ $daftarPesanan->links() }}
    </div>

</div>

<!-- Modal Popup Bukti Transfer -->
<div class="modal-overlay" id="proofModal" onclick="tutupBukti()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 12px;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 800; color: #0f172a;">Bukti Pembayaran QRIS</h3>
            <button type="button" onclick="tutupBukti()" style="background: none; border: none; font-size: 18px; font-weight: bold; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 18px; text-align: center; margin-bottom: 14px;">
            <div style="font-size: 36px; margin-bottom: 6px;">📱</div>
            <div style="font-size: 11px; font-weight: 800; color: #166534; text-transform: uppercase;">QRIS INTERAKTIF DITERIMA</div>
            <div id="modalNominal" style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 4px 0 8px;">Rp 28.000</div>
            
            <div style="font-size: 11px; color: #64748b; text-align: left; background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div>Pesanan: <strong id="modalKode">#OMY-1087</strong></div>
                <div>Pengirim: <strong id="modalPengirim">Siti Rahma</strong></div>
                <div>Waktu   : <span id="modalWaktu">14:58 WIB</span></div>
                <div>Ref ID  : <span id="modalRef">ID 9821</span></div>
                <div>Status  : <span style="color: #166534; font-weight: 800;">BERHASIL DIPROSES</span></div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" style="width: 100%; padding: 10px; font-weight: 800; font-size: 13px; text-align: center;" onclick="tutupBukti()">
            Tutup Preview
        </button>
    </div>
</div>

<script>
    function lihatBukti(kode, pengirim, file, nominal, ref, waktu) {
        document.getElementById('modalKode').innerText = '#' + kode;
        document.getElementById('modalPengirim').innerText = pengirim;
        document.getElementById('modalNominal').innerText = nominal;
        document.getElementById('modalRef').innerText = ref;
        document.getElementById('modalWaktu').innerText = waktu;
        document.getElementById('proofModal').classList.add('active');
    }

    function tutupBukti() {
        document.getElementById('proofModal').classList.remove('active');
    }
</script>
@endsection
