<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pemesanan - OMYU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background: #f8fafc; color: #1e293b; }

        .app-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 4px 30px rgba(0,0,0,0.05);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .checkout-topbar {
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
        @media (min-width: 768px) { .checkout-topbar { padding: 18px 48px; } }
        .checkout-logo { height: 32px; object-fit: contain; }
        .checkout-cart-link { text-decoration: none; color: #1e293b; font-size: 22px; position: relative; }
        .cart-badge {
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

        /* Layout Grid */
        .checkout-form {
            padding: 20px 24px 100px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 768px) {
            .checkout-form {
                grid-template-columns: 1.25fr 0.85fr;
                padding: 32px 48px;
                gap: 32px;
            }
        }

        .checkout-left { display: flex; flex-direction: column; gap: 16px; }
        .checkout-right { display: flex; flex-direction: column; gap: 16px; }

        /* Item Preview Cards */
        .item-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            gap: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .item-thumb {
            width: 65px;
            height: 65px;
            border-radius: 10px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .item-detail { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .item-name { font-size: 14px; font-weight: 800; color: #0f172a; }
        .item-desc { font-size: 11.5px; color: #64748b; line-height: 1.35; }
        .item-row { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; }
        .item-price { font-size: 13.5px; font-weight: 800; color: #0f172a; }
        .item-qty-badge { font-size: 11px; font-weight: 800; color: #15803d; background: #dcfce7; padding: 3px 10px; border-radius: 12px; }

        /* Card Section General */
        .section-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .section-header {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        /* Schedule Card (Jadwal Pengambilan) */
        .schedule-box { border: 1.5px solid #0f172a; border-radius: 10px; overflow: hidden; }
        .schedule-date {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid #0f172a;
            background: #ffffff;
        }
        .schedule-date input {
            border: none;
            outline: none;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            width: 100%;
            background: transparent;
        }
        .schedule-time {
            padding: 12px 16px;
            background: #ffffff;
        }
        .schedule-time select {
            width: 100%;
            border: none;
            outline: none;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            background: transparent;
            cursor: pointer;
        }

        /* Data Pemesanan Inputs */
        .input-pill {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 12px;
            position: relative;
        }
        .input-pill:last-child { margin-bottom: 0; }
        .input-pill label { display: block; font-size: 10.5px; font-weight: 800; color: #475569; letter-spacing: 0.5px; margin-bottom: 3px; }
        .input-pill input, .input-pill textarea {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
        }
        .input-pill .pencil-icon { position: absolute; right: 16px; top: 18px; font-size: 15px; color: #64748b; }

        /* Rincian Pembayaran Card */
        .summary-row { display: flex; justify-content: space-between; font-size: 13px; color: #334155; margin-bottom: 8px; }
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 900;
            color: #0f172a;
            border-top: 1.5px solid #0f172a;
            padding-top: 12px;
            margin-top: 10px;
        }

        /* Radio Pembayaran Cards */
        .payment-options { display: flex; flex-direction: column; gap: 12px; }
        .payment-card {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: 0.15s;
        }
        .payment-card:hover { border-color: #15803d; background: #f0fdf4; }
        .payment-left { display: flex; align-items: center; gap: 14px; }
        .custom-radio {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        input[type="radio"]:checked + .payment-card-body .custom-radio {
            border-color: #15803d;
            background: #15803d;
        }
        .radio-dot { width: 8px; height: 8px; border-radius: 50%; background: #ffffff; display: none; }
        input[type="radio"]:checked + .payment-card-body .radio-dot { display: block; }
        .payment-info .name { font-size: 14px; font-weight: 800; color: #0f172a; }
        .payment-info .sub { font-size: 11.5px; color: #64748b; margin-top: 2px; }
        .payment-icon { font-size: 22px; color: #334155; }

        /* Bottom Fixed Checkout Bar (Mobile Only) */
        .bottom-checkout-bar {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 14px 24px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -4px 16px rgba(0,0,0,0.06);
            z-index: 100;
        }
        @media (min-width: 768px) {
            .bottom-checkout-bar { display: none; }
        }
        .total-box .total-label { font-size: 10px; font-weight: 800; color: #64748b; letter-spacing: 0.5px; }
        .total-box .total-val { font-size: 17px; font-weight: 900; color: #0f172a; }
        .btn-bayar {
            background: #15803d;
            color: #ffffff;
            border: none;
            font-size: 14px;
            font-weight: 900;
            padding: 13px 34px;
            border-radius: 10px;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.35);
            transition: 0.15s;
        }
        .btn-bayar:hover { background: #166534; }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Topbar -->
        <header class="checkout-topbar">
            <a href="/">
                <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU" class="checkout-logo">
            </a>
            <div style="font-weight: 800; font-size: 16px; color: #0f172a;">Konfirmasi & Checkout Pesanan</div>
            <a href="{{ route('pelanggan.keranjang') }}" class="checkout-cart-link">
                🛒
                @if(count(session('keranjang', [])) > 0)
                    <span class="cart-badge">{{ count(session('keranjang', [])) }}</span>
                @endif
            </a>
        </header>

        <form method="POST" action="{{ route('pelanggan.checkout.proses') }}" class="checkout-form">
            @csrf

            <!-- Left Column: Item List & Schedule & Customer Form -->
            <div class="checkout-left">
                <!-- Item Cart Previews -->
                <div style="display:flex; flex-direction:column; gap:12px;">
                    @foreach($keranjang as $item)
                        @php
                            $namaLower = strtolower($item['nama_produk']);
                            $imgUrl = 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=150&auto=format&fit=crop&q=80';
                            if (str_contains($namaLower, 'kentang')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=150&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'alpukat')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=150&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'mangga')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1546173159-315724a31696?w=150&auto=format&fit=crop&q=80';
                            }
                        @endphp
                        <div class="item-card">
                            <div class="item-thumb">
                                <img src="{{ $imgUrl }}" alt="{{ $item['nama_produk'] }}">
                            </div>
                            <div class="item-detail">
                                <div>
                                    <div class="item-name">{{ $item['nama_produk'] }}</div>
                                    <div class="item-desc">{{ $item['catatan'] ?: 'Fresh jus alami pilihan.' }}</div>
                                </div>
                                <div class="item-row">
                                    <span class="item-price">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                    <span class="item-qty-badge">{{ $item['qty'] }} cup / porsi</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- 1. Jadwal Pengambilan -->
                <div class="section-card">
                    <div class="section-header">
                        <span>🕒</span>
                        <span>Jadwal Pengambilan di Outlet</span>
                    </div>
                    <div class="schedule-box">
                        <div class="schedule-date">
                            <input type="date" name="tanggal_ambil" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                            <span>📅</span>
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

                <!-- 2. Data Pemesanan -->
                <div class="section-card">
                    <div class="section-header">
                        <span>📋</span>
                        <span>Data Pemesanan</span>
                    </div>
                    <div class="input-pill">
                        <label>NAMA</label>
                        <input type="text" name="nama_pembeli" placeholder="Masukkan nama pemesan..." required>
                        <span class="pencil-icon">✏️</span>
                    </div>
                    <div class="input-pill">
                        <label>NO WHATSAPP</label>
                        <input type="text" name="telepon_pembeli" placeholder="Contoh: 085888333445" required>
                        <span class="pencil-icon">✏️</span>
                    </div>
                </div>

                <!-- 3. Catatan -->
                <div class="section-card">
                    <div class="section-header">
                        <span>✏️</span>
                        <span>Catatan Pesanan</span>
                    </div>
                    <div class="input-pill" style="margin-bottom:0;">
                        <textarea name="catatan" rows="2" placeholder="Masukkan catatan anda... (opsional)"></textarea>
                        <span class="pencil-icon">✏️</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Payment Details & Submit Button (Sticky on Desktop) -->
            <div class="checkout-right">
                <!-- 4. Rincian Pembayaran -->
                <div class="section-card">
                    <div class="section-header">
                        <span>Rincian Pembayaran</span>
                    </div>
                    <div class="summary-row" style="font-weight:700; color:#0f172a; margin-bottom:8px;">
                        <span>Total Produk : {{ count($keranjang) }} macam</span>
                    </div>
                    @foreach($keranjang as $item)
                        <div class="summary-row">
                            <span>{{ $item['nama_produk'] }} (x{{ $item['qty'] }})</span>
                            <span>Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="summary-total">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- 5. Metode Pembayaran -->
                <div class="section-card">
                    <div class="section-header">
                        <span>Pilihan Metode Pembayaran</span>
                    </div>
                    <div class="payment-options">
                        <!-- QRIS -->
                        <label style="cursor: pointer; display:block;">
                            <input type="radio" name="metode_bayar" value="qris" checked style="display:none;">
                            <div class="payment-card">
                                <div class="payment-left">
                                    <div class="custom-radio" style="background:#15803d; border-color:#15803d;">
                                        <div class="radio-dot" style="display:block;"></div>
                                    </div>
                                    <div class="payment-info">
                                        <div class="name">QRIS Instan</div>
                                        <div class="sub">Dana, Gopay, Ovo, ShopeePay</div>
                                    </div>
                                </div>
                                <div class="payment-icon">📱</div>
                            </div>
                        </label>

                        <!-- Transfer Bank -->
                        <label style="cursor: pointer; display:block;">
                            <input type="radio" name="metode_bayar" value="transfer" style="display:none;">
                            <div class="payment-card">
                                <div class="payment-left">
                                    <div class="custom-radio">
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="payment-info">
                                        <div class="name">Transfer Bank</div>
                                        <div class="sub">BCA / Mandiri</div>
                                    </div>
                                </div>
                                <div class="payment-icon">💳</div>
                            </div>
                        </label>
                    </div>

                    <!-- Desktop Submit Button inside right column -->
                    <div style="margin-top: 24px; display: none;" class="desktop-btn-wrap">
                        <button type="submit" class="btn-bayar" style="width: 100%;">
                            BAYAR SEKARANG (RP {{ number_format($totalHarga, 0, ',', '.') }}) &rarr;
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom Fixed Bar (Mobile Only) -->
            <div class="bottom-checkout-bar">
                <div class="total-box">
                    <div class="total-label">TOTAL PEMBAYARAN</div>
                    <div class="total-val">RP {{ number_format($totalHarga, 0, ',', '.') }}</div>
                </div>
                <button type="submit" class="btn-bayar">BAYAR</button>
            </div>
        </form>
    </div>

    <style>
        @media (min-width: 768px) {
            .desktop-btn-wrap { display: block !important; }
        }
    </style>
</body>
</html>
