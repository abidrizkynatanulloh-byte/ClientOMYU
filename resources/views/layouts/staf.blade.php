<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Dashboard Barista & Kasir') - OMYU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
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

        .brand-icon {
            font-size: 24px;
            line-height: 1;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 900;
            color: #198754;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 9px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.3px;
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
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 1.5px solid #fff;
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
                <img
                    src="{{ asset('images/logo-omyu.png') }}"
                    alt="OMYU Juice"
                    style="height: 36px; width: auto; object-fit: contain;"
                >
                <div>
                    <div class="brand-title">OMYU</div>
                    <div class="brand-sub">Panel Staf · Less Sugar, More Fruits</div>
                </div>
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

                <a href="{{ route('panel.staf.stok.index') }}"
                   class="desktop-nav-link {{ request()->routeIs('panel.staf.stok.*') ? 'active' : '' }}">
                    Stok & Bahan
                </a>
            </nav>
        </div>

        <div class="topbar-actions">
            <!-- Notifikasi -->
            <button type="button" class="icon-btn"
                    title="Notifikasi"
                    onclick="alert('Tidak ada notifikasi baru.')">
                <svg width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notif-badge"></span>
            </button>

            <!-- Menu Profil -->
            <button type="button" class="icon-btn"
                    title="Menu Profil"
                    onclick="toggleProfileMenu()">
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

            <a href="{{ route('panel.staf.profil.index') }}">
                👤 Profil & Ganti Sandi
            </a>

            <div style="border-top:1px solid #f1f5f9;margin:6px 0;"></div>

            <a href="{{ route('panel.staf.kasir.index') }}"
               class="{{ request()->routeIs('panel.staf.kasir.*') ? 'active-menu' : '' }}">
                🧾 Kasir POS
            </a>

            <a href="{{ route('panel.staf.aktivitas.index') }}"
               class="{{ request()->routeIs('panel.staf.aktivitas.*') ? 'active-menu' : '' }}">
                📋 Riwayat Aktivitas
            </a>

            <div style="border-top:1px solid #f1f5f9;margin:6px 0;"></div>

            @if(auth()->user()->adalahAdmin())
                <a href="{{ route('panel.admin.beranda') }}" style="color: #0f172a; font-weight: 700;">👑 Panel Owner / Admin</a>
            @endif
            <a href="/">🌐 Halaman Depan</a>
            <form method="POST" action="{{ route('keluar') }}" style="margin-top: 6px; border-top: 1px solid #f1f5f9; padding-top: 6px;">
                @csrf
                <button type="submit" style="color: #ef4444; font-weight: 700;">🚪 Keluar (Logout)</button>
            </form>
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
        <a href="{{ route('panel.staf.pesanan.index') }}" class="nav-tab {{ request()->routeIs('panel.staf.pesanan.*') && !request('status') ? 'active' : '' }}">
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

        <!-- Tab 4: Stok & Bahan -->
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
            const el = document.getElementById('profileDropdown');
            el.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const target = e.target.closest('.icon-btn');
            if (!target && !e.target.closest('#profileDropdown')) {
                dropdown.classList.remove('show');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
