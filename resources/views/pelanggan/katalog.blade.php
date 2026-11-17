<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Jus Buah Segar - OMYU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background: #f8fafc; color: #1e293b; }

        .app-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 4px 30px rgba(0,0,0,0.05);
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Banner */
        .top-banner {
            background: #bde597;
            padding: 24px 28px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        @media (min-width: 768px) {
            .top-banner { padding: 36px 48px 50px; }
        }
        .top-banner-text h2 { font-size: 22px; font-weight: 900; color: #143507; margin-bottom: 6px; }
        @media (min-width: 768px) { .top-banner-text h2 { font-size: 32px; } }
        .top-banner-text p { font-size: 13px; font-weight: 700; color: #2d5a16; display: flex; align-items: center; gap: 4px; }
        .banner-circle {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background: #4ade80;
            overflow: hidden;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        }
        @media (min-width: 768px) { .banner-circle { width: 120px; height: 120px; border-width: 6px; } }
        .banner-circle img { width: 100%; height: 100%; object-fit: cover; }

        /* Floating Capsule Header */
        .search-capsule {
            background: #ffffff;
            border-radius: 30px;
            margin: -24px 24px 16px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
            z-index: 10;
            border: 1px solid #f1f5f9;
        }
        @media (min-width: 768px) {
            .search-capsule { margin: -28px 48px 24px; padding: 12px 28px; }
        }
        .capsule-logo { height: 32px; object-fit: contain; }
        .capsule-search { flex: 1; margin: 0 16px; }
        .capsule-search input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 14px;
            background: transparent;
            color: #334155;
        }
        .capsule-actions { display: flex; align-items: center; gap: 16px; }
        .capsule-btn { text-decoration: none; color: #1e293b; font-size: 20px; position: relative; }
        .cart-count {
            position: absolute;
            top: -6px;
            right: -8px;
            background: #22c55e;
            color: white;
            font-size: 10px;
            font-weight: 800;
            border-radius: 50%;
            width: 17px;
            height: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Category Tabs */
        .category-scroll {
            display: flex;
            gap: 10px;
            padding: 4px 24px 16px;
            overflow-x: auto;
            scrollbar-width: none;
        }
        @media (min-width: 768px) {
            .category-scroll { padding: 4px 48px 20px; flex-wrap: wrap; }
        }
        .category-scroll::-webkit-scrollbar { display: none; }
        .cat-pill {
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            color: #64748b;
            background: #f1f5f9;
            transition: 0.15s;
        }
        .cat-pill.active {
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }

        /* Product Grid (Responsive: 2 cols on mobile, 4 cols on desktop) */
        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            padding: 4px 20px 24px;
        }
        @media (min-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
                gap: 20px;
                padding: 4px 48px 36px;
            }
        }
        .product-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: 0.2s;
        }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
        .product-img-box {
            width: 100%;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            overflow: hidden;
            border-radius: 10px;
        }
        @media (min-width: 768px) { .product-img-box { height: 140px; } }
        .product-img-box img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: 0.2s;
        }
        .product-card:hover .product-img-box img { transform: scale(1.05); }
        .product-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .product-desc {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.4;
            margin-bottom: 12px;
            height: 32px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }
        .product-price {
            font-size: 14px;
            font-weight: 900;
            color: #0f172a;
        }
        .btn-add-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #15803d;
            color: #ffffff;
            border: none;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(21, 128, 61, 0.4);
            transition: 0.15s;
        }
        .btn-add-circle:hover { background: #166534; transform: scale(1.1); }

        /* Jaminan Kesegaran Banner */
        .jaminan-card {
            background: #eef7e6;
            border-top: 1px solid #d9edd0;
            border-bottom: 1px solid #d9edd0;
            margin: 10px 0 30px;
            padding: 18px 24px;
            color: #143507;
        }
        @media (min-width: 768px) { .jaminan-card { padding: 22px 48px; } }
        .jaminan-title { font-size: 13px; font-weight: 900; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
        .jaminan-desc { font-size: 12px; line-height: 1.5; color: #2d5a16; }

        .alert-sukses {
            background: #dcfce7;
            color: #15803d;
            font-size: 13px;
            font-weight: 700;
            padding: 12px 20px;
            border-radius: 10px;
            margin: 0 24px 16px;
            border: 1px solid #bbf7d0;
        }
        @media (min-width: 768px) { .alert-sukses { margin: 0 48px 20px; } }

        /* Desktop Nav Header Menu (Visible on Desktop) */
        .desktop-header-links { display: none; }
        @media (min-width: 768px) {
            .desktop-header-links {
                display: flex;
                align-items: center;
                gap: 16px;
                font-size: 14px;
                font-weight: 700;
            }
            .desktop-header-links a { text-decoration: none; color: #475569; padding: 6px 14px; border-radius: 20px; }
            .desktop-header-links a:hover { color: #166534; background: #f1f5f9; }
        }

        /* Bottom Fixed Navigation Bar (Mobile Only) */
        .bottom-nav { display: none; }
        @media (max-width: 768px) {
            body { padding-bottom: 75px; }
            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: #ffffff;
                border-top: 1px solid #e2e8f0;
                justify-content: space-around;
                padding: 8px 0 10px;
                z-index: 100;
            }
            .bottom-nav a {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                color: #64748b;
                font-size: 11px;
                font-weight: 700;
                gap: 3px;
                position: relative;
            }
            .bottom-nav a.active { color: #15803d; }
            .bottom-nav a .nav-icon { font-size: 18px; }
            .bottom-badge { position: absolute; top: -2px; right: 8px; background: #ef4444; color: #fff; font-size: 9px; font-weight: 800; border-radius: 50%; width: 15px; height: 15px; display: flex; align-items: center; justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Top Banner Header -->
        <div class="top-banner">
            <div class="top-banner-text">
                <h2>Jus Buah Segar</h2>
                <p>✔ 100% Buah Alami Tanpa Gula Tambahan</p>
            </div>
            <div class="banner-circle">
                <img src="https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=300&auto=format&fit=crop&q=80" alt="Jus Segar">
            </div>
        </div>

        <!-- Floating Search & Capsule Bar -->
        <form method="GET" action="{{ route('pelanggan.katalog') }}" class="search-capsule">
            <a href="/">
                <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU" class="capsule-logo">
            </a>

            <div class="desktop-header-links">
                <a href="/">Beranda</a>
                <a href="{{ route('pelanggan.katalog') }}" style="color:#166534; font-weight:bold;">Menu</a>
                <a href="{{ route('pelanggan.lacak') }}">Lacak Pesanan</a>
            </div>

            <div class="capsule-search">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari menu jus segar..." onchange="this.form.submit()">
            </div>
            <div class="capsule-actions">
                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:18px;">🔍</button>
                <a href="{{ route('pelanggan.keranjang') }}" class="capsule-btn" title="Keranjang Belanja">
                    🛒
                    @if(count(session('keranjang', [])) > 0)
                        <span class="cart-count">{{ count(session('keranjang', [])) }}</span>
                    @endif
                </a>
            </div>
        </form>

        @if(session('sukses'))
            <div class="alert-sukses">✅ {{ session('sukses') }}</div>
        @endif

        @if(session('galat'))
            <div class="alert-sukses" style="background:#fee2e2; color:#b91c1c; border-color:#fecaca;">⚠️ {{ session('galat') }}</div>
        @endif

        <!-- Category Scrollable Pills -->
        <div class="category-scroll">
            <a href="{{ route('pelanggan.katalog') }}" class="cat-pill {{ !request('kategori_id') ? 'active' : '' }}">
                Semua
            </a>
            @foreach($kategoriList as $kat)
                <a href="{{ route('pelanggan.katalog', ['kategori_id' => $kat->id]) }}" class="cat-pill {{ request('kategori_id') == $kat->id ? 'active' : '' }}">
                    {{ $kat->nama }}
                </a>
            @endforeach
        </div>

        <!-- Responsive Product Grid -->
        <div class="product-grid">
            @forelse($daftarProduk as $p)
                <div class="product-card">
                    <div class="product-img-box">
                        @php
                            $namaLower = strtolower($p->nama);
                            $imgUrl = 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=300&auto=format&fit=crop&q=80';
                            if (str_contains($namaLower, 'mangga')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1546173159-315724a31696?w=300&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'alpukat')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=300&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'tomat')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=300&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'seledri') || str_contains($namaLower, 'green')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1610970881699-44a5587cabec?w=300&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'kopi') || str_contains($namaLower, 'coffe') || str_contains($namaLower, 'nescafe')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=300&auto=format&fit=crop&q=80';
                            }
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $p->nama }}">
                    </div>

                    <div class="product-title">{{ $p->nama }}</div>
                    <div class="product-desc">{{ $p->deskripsi ?? 'Manis, segar, dan nikmat di setiap tegukan.' }}</div>

                    <div class="product-footer">
                        <span class="product-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>

                        @if($p->stok > 0)
                            <form method="POST" action="{{ route('pelanggan.keranjang.tambah') }}">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="btn-add-circle" title="Tambah ke keranjang">+</button>
                            </form>
                        @else
                            <span style="font-size: 11px; color: #dc2626; font-weight: 700;">Habis</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 60px 20px; text-align: center; color: #94a3b8;">
                    Tidak ada menu jus dalam kategori ini.
                </div>
            @endforelse
        </div>

        <!-- Jaminan Kesegaran Banner -->
        <section class="jaminan-card">
            <div class="jaminan-title">🌿 Jaminan Kesegaran OMYU</div>
            <div class="jaminan-desc">
                Setiap gelas diproses saat pesanan masuk menggunakan teknologi hidrolik agar enzim dan rasa buah asli tetap terjaga maksimal.
            </div>
        </section>

        <!-- Bottom Fixed Navigation Bar (Mobile Only) -->
        <nav class="bottom-nav">
            <a href="/">
                <span class="nav-icon">🏠</span>
                <span>Beranda</span>
            </a>
            <a href="{{ route('pelanggan.katalog') }}" class="active">
                <span class="nav-icon">🍽️</span>
                <span>Menu</span>
            </a>
            <a href="{{ route('pelanggan.keranjang') }}">
                <span class="nav-icon">🛒</span>
                <span>Pesanan</span>
                @if(count(session('keranjang', [])) > 0)
                    <span class="bottom-badge">{{ count(session('keranjang', [])) }}</span>
                @endif
            </a>
            <a href="{{ route('pelanggan.lacak') }}">
                <span class="nav-icon">🔍</span>
                <span>Lacak</span>
            </a>
        </nav>
    </div>

</body>
</html>
