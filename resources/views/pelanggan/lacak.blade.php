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
                <a href="{{ route('pelanggan.lacak') }}" class="nav-item {{ request()->routeIs('pelanggan.lacak') ? 'active' : '' }}">
                    Lacak Pesanan
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
        @if(session('galat'))
            <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:14px;font-size:13px;font-weight:700;margin-bottom:14px;">
                {{ session('galat') }}
            </div>
        @endif
        @if(session('sukses'))
            <div class="alert-sukses" style="margin-bottom:14px;">{{ session('sukses') }}</div>
        @endif
        @if($errors->any())
            <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:14px;font-size:13px;font-weight:700;margin-bottom:14px;">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="search-card">
            <form method="GET" action="{{ route('pelanggan.lacak') }}" class="lacak-search-form">
                <input type="text" name="kode" value="{{ $kode ?? request('kode') }}" placeholder="Cari kode pesanan / nama / no. WA...">
                <button type="submit" class="btn-lacak">Cari</button>
            </form>
            <div style="font-size:12px;color:#64748b;font-weight:700;margin-top:10px;">
                @if(Auth::guard('pelanggan')->check())
                    Riwayat menampilkan pesanan akun ini dan pesanan guest di perangkat ini. Status diperbarui otomatis setiap 10 detik saat ada pesanan aktif.
                @else
                    Pesanan guest tersimpan di perangkat ini. <a href="{{ route('pelanggan.login') }}" style="color:#4d8822;text-decoration:underline;">Login</a> untuk menyimpan riwayat ke akun. Status diperbarui otomatis setiap 10 detik saat ada pesanan aktif.
                @endif
            </div>
        </div>

        <div id="lacak-live-region">
            @include('pelanggan._lacak-list')
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
    <script src="{{ asset('js/soft-refresh.js') }}?v={{ filemtime(public_path('js/soft-refresh.js')) }}"></script>
    @php
        $adaPesananAktif = ($daftarPesanan ?? collect())->contains(function ($p) {
            return !in_array($p->status_pesanan, ['selesai', 'dibatalkan'], true);
        });
    @endphp
    @if($adaPesananAktif)
        <script>
            (function () {
                var liveRegion = document.getElementById('lacak-live-region');
                if (!liveRegion) return;
                var url = @json(route('pelanggan.lacak', ['kode' => $kode ?: null, 'partial' => 1]));
                var busy = false;
                var sedangMenilai = false;

                // Jangan timpa form penilaian yang sedang diisi pelanggan.
                liveRegion.addEventListener('focusin', function (event) {
                    if (event.target.closest('.ulasan-form')) sedangMenilai = true;
                });
                liveRegion.addEventListener('submit', function () {
                    sedangMenilai = false;
                });

                async function refreshLacak() {
                    if (busy || sedangMenilai || document.visibilityState !== 'visible') return;
                    busy = true;
                    try {
                        var res = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                            credentials: 'same-origin',
                            cache: 'no-store'
                        });
                        if (!res.ok) return;
                        var html = await res.text();
                        if (!html || html === liveRegion.innerHTML) return;

                        var openCodes = Array.from(liveRegion.querySelectorAll('.lacak-card[open]'))
                            .map(function (el) { return el.getAttribute('data-kode'); })
                            .filter(Boolean);

                        liveRegion.innerHTML = html;

                        openCodes.forEach(function (code) {
                            var card = liveRegion.querySelector('.lacak-card[data-kode="' + CSS.escape(code) + '"]');
                            if (card) card.open = true;
                        });

                        if (!liveRegion.querySelector('.lacak-status.is-tunggu, .lacak-status.is-proses, .lacak-status.is-siap')) {
                            clearInterval(timer);
                        }
                    } catch (e) {
                        // abaikan error jaringan sementara; coba lagi di interval berikutnya
                    } finally {
                        busy = false;
                    }
                }

                var timer = setInterval(refreshLacak, 10000);
                document.addEventListener('visibilitychange', function () {
                    if (document.visibilityState === 'visible') refreshLacak();
                });
            })();
        </script>
    @endif
</body>
</html>
