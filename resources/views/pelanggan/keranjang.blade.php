<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - OMYU</title>
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
                    Tambah Menu
                </a>
                <a href="{{ route('pelanggan.keranjang') }}" class="icon-circle-btn" title="Keranjang Belanja">
                    <svg class="web-icon" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-red-badge">{{ count(session('keranjang', [])) }}</span>
                </a>
            </div>
        </header>
    </div>

    <main class="cart-wrapper">
        @if(empty($keranjang))
            <div class="empty-box">
                <div style="margin-bottom: 16px; color: #94a3b8;">
                    <svg viewBox="0 0 24 24" width="56" height="56" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <h3 style="font-size: 20px; font-weight: 850; color: #193808; margin-bottom: 8px;">Keranjang Belanja Anda Kosong</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">Silakan pilih aneka varian jus buah murni segar dari katalog OMYU!</p>
                <a href="{{ route('pelanggan.katalog') }}" class="btn-checkout-glow" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; width:auto; padding:12px 32px;">
                    <svg class="web-icon" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6l-2 14H8L6 6h12z"></path>
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="10" y1="2" x2="14" y2="6"></line>
                    </svg>
                    Buka Menu Sekarang
                </a>
            </div>
        @else
            <div class="cart-layout">
                <!-- Left: Items -->
                <div class="items-box">
                    <h2 style="font-size: 18px; font-weight: 900; color: #193808; margin-bottom: 8px;">
                        Menu yang Anda Pilih ({{ count($keranjang) }})
                    </h2>

                    @foreach($keranjang as $key => $item)
                        @php
                            $namaLower = strtolower($item['nama_produk']);
                            $imgUrl = 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=200&auto=format&fit=crop&q=80';
                            if (str_contains($namaLower, 'kentang')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=200&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'mangga')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1546173159-315724a31696?w=200&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'alpukat')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=200&auto=format&fit=crop&q=80';
                            }
                        @endphp

                        <div class="cart-item-row">
                            <div class="cart-item-img">
                                <img src="{{ $imgUrl }}" alt="{{ $item['nama_produk'] }}">
                            </div>
                            <div class="cart-item-info">
                                <div class="cart-item-title">{{ $item['nama_produk'] }}</div>
                                <div class="cart-item-sub">{{ $item['catatan'] ?: 'Fresh jus alami pilihan.' }}</div>
                                <div class="cart-item-price">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
                            </div>
                            
                            <!-- Stepper Control -->
                            <div class="stepper">
                                <form method="POST" action="{{ route('pelanggan.keranjang.update') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <input type="hidden" name="aksi" value="kurang">
                                    <button type="submit" class="step-btn step-minus">-</button>
                                </form>

                                <span class="step-val">{{ $item['qty'] }}</span>

                                <form method="POST" action="{{ route('pelanggan.keranjang.update') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <input type="hidden" name="aksi" value="tambah">
                                    <button type="submit" class="step-btn step-plus">+</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right: Summary Sidebar -->
                <div class="summary-card">
                    <div class="summary-header">Rincian Pembayaran</div>
                    <div class="summary-line">
                        <span>Jumlah Variasi Menu:</span>
                        <span>{{ count($keranjang) }} item</span>
                    </div>
                    @foreach($keranjang as $item)
                        <div class="summary-line">
                            <span>{{ $item['nama_produk'] }} (x{{ $item['qty'] }})</span>
                            <span>Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    <div class="summary-total">
                        <span>TOTAL BAYAR:</span>
                        <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('pelanggan.checkout') }}" class="btn-checkout-glow">
                        Lanjut ke Jadwal & Checkout &rarr;
                    </a>
                </div>
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
