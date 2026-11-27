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
                <a href="{{ route('pelanggan.katalog') }}" class="nav-item active">
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
                <form method="GET" action="{{ route('pelanggan.katalog') }}" class="search-pill">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari menu favorit...">
                    <button type="submit" class="search-icon" aria-label="Cari">
                        <svg class="web-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>

                @if(Auth::guard('pelanggan')->check())
                    <form method="POST" action="{{ route('pelanggan.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="icon-circle-btn" title="Keluar" style="cursor:pointer;">↪</button>
                    </form>
                @else
                    <a href="{{ route('pelanggan.login') }}" class="icon-circle-btn" title="Login untuk pesan" style="text-decoration:none;font-size:11px;font-weight:800;color:#183d12;">Login</a>
                @endif

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
        @if(session('galat'))
            <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:14px;font-size:13px;font-weight:700;margin-bottom:14px;">
                {{ session('galat') }}
            </div>
        @endif
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
                </svg>({{ count(session('keranjang', [])) }})
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
                @php
                    $imgUrl = $p->gambarUrl(600);
                    $firstVarian = $p->varian->first();
                    $initialPrice = $firstVarian && $firstVarian->harga > 0 ? $firstVarian->harga : $p->harga;

                    // Variasi potongan harga promo yang dinamis dan proporsional per produk
                    $pilihanPotongan = [2000, 3000, 4000, 5000];
                    $potongan = $pilihanPotongan[$p->id % count($pilihanPotongan)];
                    if ($initialPrice <= 12000 && $potongan > 3000) {
                        $potongan = 2000;
                    }
                    $strikethroughPrice = $initialPrice + $potongan;
                    $hematRibuan = round($potongan / 1000);

                    // Rating asli dari ulasan pelanggan. Menu baru mulai dari 0 bintang.
                    $ratingScore = $p->bintangRataRata();
                    $jumlahUlasan = $p->jumlahUlasan();
                    $bintangPenuh = (int) round($ratingScore);
                    $bintangTampil = str_repeat('★', $bintangPenuh).str_repeat('☆', 5 - $bintangPenuh);

                    $detailProduk = [
                        'id' => $p->id,
                        'nama' => $p->nama,
                        'deskripsi' => $p->deskripsi ?? 'Manis, segar, dan nikmat di setiap tegukan.',
                        'harga' => $p->harga,
                        'harga_format' => 'Rp ' . number_format($p->harga, 0, ',', '.'),
                        'stok' => $p->stok,
                        'kategori' => $p->kategori?->nama ?? 'Menu OMYU',
                        'img' => $imgUrl,
                        'rating' => $ratingScore,
                        'jumlah_ulasan' => $jumlahUlasan,
                        'bintang' => $bintangTampil,
                        'varians' => $p->varian->map(fn ($varian): array => [
                            'id' => $varian->id,
                            'nama' => $varian->nama,
                            'harga_format' => 'Rp '.number_format($varian->harga > 0 ? $varian->harga : $p->harga, 0, ',', '.'),
                        ])->values()->all(),
                    ];
                @endphp
                <div class="product-card product-open-detail"
                     role="button"
                     tabindex="0"
                     data-potongan="{{ $potongan }}"
                     data-produk='@json($detailProduk)'>

                    <!-- Product Hero Image Box -->
                    <div class="product-img-box">
                        <span class="product-card-badge">{{ $p->kategori?->nama ?? '100% Buah Murni' }}</span>
                        <img src="{{ $imgUrl }}" alt="{{ $p->nama }}" loading="lazy">
                        @if($p->stok > 0)
                            <div class="product-img-overlay-btn" onclick="event.stopPropagation();">
                                <button type="button" class="btn-add-cart-enhanced btn-img-add-cart" onclick="window.tambahKeranjangAnimasi(this, event)">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>Tambah ke Keranjang</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Product Title -->
                    <div class="product-title">{{ $p->nama }}</div>

                    <!-- Rating dari rata-rata ulasan pelanggan -->
                    <div class="product-rating-row">
                        <span class="product-stars">{{ $bintangTampil }}</span>
                        <span class="product-rating-score">{{ number_format($ratingScore, 1, ',', '.') }}</span>
                        <span class="product-rating-reviews">({{ $jumlahUlasan }} ulasan)</span>
                    </div>

                    <!-- Price & Discount Tag -->
                    <div class="product-price-row">
                        <span class="product-price-main">Rp {{ number_format($initialPrice, 0, ',', '.') }}</span>
                        <span class="product-price-strike">Rp {{ number_format($strikethroughPrice, 0, ',', '.') }}</span>
                        <span class="product-discount-tag">Hemat {{ $hematRibuan }}rb</span>
                    </div>

                    <!-- Variant Chips (Interactive Pills) -->
                    @if($p->varian->isNotEmpty())
                        <div class="variant-chips-container" onclick="event.stopPropagation();">
                            @foreach($p->varian as $varian)
                                @php
                                    $varPrice = $varian->harga > 0 ? $varian->harga : $p->harga;
                                @endphp
                                <button type="button"
                                        class="variant-chip {{ $loop->first ? 'active' : '' }}"
                                        data-id="{{ $varian->id }}"
                                        data-harga="{{ $varPrice }}"
                                        data-harga-format="Rp {{ number_format($varPrice, 0, ',', '.') }}"
                                        onclick="window.pilihVarianKartu(this, event)">
                                    {{ $varian->nama }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Card Action Area -->
                    <div class="card-action-container" onclick="event.stopPropagation();">
                        @if($p->stok > 0)
                            <form method="POST" action="{{ route('pelanggan.keranjang.tambah') }}" class="card-cart-form" onsubmit="event.preventDefault();">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                <input type="hidden" name="varian_id" class="input-varian-id" value="{{ $firstVarian?->id ?? '' }}">
                                <input type="hidden" name="langsung" class="input-langsung" value="0">
                                <input type="hidden" name="qty" class="input-qty" value="1">

                                <div class="card-qty-row">
                                    <span class="card-stok-text">Stok: <strong>{{ $p->stok }} cup</strong></span>
                                    <div class="card-qty-pill">
                                        <button type="button" class="card-qty-btn" onclick="window.ubahQtyKartu(this, -1, {{ $p->stok }}, event)">−</button>
                                        <input type="text" class="card-qty-input" value="1" readonly>
                                        <button type="button" class="card-qty-btn" onclick="window.ubahQtyKartu(this, 1, {{ $p->stok }}, event)">+</button>
                                    </div>
                                </div>

                                <button type="submit" class="btn-order-direct" onclick="this.form.querySelector('.input-langsung').value='1'; this.form.submit();">
                                    <span>Pesan Langsung</span>
                                    <span>&rarr;</span>
                                </button>
                            </form>
                        @else
                            <div style="text-align: center; padding: 10px; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 999px; color: #dc2626; font-size: 12px; font-weight: 800;">
                                Stok Habis
                            </div>
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

    <!-- Floating Bottom Navigation (Mobile Only) -->
    <nav class="mobile-bottom-nav">
        <a href="/" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="mobile-nav-label">Beranda</span>
        </a>
        <a href="{{ route('pelanggan.katalog') }}" class="mobile-nav-item active">
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
        <a href="{{ url('/#kontak') }}" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
            </svg>
            <span class="mobile-nav-label">Kontak</span>
        </a>
    </nav>

    <!-- Product Detail Modal -->
    <div id="productModal" class="product-modal-overlay" aria-hidden="true">
        <div class="product-modal" role="dialog" aria-modal="true" aria-labelledby="modalNamaProduk">
            <button type="button" class="product-modal-close" id="closeProductModal" aria-label="Tutup">&times;</button>
            <div class="product-modal-body">
                <div class="product-modal-image">
                    <img id="modalImgProduk" src="" alt="Detail produk">
                </div>
                <div class="product-modal-content">
                    <span class="product-modal-badge" id="modalKategoriProduk">Menu OMYU</span>
                    <h3 class="product-modal-title" id="modalNamaProduk"></h3>
                    <p class="product-modal-desc" id="modalDeskripsiProduk"></p>
                    <div class="product-modal-rating" id="modalRatingProduk">☆☆☆☆☆ 0,0 (0 ulasan)</div>
                    <div class="product-modal-price" id="modalHargaProduk"></div>
                    <div class="product-modal-meta">
                        <div class="product-modal-meta-item"><strong>Stok tersedia:</strong> <span id="modalStokProduk"></span></div>
                        <div class="product-modal-meta-item"><strong>Catatan:</strong> Dibuat segar saat pesanan diproses.</div>
                    </div>
                    <form method="POST" action="{{ route('pelanggan.keranjang.tambah') }}" class="product-modal-form">
                        @csrf
                        <input type="hidden" name="produk_id" id="modalProdukId">
                        <label class="qty-control">
                            <span>Varian</span>
                            <select name="varian_id" id="modalVarianId" aria-label="Varian produk"></select>
                        </label>
                        <label class="qty-control">
                            <span>Jumlah</span>
                            <input type="number" name="qty" id="modalQty" value="1" min="1" max="1" inputmode="numeric">
                        </label>
                        <div class="product-modal-actions">
                            <button type="button" class="btn-add-cart btn-add-cart-enhanced" onclick="window.tambahKeranjangAnimasi(this, event)">+ Keranjang</button>
                            <button type="submit" name="langsung" value="1" class="btn-order-now">Pesan Langsung</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('productModal');
            if (!modal) return;
            const closeBtn = document.getElementById('closeProductModal');
            const img = document.getElementById('modalImgProduk');
            const kategori = document.getElementById('modalKategoriProduk');
            const nama = document.getElementById('modalNamaProduk');
            const desk = document.getElementById('modalDeskripsiProduk');
            const rating = document.getElementById('modalRatingProduk');
            const harga = document.getElementById('modalHargaProduk');
            const stok = document.getElementById('modalStokProduk');
            const idProduk = document.getElementById('modalProdukId');
            const varianSelect = document.getElementById('modalVarianId');
            const qty = document.getElementById('modalQty');

            function isiVarian(data) {
                varianSelect.innerHTML = '';

                const daftarVarian = data.varians || [];
                if (daftarVarian.length === 0) {
                    const opsi = document.createElement('option');
                    opsi.value = '';
                    opsi.textContent = 'Reguler';
                    varianSelect.appendChild(opsi);
                    return;
                }

                daftarVarian.forEach(function (varian) {
                    const opsi = document.createElement('option');
                    opsi.value = varian.id;
                    opsi.textContent = varian.nama + ' — ' + (varian.harga_format || '');
                    opsi.dataset.hargaFormat = varian.harga_format || '';
                    varianSelect.appendChild(opsi);
                });
            }

            varianSelect.addEventListener('change', function () {
                const opsi = varianSelect.selectedOptions[0];
                if (opsi?.dataset.hargaFormat) {
                    harga.textContent = opsi.dataset.hargaFormat;
                }
            });

            function openModal(data) {
                img.src = data.img || '';
                img.alt = data.nama || 'Produk OMYU';
                kategori.textContent = data.kategori || 'Menu OMYU';
                nama.textContent = data.nama || '';
                desk.textContent = data.deskripsi || '';
                rating.textContent = (data.bintang || '☆☆☆☆☆') + ' '
                    + Number(data.rating ?? 0).toFixed(1).replace('.', ',') + ' ('
                    + (data.jumlah_ulasan ?? 0) + ' ulasan)';
                harga.textContent = data.harga_format || '';
                stok.textContent = (data.stok ?? 0) + ' cup';
                idProduk.value = data.id || '';
                isiVarian(data);
                qty.max = String(data.stok ?? 0);
                qty.value = '1';
                qty.setAttribute('aria-label', 'Jumlah ' + (data.nama || 'produk'));
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                document.body.classList.add('product-modal-open');
            }

            function closeModal() {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                document.body.classList.remove('product-modal-open');
            }

            document.querySelectorAll('.product-open-detail').forEach((card) => {
                card.addEventListener('click', function (e) {
                    if (e.target.closest('form, button, a, input, select, textarea')) return;
                    const raw = this.getAttribute('data-produk');
                    if (!raw) return;
                    try { openModal(JSON.parse(raw)); } catch (_) {}
                });
                card.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const raw = this.getAttribute('data-produk');
                        if (!raw) return;
                        try { openModal(JSON.parse(raw)); } catch (_) {}
                    }
                });
            });

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
            });
        })();
    </script>
    <script src="{{ asset('js/pelanggan-anim.js') }}?v={{ filemtime(public_path('js/pelanggan-anim.js')) }}"></script>
    <script src="{{ asset('js/soft-refresh.js') }}?v={{ filemtime(public_path('js/soft-refresh.js')) }}"></script>
</body>
</html>
