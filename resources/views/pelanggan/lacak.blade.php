<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian & Lacak Pesanan - OMYU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background: #f8fafc; color: #1e293b; }

        .app-container {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 4px 30px rgba(0,0,0,0.05);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .ticket-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 28px;
            border-bottom: 1px solid #f1f5f9;
            position: sticky;
            top: 0;
            background: #ffffff;
            z-index: 50;
        }
        @media (min-width: 768px) { .ticket-topbar { padding: 18px 48px; } }
        .ticket-title { font-size: 16px; font-weight: 800; color: #0f172a; }
        .ticket-logo { height: 32px; object-fit: contain; }

        .ticket-content {
            padding: 20px 24px 80px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        @media (min-width: 768px) {
            .ticket-content { padding: 32px 48px 40px; }
        }

        /* Card 1: Notification Card */
        .notif-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            gap: 16px;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .check-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #15803d;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 900;
            flex-shrink: 0;
        }
        .notif-text h3 { font-size: 15px; font-weight: 900; color: #0f172a; margin-bottom: 3px; }
        .notif-text p { font-size: 12.5px; color: #475569; line-height: 1.4; }

        /* Card 2: QR Ticket Card */
        .qr-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .pill-role {
            display: inline-block;
            background: #c6e79d;
            color: #143507;
            font-size: 11px;
            font-weight: 900;
            padding: 6px 16px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
        }
        .ticket-label { font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.5px; margin-bottom: 4px; }
        .queue-code { font-size: 20px; font-weight: 900; color: #166534; margin-bottom: 18px; }

        .qr-box {
            display: inline-block;
            background: #ffffff;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            margin-bottom: 24px;
        }
        .qr-box img { width: 190px; height: 190px; display: block; }

        .btn-unduh {
            display: inline-block;
            max-width: 300px;
            width: 100%;
            background: #3b82f6;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 800;
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: 0.15s;
        }
        .btn-unduh:hover { background: #2563eb; }

        /* Card 3: Estimasi Waktu Siap */
        .estimasi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .clock-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #15803d;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .estimasi-label { font-size: 12px; font-weight: 900; color: #0f172a; letter-spacing: 0.5px; }
        .estimasi-time { font-size: 13.5px; font-weight: 700; color: #334155; margin-top: 2px; }

        /* Search Box */
        .search-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 20px;
        }
        .search-box form { display: flex; gap: 10px; }
        @media (max-width: 600px) { .search-box form { flex-direction: column; } }
        .search-box input {
            flex: 1;
            padding: 11px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13.5px;
            box-sizing: border-box;
        }
        .btn-lacak-submit {
            background: #0f172a;
            color: #ffffff;
            padding: 11px 24px;
            border-radius: 8px;
            border: none;
            font-size: 13.5px;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
        }

        /* Detail Menu */
        .order-detail-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 20px;
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
        }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Topbar -->
        <header class="ticket-topbar">
            <span class="ticket-title">Rincian & Tiket Pesanan</span>
            <div style="display:flex; align-items:center; gap:16px;">
                <a href="{{ route('pelanggan.katalog') }}" style="text-decoration:none; color:#15803d; font-size:13px; font-weight:700;">+ Pesan Lagi</a>
                <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU" class="ticket-logo">
            </div>
        </header>

        <div class="ticket-content">
            <!-- Search Form -->
            <div class="search-box">
                <form method="GET" action="{{ route('pelanggan.lacak') }}">
                    <input type="text" name="kode" value="{{ request('kode') }}" placeholder="Masukkan Kode Pesanan (Contoh: OMYU-ORD-...)" required>
                    <button type="submit" class="btn-lacak-submit">Cek Status Pesanan</button>
                </form>
            </div>

            @if($pesanan)
                <!-- Card 1: Notification Status -->
                <div class="notif-card">
                    <div class="check-circle">✓</div>
                    <div class="notif-text">
                        <h3>
                            @if($pesanan->status_pesanan === 'selesai')
                                Hore!! Pesanan Sudah Selesai
                            @elseif($pesanan->status_pesanan === 'siap_diambil')
                                Siap Diambil!! Pesanan Menunggu Anda di Outlet
                            @elseif($pesanan->status_pesanan === 'dibatalkan')
                                Pesanan Dibatalkan
                            @else
                                Yummy!! Pesanan Sedang Diproses
                            @endif
                        </h3>
                        <p>Tunjukkan kode QR di bawah ini ke barista saat tiba di counter OMYU.</p>
                    </div>
                </div>

                <!-- Card 2: QR Ticket Card -->
                <div class="qr-card" id="printable-ticket">
                    <div class="pill-role">TUNJUKKAN KE KASIR / BARISTA</div>
                    <div class="ticket-label">KODE ANTRIAN RESMI</div>
                    <div class="queue-code">#{{ $pesanan->kode_pesanan }}</div>

                    <!-- Dynamic Live QR Code -->
                    <div class="qr-box">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($pesanan->kode_pesanan) }}" alt="QR Code Pesanan">
                    </div>

                    <div>
                        <button type="button" onclick="window.print()" class="btn-unduh">UNDUH / CETAK TIKET</button>
                    </div>
                </div>

                <!-- Card 3: Estimasi Waktu Siap -->
                <div class="estimasi-card">
                    <div class="clock-circle">⏱</div>
                    <div>
                        <div class="estimasi-label">ESTIMASI WAKTU SIAP</div>
                        <div class="estimasi-time">{{ $pesanan->catatan ?: 'Segera dalam 15-20 menit' }}</div>
                    </div>
                </div>

                <!-- Detail Ringkasan Menu -->
                <div class="order-detail-card">
                    <div style="font-size:13px; font-weight:800; color:#0f172a; margin-bottom:10px;">Menu yang Dipesan:</div>
                    @foreach($pesanan->itemPesanan as $item)
                        <div style="display:flex; justify-content:space-between; font-size:13px; color:#475569; margin-bottom:6px;">
                            <span>{{ $item->nama_produk }} (x{{ $item->jumlah }})</span>
                            <span style="font-weight:700; color:#0f172a;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div style="border-top:1px solid #f1f5f9; margin-top:12px; padding-top:10px; display:flex; justify-content:space-between; font-size:14px; font-weight:900;">
                        <span>Total Bayar:</span>
                        <span style="color:#15803d;">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                    </div>
                </div>

            @elseif(request('kode'))
                <div style="background:#fee2e2; color:#b91c1c; font-size:13px; padding:14px 20px; border-radius:10px; border:1px solid #fecaca; text-align:center;">
                    Pesanan dengan kode <strong>{{ request('kode') }}</strong> tidak ditemukan. Silakan periksa kembali kodenya.
                </div>
            @endif
        </div>

        <!-- Bottom Fixed Navigation Bar (Mobile Only) -->
        <nav class="bottom-nav">
            <a href="/">
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
            </a>
            <a href="{{ route('pelanggan.lacak') }}" class="active">
                <span class="nav-icon">🔍</span>
                <span>Lacak</span>
            </a>
        </nav>
    </div>

</body>
</html>
