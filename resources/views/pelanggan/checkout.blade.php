<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pesanan - OMYU</title>
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
                <a href="{{ route('pelanggan.lacak') }}" class="nav-item {{ request()->routeIs('pelanggan.lacak') ? 'active' : '' }}">
                    Lacak Pesanan
                </a>
            </nav>

            <div class="nav-right">
                <a href="{{ route('pelanggan.keranjang') }}" class="icon-circle-btn" title="Keranjang Belanja">
                    <svg class="web-icon" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-red-badge">
                        {{ count(session('keranjang', [])) }}
                    </span>
                </a>
            </div>
        </header>
    </div>

    <main class="checkout-wrapper">
        @if(($modeCheckout ?? 'keranjang') === 'langsung')
            <div class="alert-sukses" style="margin-bottom: 14px;">
                <strong>Mode Pesan Langsung:</strong> pesanan ini hanya berisi item yang kamu pilih. Item di keranjang tetap aman.
            </div>
        @endif
        <form method="POST" action="{{ route('pelanggan.checkout.proses') }}" class="checkout-grid">
            @csrf
            <input type="hidden" name="mode_checkout" value="{{ $modeCheckout ?? 'keranjang' }}">

            <!-- Left: Items, Schedule & Buyer Data -->
            <div>
                <!-- Previews -->
                <div class="section-card">
                    <div class="section-header">
                        <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6l-2 14H8L6 6h12z"></path>
                            <line x1="4" y1="6" x2="20" y2="6"></line>
                            <line x1="10" y1="2" x2="14" y2="6"></line>
                        </svg>
                        Ringkasan Menu Dipesan
                    </div>
                    @foreach($keranjang as $item)
                        @php
                            $namaLower = strtolower($item['nama_produk']);
                            $imgUrl = 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=150&auto=format&fit=crop&q=80';
                            if (str_contains($namaLower, 'kentang')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=150&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'mangga')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1546173159-315724a31696?w=150&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'alpukat')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=150&auto=format&fit=crop&q=80';
                            }
                        @endphp
                        <div class="item-preview-row">
                            <div class="item-thumb-sm">
                                <img src="{{ $imgUrl }}" alt="{{ $item['nama_produk'] }}">
                            </div>
                            <div class="item-preview-info">
                                <div class="item-preview-name">{{ $item['nama_produk'] }} (x{{ $item['qty'] }})</div>
                                <div class="item-preview-sub">{{ $item['catatan'] ?: 'Tanpa catatan khusus' }}</div>
                            </div>
                            <div class="item-preview-price">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Schedule -->
                <div class="section-card">
                    <div class="section-header">
                        <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Jadwal Pengambilan di Outlet
                    </div>
                    <div class="schedule-box">
                        <div class="schedule-date">
                            <input type="date" name="tanggal_ambil" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                            <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="schedule-time">
                            <select name="jam_ambil" required>
                                <option value="15.30-16.00 WIB">15.30 - 16.00 WIB</option>
                                <option value="10.00-11.00 WIB">10.00 - 11.00 WIB</option>
                                <option value="11.00-12.00 WIB">11.00 - 12.00 WIB</option>
                                <option value="13.00-14.00 WIB">13.00 - 14.00 WIB</option>
                                <option value="14.00-15.00 WIB">14.00 - 15.00 WIB</option>
                                <option value="16.00-17.00 WIB">16.00 - 17.00 WIB</option>
                                <option value="17.00-18.00 WIB">17.00 - 18.00 WIB</option>
                                <option value="18.30-19.30 WIB">18.30 - 19.30 WIB</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Buyer Details -->
                <div class="section-card">
                    <div class="section-header">
                        <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        Data Pemesan & Catatan
                    </div>
                    @if($pelanggan)
                        <div style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:10px 12px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:14px;">
                            Login sebagai {{ $pelanggan->nama }} ({{ $pelanggan->telepon }}). Pesanan akan tersimpan ke akunmu.
                        </div>
                        <div class="input-pill">
                            <label>NAMA ANDA</label>
                            <input type="text" value="{{ $pelanggan->nama }}" readonly style="background:#f1f5f9;cursor:not-allowed;">
                        </div>
                        <div class="input-pill">
                            <label>NOMOR WHATSAPP</label>
                            <input type="text" value="{{ $pelanggan->telepon }}" readonly style="background:#f1f5f9;cursor:not-allowed;">
                            <div style="font-size:11px;color:#64748b;font-weight:700;margin-top:6px;">Nomor HP mengikuti akun login.</div>
                        </div>
                    @else
                        <div style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:10px 12px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:14px;">
                            Bisa pesan tanpa login. Pesanan akan tersimpan di perangkat ini. <a href="{{ route('pelanggan.login') }}" style="color:#166534;text-decoration:underline;">Login</a> jika ingin riwayat tersimpan ke akun.
                        </div>
                        <div class="input-pill">
                            <label>NAMA ANDA</label>
                            <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli') }}" placeholder="Masukkan nama pemesan..." required>
                        </div>
                        <div class="input-pill">
                            <label>NOMOR WHATSAPP</label>
                            <input type="text" name="telepon_pembeli" value="{{ old('telepon_pembeli') }}" placeholder="Contoh: 085888333445" required inputmode="numeric">
                        </div>
                    @endif
                    <div class="input-pill">
                        <label>CATATAN (OPSIONAL)</label>
                        <textarea name="catatan" rows="2" placeholder="Contoh: Es sedikit, jangan terlalu manis..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Right: Payment & Summary -->
            <div>
                <div class="sidebar-box">
                    <div class="section-header" style="margin-bottom:14px;">
                        <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        Metode Pembayaran
                    </div>

                    @if($errors->any())
                        <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 12px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:12px;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- QRIS -->
                    <label class="payment-option" style="cursor: pointer; display:block;">
                        <input type="radio" name="metode_bayar" value="qris" required style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">
                        <div class="payment-card">
                            <div class="payment-left">
                                <div class="custom-radio"><div class="radio-dot"></div></div>
                                <div class="payment-info">
                                    <div class="name">QRIS Instan</div>
                                    <div class="sub">Dana, GoPay, OVO, ShopeePay</div>
                                </div>
                            </div>
                            <div style="color: #475569;">
                                <svg class="web-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Transfer Bank -->
                    <label class="payment-option" style="cursor: pointer; display:block;">
                        <input type="radio" name="metode_bayar" value="transfer" required style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">
                        <div class="payment-card">
                            <div class="payment-left">
                                <div class="custom-radio"><div class="radio-dot"></div></div>
                                <div class="payment-info">
                                    <div class="name">Transfer Bank</div>
                                    <div class="sub">BCA / Mandiri</div>
                                </div>
                            </div>
                            <div style="color: #475569;">
                                <svg class="web-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Summary -->
                    <div style="margin-top: 24px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                        <div class="summary-row">
                            <span>Total Item:</span>
                            <span>{{ count($keranjang) }} macam</span>
                        </div>
                        @foreach($keranjang as $item)
                            <div class="summary-row">
                                <span>{{ $item['nama_produk'] }} (x{{ $item['qty'] }})</span>
                                <span>Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <div class="summary-total">
                            <span>TOTAL BAYAR:</span>
                            <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn-bayar-glow">
                            BUAT PESANAN & BAYAR SEKARANG &rarr;
                        </button>
                    </div>
                </div>
            </div>
        </form>
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
