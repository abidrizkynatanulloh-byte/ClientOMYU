<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMYU Juice - Fresh Juice, Smarter Business</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #fdfcf9; color: #1e293b; overflow-x: hidden; }

        /* Navbar */
        .navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 14px 30px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 40; }
        .brand { font-size: 20px; font-weight: 800; color: #6aa332; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .brand span { color: #0f172a; font-size: 14px; font-weight: 500; }
        .nav-actions { display: flex; align-items: center; gap: 12px; }

        /* Buttons */
        .btn { padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; transition: 0.15s; }
        .btn-primary { background: #6aa332; color: #ffffff; box-shadow: 0 2px 6px rgba(106, 163, 50, 0.25); }
        .btn-primary:hover { background: #5c8f2b; }
        .btn-dark { background: #0f172a; color: #ffffff; }
        .btn-dark:hover { background: #1e293b; }
        .btn-outline { background: transparent; border: 1px solid #cbd5e1; color: #334155; }
        .btn-outline:hover { background: #f1f5f9; }

        /* Hero Banner */
        .hero { padding: 50px 30px 40px; text-align: center; max-width: 800px; margin: 0 auto; }
        .tagline { display: inline-block; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
        .hero h1 { font-size: 38px; font-weight: 900; color: #0f172a; line-height: 1.2; margin-bottom: 14px; }
        .hero h1 span { color: #6aa332; }
        .hero p { font-size: 16px; color: #64748b; line-height: 1.6; margin-bottom: 24px; }

        /* Menu Showcase */
        .catalog { max-width: 1050px; margin: 0 auto; padding: 0 24px 60px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-header h2 { font-size: 20px; font-weight: 800; color: #0f172a; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 18px; }
        .menu-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between; }
        .menu-card h3 { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .menu-card p { font-size: 12px; color: #64748b; margin-bottom: 12px; flex-grow: 1; }
        .menu-card .price { font-size: 16px; font-weight: 800; color: #6aa332; }

        /* Floating Slide Panel Toggle Button */
        .floating-btn { position: fixed; right: 24px; bottom: 24px; background: #0f172a; color: #ffffff; padding: 14px 22px; border-radius: 30px; font-weight: 700; font-size: 14px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.3); border: 2px solid #6aa332; cursor: pointer; display: flex; align-items: center; gap: 10px; z-index: 50; transition: 0.2s transform; }
        .floating-btn:hover { transform: translateY(-3px); }
        .pulse-dot { width: 10px; height: 10px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.25); animation: pulse 1.8s infinite; }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        /* Slide Panel Overlay & Drawer */
        .slide-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); z-index: 60; opacity: 0; visibility: hidden; transition: 0.3s; }
        .slide-overlay.active { opacity: 1; visibility: visible; }

        .slide-panel { position: fixed; top: 0; right: -420px; width: 400px; max-width: 90vw; height: 100vh; background: #ffffff; z-index: 70; box-shadow: -10px 0 30px rgba(0,0,0,0.15); display: flex; flex-direction: column; transition: 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
        .slide-panel.active { right: 0; }

        .panel-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
        .panel-header h2 { font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; }
        .btn-close { width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; border: none; font-size: 16px; font-weight: bold; color: #475569; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .btn-close:hover { background: #cbd5e1; }

        .panel-body { padding: 24px; overflow-y: auto; flex: 1; }

        /* Panel Content Styles */
        .shortcut-card { display: flex; align-items: center; gap: 14px; padding: 14px; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration: none; color: #0f172a; margin-bottom: 12px; transition: 0.15s; background: #ffffff; }
        .shortcut-card:hover { border-color: #6aa332; transform: translateX(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .shortcut-icon { width: 42px; height: 42px; border-radius: 8px; background: #f0fdf4; color: #166534; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .shortcut-info h4 { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
        .shortcut-info p { font-size: 11px; color: #64748b; margin: 0; }

        .demo-box { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 14px; margin-top: 16px; }
        .demo-account { background: #ffffff; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 6px; font-size: 12px; margin-top: 8px; display: flex; justify-content: space-between; align-items: center; }
        .btn-copy { background: #e2e8f0; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header class="navbar">
        <a href="/" class="brand">
            OMYU JUICE <span>| Fresh Juice, Smarter Business</span>
        </a>
        <div class="nav-actions">
            @auth
                <span style="font-size: 13px; font-weight: 600; color: #475569;">
                    Halo, {{ auth()->user()->nama }} ({{ ucfirst(auth()->user()->peran) }})
                </span>
                <a href="{{ route('panel.beranda') }}" class="btn btn-primary">
                    📊 Buka Panel
                </a>
            @else
                <button type="button" class="btn btn-primary" onclick="bukaSlidePanel()">
                    🔐 Masuk Backend Staf
                </button>
            @endauth
        </div>
    </header>

    <!-- Hero Showcase -->
    <section class="hero">
        <div class="tagline">🥤 Sistem Manajemen UMKM Jus Buah</div>
        <h1>Fresh Juice, <span>Smarter Business</span></h1>
        <p>
            Sistem terintegrasi untuk kasir, manajemen stok bahan, resep, dan pesanan jus buah asli tanpa ribet.
        </p>
        <div style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" class="btn btn-dark" style="font-size: 14px; padding: 12px 24px;" onclick="bukaSlidePanel()">
                🚀 Akses Backend Staf Sekarang
            </button>
            <a href="{{ route('panel.staf.kasir.index') }}" class="btn btn-outline" style="font-size: 14px; padding: 12px 24px;">
                🛒 Buka Kasir POS Langsung
            </a>
        </div>
    </section>

    <!-- Menu Showcase -->
    <section class="catalog">
        <div class="section-header">
            <h2>🍹 Menu Jus Buah Pilihan OMYU</h2>
            <span style="font-size: 13px; color: #64748b;">Dikelola langsung lewat Panel Staf & Kasir</span>
        </div>

        <div class="menu-grid">
            @forelse($daftarProduk as $produk)
                <div class="menu-card">
                    <div>
                        <h3>{{ $produk->nama }}</h3>
                        <p>{{ $produk->deskripsi ?: 'Jus buah murni segar pilihan.' }}</p>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                        <span class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                        <span style="font-size: 11px; font-weight: 700; color: {{ $produk->stok > 0 ? '#16a34a' : '#dc2626' }};">
                            {{ $produk->stok > 0 ? "Stok: {$produk->stok} cup" : 'Habis' }}
                        </span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 30px; text-align: center; color: #94a3b8; background: #fff; border-radius: 10px;">
                    Belum ada menu yang ditampilkan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Floating Action Button: Slide Panel Trigger -->
    <button type="button" class="floating-btn" onclick="bukaSlidePanel()">
        <span class="pulse-dot"></span>
        <span>Akses Backend Staf</span>
        <span style="font-size: 16px;">&rarr;</span>
    </button>

    <!-- Slide Panel Overlay & Drawer Container -->
    <div class="slide-overlay" id="slideOverlay" onclick="tutupSlidePanel()"></div>

    <aside class="slide-panel" id="slidePanel">
        <div class="panel-header">
            <div>
                <h2>🚀 Panel Akses Staf OMYU</h2>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Navigasi Cepat Pengujian Fitur</div>
            </div>
            <button type="button" class="btn-close" onclick="tutupSlidePanel()">✕</button>
        </div>

        <div class="panel-body">
            @auth
                <!-- Jika Sedang Login -->
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px; margin-bottom: 20px;">
                    <div style="font-size: 11px; color: #166534; font-weight: 700; text-transform: uppercase;">Akun Aktif Saat Ini</div>
                    <div style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ auth()->user()->nama }}</div>
                    <div style="font-size: 12px; color: #475569;">{{ auth()->user()->email }} &bull; <strong>{{ ucfirst(auth()->user()->peran) }}</strong></div>
                </div>

                <div style="margin-bottom: 16px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                    Menu Backend Staf:
                </div>

                <a href="{{ route('panel.beranda') }}" class="shortcut-card">
                    <div class="shortcut-icon">📊</div>
                    <div class="shortcut-info">
                        <h4>Dashboard Staf (K-02)</h4>
                        <p>Ringkasan antrean pesanan & ketersediaan stok</p>
                    </div>
                </a>

                <a href="{{ route('panel.staf.kasir.index') }}" class="shortcut-card">
                    <div class="shortcut-icon" style="background: #eff6ff; color: #1d4ed8;">🛒</div>
                    <div class="shortcut-info">
                        <h4>Kasir POS Penjualan (K-07)</h4>
                        <p>Melayani transaksi langsung & cetak struk</p>
                    </div>
                </a>

                <a href="{{ route('panel.staf.pesanan.index') }}" class="shortcut-card">
                    <div class="shortcut-icon" style="background: #fefce8; color: #a16207;">📋</div>
                    <div class="shortcut-info">
                        <h4>Pesanan Masuk (K-03)</h4>
                        <p>Verifikasi pembayaran & tahapan produksi</p>
                    </div>
                </a>

                <a href="{{ route('panel.staf.stok.index') }}" class="shortcut-card">
                    <div class="shortcut-icon" style="background: #fdf2f8; color: #be185d;">📦</div>
                    <div class="shortcut-info">
                        <h4>Cek & Atur Stok (K-09, K-10)</h4>
                        <p>Penyesuaian stok fisik & catat bahan rusak</p>
                    </div>
                </a>

                <a href="{{ route('panel.staf.aktivitas.index') }}" class="shortcut-card">
                    <div class="shortcut-icon" style="background: #f1f5f9; color: #334155;">📝</div>
                    <div class="shortcut-info">
                        <h4>Riwayat Aktivitas (K-14)</h4>
                        <p>Audit log seluruh aksi operasional staf</p>
                    </div>
                </a>

                @if(auth()->user()->adalahAdmin())
                    <a href="{{ route('panel.admin.beranda') }}" class="shortcut-card" style="border-color: #0f172a;">
                        <div class="shortcut-icon" style="background: #0f172a; color: #ffffff;">👑</div>
                        <div class="shortcut-info">
                            <h4>Panel Owner Eksekutif</h4>
                            <p>Laba rugi, rekap penjualan & HPP per cup</p>
                        </div>
                    </a>
                @endif

                <form method="POST" action="{{ route('keluar') }}" style="margin-top: 20px;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%; justify-content: center; color: #dc2626; border-color: #fecaca; background: #fef2f2;">
                        🚪 Keluar dari Akun (Logout)
                    </button>
                </form>
            @else
                <!-- Jika Belum Login: Form Login Cepat / Demo Akun -->
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Masuk ke Akun Staf / Admin</h3>
                    <p style="font-size: 12px; color: #64748b;">Silakan masuk menggunakan akun internal untuk mengetes modul staf.</p>
                </div>

                <form method="POST" action="{{ route('login.proses') }}">
                    @csrf
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Email Staf:</label>
                        <input type="email" name="email" id="inputEmail" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;" placeholder="budi@omyu.test">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Kata Sandi:</label>
                        <input type="password" name="password" id="inputPassword" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;" placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 10px; font-size: 14px;">
                        🔐 Masuk ke Panel Staf
                    </button>
                </form>

                <!-- Kotak Bantuan Akun Uji Coba -->
                <div class="demo-box">
                    <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">
                        🔑 Akun Uji Coba (Klik untuk Isi Cepat):
                    </div>

                    <div class="demo-account">
                        <div>
                            <strong>Budi (Kasir)</strong>
                            <div style="color: #64748b;">budi@omyu.test</div>
                        </div>
                        <button type="button" class="btn-copy" onclick="isiAkunDemo('budi@omyu.test', 'Rahasia12345')">Pakai Ini</button>
                    </div>

                    <div class="demo-account">
                        <div>
                            <strong>Siti (Kasir)</strong>
                            <div style="color: #64748b;">siti@omyu.test</div>
                        </div>
                        <button type="button" class="btn-copy" onclick="isiAkunDemo('siti@omyu.test', 'Rahasia67890')">Pakai Ini</button>
                    </div>

                    <div class="demo-account">
                        <div>
                            <strong>Admin / Owner</strong>
                            <div style="color: #64748b;">admin@omyu.test</div>
                        </div>
                        <button type="button" class="btn-copy" onclick="isiAkunDemo('admin@omyu.test', 'ADMIN123')">Pakai Ini</button>
                    </div>
                </div>
            @endauth
        </div>
    </aside>

    <script>
        function bukaSlidePanel() {
            document.getElementById('slideOverlay').classList.add('active');
            document.getElementById('slidePanel').classList.add('active');
        }

        function tutupSlidePanel() {
            document.getElementById('slideOverlay').classList.remove('active');
            document.getElementById('slidePanel').classList.remove('active');
        }

        function isiAkunDemo(email, password) {
            const emailInput = document.getElementById('inputEmail');
            const passInput = document.getElementById('inputPassword');
            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = password;
                emailInput.focus();
            }
        }
    </script>
</body>
</html>

