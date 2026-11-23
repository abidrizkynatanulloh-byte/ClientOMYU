<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian & Lacak Pesanan - OMYU</title>
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

            <nav class="nav-center">
                <a href="/" class="nav-item">
                    Beranda
                </a>
                <a href="{{ route('pelanggan.katalog') }}" class="nav-item">
                    Menu
                </a>
                <a href="{{ url('/#tentang-kami') }}" class="nav-item">
                    Tentang Kami
                </a>
                <a href="{{ url('/#kontak') }}" class="nav-item">
                    Kontak
                </a>
            </nav>

            <div class="nav-right">
                <a href="{{ route('pelanggan.katalog') }}" style="color:#193808; text-decoration:none; font-size:13.5px; font-weight:750; display:inline-flex; align-items:center; gap:6px;">
                    <svg class="web-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Pesan Lagi
                </a>
            </div>
        </header>
    </div>

    <main class="ticket-wrapper">
        <div class="search-card">
            <form method="GET" action="{{ route('pelanggan.lacak') }}">
                <input type="text" name="kode" value="{{ request('kode') }}" placeholder="Masukkan Kode Pesanan (Contoh: OMYU-ORD-...)" required>
                <button type="submit" class="btn-lacak">Lacak Status</button>
            </form>
        </div>

        @if($pesanan)
            <div class="ticket-main-box">
                <div class="status-pill-big" style="display:inline-flex; align-items:center; gap:8px;">
                    @if($pesanan->status_pesanan === 'selesai')
                        <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        PESANAN SELESAI
                    @elseif($pesanan->status_pesanan === 'siap_diambil')
                        <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6l-2 14H8L6 6h12z"></path>
                            <line x1="4" y1="6" x2="20" y2="6"></line>
                            <line x1="10" y1="2" x2="14" y2="6"></line>
                        </svg>
                        SIAP DIAMBIL DI OUTLET
                    @elseif($pesanan->status_pesanan === 'dibatalkan')
                        <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        PESANAN DIBATALKAN
                    @else
                        <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        YUMMY! PESANAN SEDANG DIPROSES
                    @endif
                </div>

                <div style="font-size:13px; font-weight:800; color:#64748b; letter-spacing:1px;">KODE ANTRIAN RESMI</div>
                <h1 style="font-size:28px; font-weight:950; color:#193808; margin:6px 0 16px;">#{{ $pesanan->kode_pesanan }}</h1>

                <div class="qr-display-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($pesanan->kode_pesanan) }}" alt="QR Code Pesanan">
                </div>

                <p style="font-size:14px; color:#475569; margin-bottom:20px;">
                    Tunjukkan kode QR di atas ke barista saat tiba di booth OMYU.<br>
                    <strong>Estimasi Siap / Jadwal:</strong> {{ $pesanan->catatan ?: 'Segera dalam 15-20 menit' }}
                </p>

                <div>
                    <button type="button" onclick="window.print()" class="btn-print" style="display:inline-flex; align-items:center; gap:8px;">
                        <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Cetak / Unduh Tiket
                    </button>
                </div>
            </div>
        @elseif(request('kode'))
            <div style="background: rgba(254, 226, 226, 0.95); color: #b91c1c; padding: 18px 24px; border-radius: 20px; text-align: center; font-weight: 700; border: 1px solid #fecaca;">
                Pesanan dengan kode <strong>{{ request('kode') }}</strong> tidak ditemukan. Silakan periksa kembali kodenya.
            </div>
        @endif
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
