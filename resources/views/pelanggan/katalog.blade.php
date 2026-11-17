<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu Jus Segar - OMYU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700;800;900&family=Nunito:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}?v={{ filemtime(public_path('css/pelanggan.css')) }}">
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
                <a href="{{ route('pelanggan.katalog') }}" class="nav-item active">
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
                <form method="GET" action="{{ route('pelanggan.katalog') }}" class="search-pill">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari menu favorit...">
                    <button type="submit" class="search-icon" aria-label="Cari">
                        <svg class="web-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>

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

    <main class="catalog-wrapper">
        @if(session('sukses'))
            <div class="alert-sukses">
                <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('sukses') }}
            </div>
        @endif

        <div class="catalog-banner">
            <div>
                <h2>Pilihan Menu Jus Segar OMYU</h2>
                <p>100% Buah Murni Alami &bull; Diproses hidrolik segar saat pesanan dibuat</p>
            </div>
            <a href="{{ route('pelanggan.keranjang') }}" class="btn-banner-cart">
                <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Buka Keranjang ({{ count(session('keranjang', [])) }})
            </a>
        </div>

        <!-- Filter Categories -->
        <div class="category-scroll">
            <a href="{{ route('pelanggan.katalog') }}" class="cat-pill {{ !request('kategori_id') ? 'active' : '' }}">
                Semua Menu
            </a>
            @foreach($kategoriList as $kat)
                <a href="{{ route('pelanggan.katalog', ['kategori_id' => $kat->id]) }}" class="cat-pill {{ request('kategori_id') == $kat->id ? 'active' : '' }}">
                    {{ $kat->nama }}
                </a>
            @endforeach
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
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
                                <button type="submit" class="btn-add-cart">+ Keranjang</button>
                            </form>
                        @else
                            <span style="font-size: 12px; color: #dc2626; font-weight: 800;">Stok Habis</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 60px 20px; text-align: center; color: #64748b; background: rgba(255,255,255,0.9); border-radius: 20px;">
                    Tidak ada menu yang sesuai dengan pencarian Anda.
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>
