<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Barista & Kasir') - OMYU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/js/staf-scanner.js')
    <style>
        :root {
            --hijau-utama: #198754;
            --hijau-tua: #0f5132;
            --hijau-muda: #e8f5e9;
            --hijau-teks: #14532d;
            --latar: #f4f8f4;
            --putih: #ffffff;
            --garis: #e2e8f0;
            --teks-gelap: #0f172a;
            --teks-abu: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--latar);
            color: var(--teks-gelap);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-bottom: 74px; /* Space for bottom nav on mobile */
        }

        /* ===== TOPBAR (MOBILE-FIRST) ===== */
        .app-topbar {
            background: var(--putih);
            border-bottom: 1px solid var(--garis);
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .brand-img {
            display: block;
            height: 80px;
            width: auto;
        }

        @media (max-width: 640px) {
            .brand-img {
                height: 60px;
            }
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: #334155;
            transition: 0.15s;
        }

        .icon-btn:hover {
            background: #f1f5f9;
        }

        .notif-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ef4444;
            color: #ffffff;
            border-radius: 999px;
            border: 1.5px solid #ffffff;
            font-size: 9.5px;
            font-weight: 900;
            line-height: 1;
        }

        .desktop-nav-links {
            display: none;
        }

        /* ===== CONTAINER (MOBILE-FIRST) ===== */
        .app-container {
            width: 100%;
            max-width: 480px; /* Mockup size on mobile */
            margin: 0 auto;
            padding: 14px 14px 20px;
            flex: 1;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-sukses { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* ===== BOTTOM NAV BAR (MOBILE-FIRST) ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-around;
            padding: 6px 4px 8px;
            z-index: 50;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.03);
        }

        .nav-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            text-decoration: none;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 8px;
            transition: 0.15s;
            flex: 1;
            text-align: center;
        }

        .nav-tab.active {
            color: #198754;
        }

        .nav-tab svg {
            width: 22px;
            height: 22px;
            stroke-width: 2;
        }

        /* Profile Dropdown / Modal */
        .profile-menu {
            display: none;
            position: absolute;
            top: 54px;
            right: 14px;
            width: 220px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 12px;
            z-index: 60;
        }
        .profile-menu.show { display: block; }
        .profile-menu a, .profile-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 10px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            border-radius: 6px;
            background: none;
            border: none;
            cursor: pointer;
        }
        .profile-menu a:hover, .profile-menu button:hover {
            background: #f1f5f9;
        }
        .profile-menu a.active-menu {
            background: #f0fdf4;
            color: #15803d;
            font-weight: 700;
        }

        /* ===== NOTIFICATION MENU ===== */
        .notif-menu {
            display: none;
            position: absolute;
            top: calc(100% - 2px);
            right: 60px;
            width: 360px;
            background: #ffffff;
            border: 1px solid #e8eef5;
            border-radius: 18px;
            box-shadow: 0 24px 50px -20px rgba(15, 23, 42, 0.35);
            overflow: hidden;
            z-index: 60;
            transform-origin: top right;
        }

        .notif-menu.show {
            display: block;
            animation: notifIn 0.16s ease-out;
        }

        @keyframes notifIn {
            from { opacity: 0; transform: translateY(-6px) scale(0.98); }
            to { opacity: 1; transform: none; }
        }

        .notif-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            padding: 16px 18px 12px;
            background: linear-gradient(180deg, #f7fbf7, #ffffff);
            border-bottom: 1px solid #f1f5f9;
        }

        .notif-title {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: -0.2px;
            color: #0f172a;
        }

        .notif-sub {
            margin-top: 2px;
            font-size: 11.5px;
            font-weight: 700;
            color: #94a3b8;
        }

        .notif-clear {
            flex-shrink: 0;
            padding: 6px 10px;
            border: none;
            border-radius: 999px;
            background: #e8f5e9;
            color: #15803d;
            font-family: inherit;
            font-size: 10.5px;
            font-weight: 900;
            letter-spacing: 0.2px;
            cursor: pointer;
            transition: 0.15s;
        }

        .notif-clear:hover {
            background: #d7efd9;
        }

        .notif-list {
            max-height: 360px;
            overflow-y: auto;
            padding: 6px;
        }

        .notif-item {
            display: flex;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 12px;
            text-decoration: none;
            transition: 0.15s;
        }

        .notif-item:hover {
            background: #f6faf6;
        }

        .notif-item.notif-read {
            opacity: 0.5;
        }

        .notif-mark {
            flex-shrink: 0;
            width: 8px;
            height: 8px;
            margin-top: 5px;
            border-radius: 50%;
            background: #cbd5e1;
        }

        .notif-item.warning .notif-mark { background: #f59e0b; }
        .notif-item.danger .notif-mark { background: #ef4444; }
        .notif-item.success .notif-mark { background: #22c55e; }
        .notif-item.info .notif-mark { background: #3b82f6; }

        .notif-body {
            flex: 1;
            min-width: 0;
        }

        .notif-item-title {
            font-size: 12.5px;
            font-weight: 800;
            line-height: 1.3;
            color: #1e293b;
        }

        .notif-item-text {
            margin-top: 2px;
            font-size: 11.5px;
            line-height: 1.4;
            color: #64748b;
        }

        .notif-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 6px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .notif-tag {
            padding: 2px 7px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-size: 9.5px;
        }

        .notif-item.warning .notif-tag { background: #fef3c7; color: #b45309; }
        .notif-item.danger .notif-tag { background: #fee2e2; color: #b91c1c; }
        .notif-item.success .notif-tag { background: #dcfce7; color: #15803d; }
        .notif-item.info .notif-tag { background: #e0f2fe; color: #0369a1; }

        .notif-empty {
            padding: 30px 18px;
            text-align: center;
        }

        .notif-empty-title {
            font-size: 13px;
            font-weight: 900;
            color: #1e293b;
        }

        .notif-empty-text {
            margin-top: 3px;
            font-size: 11.5px;
            line-height: 1.4;
            color: #94a3b8;
        }

        .notif-footer {
            display: block;
            padding: 11px;
            border-top: 1px solid #f1f5f9;
            background: #fbfdfb;
            text-align: center;
            font-size: 11.5px;
            font-weight: 900;
            color: #198754;
            text-decoration: none;
            transition: 0.15s;
        }

        .notif-footer:hover {
            background: #f3faf4;
        }

        @media (max-width: 520px) {
            .notif-menu {
                top: calc(100% - 2px);
                left: 12px;
                right: 12px;
                width: auto;
            }
        }

        /* ===== DESKTOP ADAPTATION (>= 768px) ===== */
        @media (min-width: 768px) {
            body {
                padding-bottom: 30px;
                background: #f1f5f9;
            }

            .app-topbar {
                padding: 14px 40px;
            }

            .desktop-nav-links {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-left: 28px;
            }

            .desktop-nav-link {
                text-decoration: none;
                font-size: 13px;
                font-weight: 700;
                color: #64748b;
                padding: 7px 14px;
                border-radius: 8px;
                transition: 0.15s;
            }

            .desktop-nav-link:hover {
                background: #f1f5f9;
                color: #0f172a;
            }

            .desktop-nav-link.active {
                background: #198754;
                color: #ffffff;
            }

            .app-container {
                max-width: 1050px;
                padding: 24px 20px 40px;
            }

            /* Responsive 2-column grid on desktop */
            .desktop-split-layout {
                display: grid;
                grid-template-columns: 1.15fr 1fr;
                gap: 20px;
                align-items: start;
            }

            .bottom-nav {
                display: none; /* Hide mobile bottom nav on desktop since topbar nav exists */
            }
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>
<body>


    <!-- App Topbar -->
    <header class="app-topbar">
        <div style="display: flex; align-items: center;">
            <a href="{{ route('panel.beranda') }}" class="brand-logo">
                <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU - Less Sugar, More Fruits" class="brand-img">
            </a>

            <!-- Desktop Nav Links -->
            <nav class="desktop-nav-links">
                <a href="{{ route('panel.beranda') }}"
                   class="desktop-nav-link {{ request()->routeIs('panel.beranda') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('panel.staf.pesanan.index') }}"
                   class="desktop-nav-link {{ request()->routeIs('panel.staf.pesanan.*') ? 'active' : '' }}">
                    Pesanan Masuk
                </a>

                <a href="{{ route('panel.staf.produksi.index') }}"
                   class="desktop-nav-link {{ request()->routeIs('panel.staf.produksi.*') ? 'active' : '' }}">
                    Produksi / KDS
                </a>
                <a href="{{ route('panel.staf.kasir.index') }}"
                   class="desktop-nav-link {{ request()->routeIs('panel.staf.kasir.*') ? 'active' : '' }}">
                    Kasir POS
                </a>

                <a href="{{ route('panel.staf.stok.index') }}"
                   class="desktop-nav-link {{ request()->routeIs('panel.staf.stok.*') ? 'active' : '' }}">
                    Stok & Bahan
                </a>
            </nav>
        </div>

        <div class="topbar-actions">
            <!-- Notifikasi -->
            <button type="button" class="icon-btn" id="notifBell" title="Notifikasi" onclick="toggleNotifMenu()">
                <svg width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                @if($notifStafCount > 0)
                    <span class="notif-badge" id="notifBadge">{{ $notifStafCount > 9 ? '9+' : $notifStafCount }}</span>
                @endif
            </button>

            <!-- Menu Profil -->
            <button type="button" class="icon-btn" id="profileMenuBtn" title="Menu Profil" onclick="toggleProfileMenu()">
                <svg width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </button>
        </div>

        <!-- Profile Dropdown -->
        <div class="profile-menu" id="profileDropdown">
            <div style="padding: 6px 10px 10px; border-bottom: 1px solid #f1f5f9; margin-bottom: 6px;">
                <div style="font-weight: 800; font-size: 14px; color: #0f172a;">
                    {{ auth()->user()->nama }}
                </div>
                <div style="font-size: 11px; color: #64748b;">
                    {{ auth()->user()->email }} ({{ ucfirst(auth()->user()->peran) }})
                </div>
            </div>
            <a href="{{ route('panel.staf.profil.index') }}" class="{{ request()->routeIs('panel.staf.profil.*') ? 'active-menu' : '' }}">
                👤 Profil & Ganti Sandi
            </a>

            <div style="border-top:1px solid #f1f5f9;margin:6px 0;"></div>

            <a href="{{ route('panel.staf.kasir.index') }}"
               class="{{ request()->routeIs('panel.staf.kasir.*') ? 'active-menu' : '' }}">
                🧾 Kasir POS
            </a>

            <a href="{{ route('panel.staf.pesanan.scan') }}"
               class="{{ request()->routeIs('panel.staf.pesanan.scan') ? 'active-menu' : '' }}">
                📷 Scan QR Pengambilan
            </a>

            <a href="{{ route('panel.staf.aktivitas.index') }}"
               class="{{ request()->routeIs('panel.staf.aktivitas.*') ? 'active-menu' : '' }}">
                📋 Riwayat Aktivitas
            </a>

            <div style="border-top:1px solid #f1f5f9;margin:6px 0;"></div>

            @if(auth()->user()->adalahAdmin())
                <a href="{{ route('panel.admin.beranda') }}" style="color: #0f172a; font-weight: 700;">Panel Owner / Admin</a>
            @endif
            <form method="POST" action="{{ route('keluar') }}" style="margin-top: 6px; border-top: 1px solid #f1f5f9; padding-top: 6px;">
                @csrf
                <button type="submit" style="color: #ef4444; font-weight: 700;">Keluar</button>
            </form>
        </div>

        <!-- Notification Dropdown -->
        <div class="notif-menu" id="notifDropdown">
            <div class="notif-head">
                <div>
                    <div class="notif-title">Notifikasi</div>
                    <div class="notif-sub" id="notifSub">
                        {{ $notifStafCount > 0 ? $notifStafCount . ' pembaruan perlu perhatian' : 'Semua dalam kondisi aman' }}
                    </div>
                </div>
                @if($notifStafCount > 0)
                    <button type="button" class="notif-clear" onclick="markAllNotifRead()">Tandai dibaca</button>
                @endif
            </div>

            <div class="notif-list">
                @forelse($notifikasiStaf as $notif)
                    <a href="{{ $notif['url'] }}" class="notif-item {{ $notif['tone'] }}">
                        <span class="notif-mark"></span>
                        <div class="notif-body">
                            <div class="notif-item-title">{{ $notif['title'] }}</div>
                            <div class="notif-item-text">{{ $notif['teks'] }}</div>
                            <div class="notif-meta">
                                <span class="notif-tag">{{ $notif['tag'] }}</span>
                                <span>{{ $notif['waktu'] ? $notif['waktu']->locale('id')->diffForHumans() : '-' }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="notif-empty">
                        <div class="notif-empty-title">Belum ada notifikasi</div>
                        <div class="notif-empty-text">Pesanan dan stok bahan dalam kondisi aman.</div>
                    </div>
                @endforelse
            </div>

            <a href="{{ route('panel.staf.aktivitas.index') }}" class="notif-footer">Lihat semua aktivitas</a>
        </div>
    </header>

    <!-- Main App Content -->
    <main class="app-container">
        @if(session('sukses'))
            <div class="alert alert-sukses">✓ {{ session('sukses') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">⚠ {{ session('error') }}</div>
        @endif

        @if(session('info'))
            <div class="alert" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">ℹ {{ session('info') }}</div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="bottom-nav">
        <!-- Tab 1: Dashboard -->
        <a href="{{ route('panel.beranda') }}" class="nav-tab {{ request()->routeIs('panel.beranda') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Tab 2: Pesanan -->
        <a href="{{ route('panel.staf.pesanan.index') }}" class="nav-tab {{ request()->routeIs('panel.staf.pesanan.*') && ! request()->routeIs('panel.staf.pesanan.scan') && ! request('tab') && ! request('status') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Pesanan</span>
        </a>

        <!-- Tab 3: Produksi (Antrean Racik Jus & KDS) -->
        <a href="{{ route('panel.staf.produksi.index') }}" class="nav-tab {{ request()->routeIs('panel.staf.produksi.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M7 2h10l-2 11a4 4 0 0 1-4 3h0a4 4 0 0 1-4-3L5 2z"></path>
                <path d="M10 16v4"></path>
                <path d="M7 20h10"></path>
            </svg>
            <span>Produksi</span>
        </a>

        <!-- Tab 4: Kasir POS -->
        <a href="{{ route('panel.staf.kasir.index') }}" class="nav-tab {{ request()->routeIs('panel.staf.kasir.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <path d="M6 8h12"></path>
                <path d="M6 12h4v4H6z"></path>
                <path d="M14 12h4"></path>
                <path d="M14 16h4"></path>
            </svg>
            <span>Kasir</span>
        </a>

        <!-- Tab 5: Stok & Bahan -->
        <a href="{{ route('panel.staf.stok.index') }}" class="nav-tab {{ request()->routeIs('panel.staf.stok.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                <path d="m3.3 7 8.7 5 8.7-5"></path>
                <path d="M12 22V12"></path>
            </svg>
            <span>Stok & Bahan</span>
        </a>
    </nav>

    <script>
        function toggleProfileMenu() {
            document.getElementById('notifDropdown').classList.remove('show');
            document.getElementById('profileDropdown').classList.toggle('show');
        }

        function toggleNotifMenu() {
            document.getElementById('profileDropdown').classList.remove('show');
            document.getElementById('notifDropdown').classList.toggle('show');
        }

        function markAllNotifRead() {
            document.querySelectorAll('#notifDropdown .notif-item').forEach(function (item) {
                item.classList.add('notif-read');
            });

            localStorage.setItem('omyu_notif_read_count', '{{ $notifStafCount }}');

            const badge = document.getElementById('notifBadge');
            if (badge) {
                badge.style.display = 'none';
            }

            const sub = document.getElementById('notifSub');
            if (sub) {
                sub.textContent = 'Semua notifikasi sudah dibaca';
            }

            const clear = document.querySelector('#notifDropdown .notif-clear');
            if (clear) {
                clear.remove();
            }
        }

        (function () {
            const terakhirDibaca = parseInt(localStorage.getItem('omyu_notif_read_count') || '0', 10);
            if ({{ $notifStafCount }} <= terakhirDibaca) {
                markAllNotifRead();
            }
        })();

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            const notif = document.getElementById('notifDropdown');
            const profile = document.getElementById('profileDropdown');

            if (notif && !e.target.closest('#notifDropdown') && !e.target.closest('#notifBell')) {
                notif.classList.remove('show');
            }

            if (profile && !e.target.closest('#profileDropdown') && !e.target.closest('#profileMenuBtn')) {
                profile.classList.remove('show');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
