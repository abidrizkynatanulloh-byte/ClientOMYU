<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruksi Pembayaran - OMYU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700;800;900&family=Nunito:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}?v={{ filemtime(public_path('css/pelanggan.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/pelanggan-anim.css') }}?v={{ filemtime(public_path('css/pelanggan-anim.css')) }}">
</head>
<body>

    <div class="top-scroll-shield"></div>

    <!-- Floating Capsule Topbar -->
    <div class="topbar-wrapper">
        <header class="fixed-topbar">
            <!-- Logo OMYU -->
            <a href="/" class="brand-container">
                <img src="{{ asset('images/logo_omyu.png') }}" alt="OMYU" class="brand-logo-img">
                <span class="brand-text">MYU</span>
            </a>
            <div style="font-weight:800; font-size:15px; color:#193808;">Selesaikan Pembayaran</div>
            <a href="/" style="text-decoration:none; color:#193808; font-size:13.5px; font-weight:750; display:inline-flex; align-items:center; gap:6px;">
                &larr; Beranda
            </a>
        </header>
    </div>

    <main class="pay-wrapper">
        <div class="pay-card">
            <div class="order-code-badge">KODE: {{ $pesanan->kode_pesanan }}</div>
            <div style="font-size:13.5px; color:#64748b; font-weight:600;">Total Tagihan Pembayaran:</div>
            <div class="pay-total-amount">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</div>

            <div class="instruction-box">
                <h4>Rekening & Petunjuk Transfer:</h4>
                @if($pesanan->metode_bayar === 'transfer')
                    <p><strong>Bank BCA:</strong> 123-456-7890 a/n OMYU JUICE</p>
                    <p><strong>Bank Mandiri:</strong> 987-654-3210 a/n OMYU JUICE</p>
                @elseif($pesanan->metode_bayar === 'qris')
                    <p>Silakan scan QRIS OMYU melalui Dana, GoPay, OVO, ShopeePay, atau BCA Mobile:</p>
                    <div style="text-align: center; margin: 16px 0;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('QRIS-OMYU-' . $pesanan->kode_pesanan) }}" alt="QRIS OMYU" style="border: 2px solid #cbd5e1; border-radius: 14px; padding: 6px; background:#fff;">
                    </div>
                @else
                    <p>Pembayaran Tunai di Lokasi Booth saat pesanan siap diambil.</p>
                @endif
            </div>

            @if($errors->any())
                <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 12px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:14px;text-align:left;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('pelanggan.pembayaran.konfirmasi', $pesanan->kode_pesanan) }}" enctype="multipart/form-data">
                @csrf
                <div style="text-align: left; margin-bottom: 20px;">
                    <label style="display: block; font-weight: 750; font-size: 13px; margin-bottom: 8px; color: #193808;">Upload Bukti Transfer <span style="color:#dc2626;">*</span></label>
                    <input type="file" name="bukti_transfer" accept="image/*" required style="font-size: 13px; width: 100%;">
                    <div style="font-size:11px;color:#64748b;margin-top:6px;font-weight:600;">Wajib upload bukti pembayaran (gambar, maks. 3MB).</div>
                </div>

                <button type="submit" class="btn-confirm-glow" style="display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                    <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Saya Sudah Bayar (Konfirmasi)
                </button>
            </form>
        </div>
    </main>

    <!-- Floating Bottom Navigation (Mobile Only) -->
    <nav class="mobile-bottom-nav">
        <a href="/" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="mobile-nav-label">Beranda</span>
        </a>
        <a href="{{ route('pelanggan.katalog') }}" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6l-2 14H8L6 6h12z"></path>
                <line x1="4" y1="6" x2="20" y2="6"></line>
                <line x1="10" y1="2" x2="14" y2="6"></line>
            </svg>
            <span class="mobile-nav-label">Menu</span>
        </a>
        <a href="{{ url('/#tentang-kami') }}" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span class="mobile-nav-label">Tentang Kami</span>
        </a>
        <a href="{{ route('pelanggan.lacak') }}" class="mobile-nav-item {{ request()->routeIs('pelanggan.lacak') ? 'active' : '' }}">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                <path d="M8 11h6"></path>
            </svg>
            <span class="mobile-nav-label">Lacak</span>
        </a>
        <a href="https://wa.me/6285807106570?text=Halo%20OMYU,%20saya%20ingin%20tanya%20menu" target="_blank" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
            </svg>
            <span class="mobile-nav-label">Kontak</span>
        </a>
    </nav>

    <script src="{{ asset('js/pelanggan-anim.js') }}?v={{ filemtime(public_path('js/pelanggan-anim.js')) }}"></script>
</body>
</html>
