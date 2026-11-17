<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMYU Juice - Sensasi Segar Rendah Gula, Manis Alami</title>
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

        /* Top Header Navbar */
        .top-navbar {
            background: #ffffff;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid #f1f5f9;
        }
        .top-navbar .logo-img { height: 38px; object-fit: contain; }
        .nav-menu { display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 700; }
        .nav-menu a { text-decoration: none; color: #64748b; padding: 7px 16px; border-radius: 20px; transition: 0.2s; }
        .nav-menu a:hover { color: #166534; }
        .nav-menu a.active { background: #c6e79d; color: #143507; }
        .nav-icons { display: flex; align-items: center; gap: 16px; }
        .nav-icons a { color: #1e293b; text-decoration: none; font-size: 19px; position: relative; }
        .cart-badge { position: absolute; top: -6px; right: -8px; background: #22c55e; color: white; font-size: 10px; font-weight: 800; border-radius: 50%; width: 17px; height: 17px; display: flex; align-items: center; justify-content: center; }

        /* Hero Section */
        .hero-section {
            padding: 40px 28px 30px;
            background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            align-items: center;
        }
        @media (min-width: 768px) {
            .hero-section {
                grid-template-columns: 1.2fr 0.8fr;
                padding: 60px 48px;
            }
        }
        .tag-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #bbf7d0;
            color: #14532d;
            font-size: 12px;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 14px;
            letter-spacing: 0.5px;
        }
        .hero-title {
            font-size: 28px;
            font-weight: 900;
            line-height: 1.25;
            color: #0f172a;
            margin-bottom: 12px;
        }
        @media (min-width: 768px) {
            .hero-title { font-size: 44px; }
        }
        .hero-title span { color: #4d8822; }
        .hero-desc {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 22px;
            max-width: 520px;
        }
        @media (min-width: 768px) {
            .hero-desc { font-size: 16px; }
        }
        .hero-visual-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .hero-juice-circle {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            background: #4ade80;
            overflow: hidden;
            border: 6px solid #ffffff;
            box-shadow: 0 8px 24px rgba(74, 222, 128, 0.4);
        }
        @media (min-width: 768px) {
            .hero-juice-circle { width: 280px; height: 280px; border-width: 8px; }
        }
        .hero-juice-circle img { width: 100%; height: 100%; object-fit: cover; }

        .btn-order-now {
            display: inline-block;
            background: #4d8822;
            color: #ffffff;
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            box-shadow: 0 4px 14px rgba(77, 136, 34, 0.35);
            transition: 0.2s;
        }
        .btn-order-now:hover { background: #3d6e19; transform: translateY(-2px); }

        /* Feature Bar (3 Pills) */
        .feature-bar {
            background: #bde597;
            border-radius: 16px;
            margin: 0 28px 28px;
            padding: 18px 16px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            text-align: center;
            border: 1px solid #a3d973;
        }
        @media (min-width: 768px) {
            .feature-bar { margin: 0 48px 36px; padding: 22px 32px; }
        }
        .feature-item { padding: 0 8px; border-right: 1px solid rgba(0,0,0,0.08); }
        .feature-item:last-child { border-right: none; }
        .feature-item .icon { font-size: 26px; margin-bottom: 4px; }
        .feature-item .title { font-size: 13px; font-weight: 800; color: #143507; margin-bottom: 2px; }
        .feature-item .sub { font-size: 11px; color: #2d5a16; font-weight: 500; line-height: 1.3; }

        /* Grid Content on Desktop */
        .content-grid {
            padding: 0 28px 30px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }
        @media (min-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr 1fr;
                padding: 0 48px 48px;
                gap: 24px;
            }
        }

        /* Card Sections */
        .content-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .box-title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        .box-desc { font-size: 13px; color: #475569; line-height: 1.6; }

        /* Map Section */
        .map-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .map-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 14px; font-weight: 700; color: #0f172a; }
        .map-header .verified { font-size: 12px; color: #64748b; font-weight: 500; text-decoration: underline; }
        .map-banner {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            border-radius: 12px;
            flex: 1;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #475569;
            text-align: center;
            padding: 16px;
        }
        .map-banner .pin { font-size: 32px; margin-bottom: 4px; }
        .map-banner a { font-size: 12px; font-weight: 700; color: #15803d; text-decoration: none; background: #fff; padding: 5px 14px; border-radius: 20px; margin-top: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }

        /* WhatsApp Card */
        .wa-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
            transition: 0.2s;
            height: 100%;
        }
        .wa-card:hover { border-color: #22c55e; background: #f0fdf4; transform: translateY(-2px); }
        .wa-left { display: flex; align-items: center; gap: 14px; }
        .wa-icon { width: 42px; height: 42px; background: #25d366; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; }
        .wa-label { font-size: 14px; font-weight: 800; color: #0f172a; }
        .wa-num { font-size: 13px; color: #64748b; margin-top: 2px; }
        .wa-arrow { font-size: 20px; color: #94a3b8; }

        /* Mutu OMYU Card */
        .mutu-card {
            background: #d8efb7;
            border: 1px solid #c2e592;
            border-radius: 16px;
            padding: 20px;
            color: #143507;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .mutu-title { font-size: 14px; font-weight: 900; letter-spacing: 0.5px; margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }
        .mutu-point { display: flex; gap: 10px; margin-bottom: 12px; font-size: 13px; line-height: 1.5; }
        .mutu-point:last-child { margin-bottom: 0; }
        .mutu-point .check { color: #166534; font-weight: 900; font-size: 16px; }
        .mutu-point strong { color: #0c2602; }

        /* Footer */
        .desktop-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            margin-top: auto;
        }

        /* Bottom Fixed Navigation Bar (Mobile Only) */
        .bottom-nav {
            display: none;
        }
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
            .bottom-nav a.active { color: #4d8822; }
            .bottom-nav a .nav-icon { font-size: 18px; }
            .bottom-badge { position: absolute; top: -2px; right: 8px; background: #ef4444; color: #fff; font-size: 9px; font-weight: 800; border-radius: 50%; width: 15px; height: 15px; display: flex; align-items: center; justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <a href="/">
                <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU" class="logo-img">
            </a>
            <nav class="nav-menu">
                <a href="/" class="active">Beranda</a>
                <a href="{{ route('pelanggan.katalog') }}">Menu Lengkap</a>
                <a href="{{ route('pelanggan.lacak') }}">Lacak Pesanan</a>
            </nav>
            <div class="nav-icons">
                <a href="{{ route('pelanggan.katalog') }}" title="Cari Menu">🔍</a>
                <a href="{{ route('pelanggan.keranjang') }}" title="Keranjang">
                    🛒
                    @if(count(session('keranjang', [])) > 0)
                        <span class="cart-badge">{{ count(session('keranjang', [])) }}</span>
                    @endif
                </a>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero-section">
            <div>
                <div class="tag-pill">🌿 100% BUAH MURNI</div>
                <h1 class="hero-title">
                    Sensasi Segar <span>Rendah Gula,</span><br>Manis Alami.
                </h1>
                <p class="hero-desc">
                    Perasan buah asli pilihan tanpa pemanis buatan untuk gaya hidup sehat Anda. Pesan online tanpa antre dan ambil di outlet OMYU!
                </p>
                <a href="{{ route('pelanggan.katalog') }}" class="btn-order-now">
                    🍹 Pesan Sekarang Tanpa Antre &rarr;
                </a>
            </div>

            <div class="hero-visual-wrap">
                <div class="hero-juice-circle">
                    <img src="https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=500&auto=format&fit=crop&q=80" alt="OMYU Fresh Juice">
                </div>
            </div>
        </section>

        <!-- Feature Bar 3 Pills -->
        <section class="feature-bar">
            <div class="feature-item">
                <div class="icon">🍎</div>
                <div class="title">100% Buah Segar</div>
                <div class="sub">Tanpa esens sintetis</div>
            </div>
            <div class="feature-item">
                <div class="icon">🍯</div>
                <div class="title">Atur Manismu</div>
                <div class="sub">0%, 25%, 50%, Normal</div>
            </div>
            <div class="feature-item">
                <div class="icon">⚡</div>
                <div class="title">Ambil Kilat</div>
                <div class="sub">Tanpa antre & ribet</div>
            </div>
        </section>

        <!-- Content Grid (2x2 on Desktop, 1 Column on Mobile) -->
        <div class="content-grid">
            <!-- Layanan Booth & Info -->
            <section class="content-box">
                <div class="box-title">🌿 Layanan Booth & Informasi</div>
                <p class="box-desc">
                    <strong>Hubungi & Kunjungi Booth OMYU:</strong> Temukan segelas kesegaran alami 100% buah murni di booth kami atau hubungi tim barista kami untuk pesanan katering, acara kantor, dan kemitraan buah lokal.
                </p>
            </section>

            <!-- Mutu OMYU Card -->
            <section class="mutu-card">
                <div class="mutu-title">🌿 STANDAR MUTU OMYU</div>
                <div class="mutu-point">
                    <span class="check">✔</span>
                    <div>
                        <strong>Tanpa Sirup Pemanis Buatan:</strong> Rasa manis alami asli dari kematangan buah atau opsi madu randu murni.
                    </div>
                </div>
                <div class="mutu-point">
                    <span class="check">✔</span>
                    <div>
                        <strong>Higienitas Booth Terjaga:</strong> Seluruh buah dicuci bersih dengan ozone water dan disimpan dalam chiller steril.
                    </div>
                </div>
            </section>

            <!-- Peta Lokasi -->
            <section class="map-card">
                <div class="map-header">
                    <span>Peta Lokasi</span>
                    <span class="verified">Terverifikasi Google Maps</span>
                </div>
                <div class="map-banner">
                    <div class="pin">📍</div>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a;">Booth OMYU Juice Center</div>
                    <div style="font-size: 12px; color: #475569;">Buka Setiap Hari: 09.00 - 21.00 WIB</div>
                    <a href="https://maps.google.com" target="_blank">Buka Petunjuk Arah ↗</a>
                </div>
            </section>

            <!-- WhatsApp Card -->
            <a href="https://wa.me/6285643123456?text=Halo%20OMYU,%20saya%20ingin%20tanya%20menu" target="_blank" class="wa-card">
                <div class="wa-left">
                    <div class="wa-icon">💬</div>
                    <div>
                        <div class="wa-label">WhatsApp Admin / Barista</div>
                        <div class="wa-num">085643123456 (Respon Cepat)</div>
                    </div>
                </div>
                <div class="wa-arrow">❯</div>
            </a>
        </div>

        <!-- Footer -->
        <footer class="desktop-footer">
            <p><strong>OMYU Fresh Juice</strong> &bull; Less Sugar, More Fruits &bull; Buka Setiap Hari</p>
            <p style="margin-top: 6px; font-size: 12px; color: #94a3b8;">&copy; {{ date('Y') }} OMYU. Hak Cipta Dilindungi.</p>
        </footer>

        <!-- Bottom Fixed Navigation Bar (Mobile Only) -->
        <nav class="bottom-nav">
            <a href="/" class="active">
                <span class="nav-icon">🏠</span>
                <span>Beranda</span>
            </a>
            <a href="{{ route('pelanggan.katalog') }}">
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
