<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - OMYU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background: #f8fafc; color: #1e293b; }

        .app-container {
            max-width: 1050px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 4px 30px rgba(0,0,0,0.05);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar */
        .cart-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 28px;
            border-bottom: 1px solid #f1f5f9;
            position: sticky;
            top: 0;
            background: #ffffff;
            z-index: 50;
        }
        @media (min-width: 768px) { .cart-topbar { padding: 20px 48px; } }
        .cart-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .cart-logo { height: 32px; object-fit: contain; }

        /* Main Cart Layout */
        .cart-layout {
            padding: 20px 24px 100px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 768px) {
            .cart-layout {
                grid-template-columns: 1.4fr 0.8fr;
                padding: 32px 48px;
                gap: 32px;
            }
        }

        /* Item List */
        .cart-items { display: flex; flex-direction: column; gap: 16px; }
        .cart-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            display: flex;
            gap: 16px;
            align-items: center;
        }
        .item-thumb {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .item-info { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .item-name { font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
        .item-sub { font-size: 12px; color: #64748b; line-height: 1.4; margin-bottom: 10px; }

        .item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .item-price { font-size: 14px; font-weight: 900; color: #0f172a; }

        /* Stepper Control (- 1 +) */
        .stepper { display: flex; align-items: center; gap: 10px; }
        .step-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            cursor: pointer;
            font-size: 14px;
            transition: 0.15s;
        }
        .step-minus { background: #e2e8f0; color: #475569; }
        .step-minus:hover { background: #cbd5e1; }
        .step-plus { background: #15803d; color: #ffffff; }
        .step-plus:hover { background: #166534; }
        .step-val { font-size: 14px; font-weight: 800; color: #0f172a; min-width: 18px; text-align: center; }

        /* Desktop Sidebar Summary Card */
        .summary-sidebar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.03);
            height: fit-content;
            position: sticky;
            top: 90px;
        }
        .summary-title { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
        .summary-line { display: flex; justify-content: space-between; font-size: 13px; color: #475569; margin-bottom: 10px; }
        .summary-total-line {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 900;
            color: #0f172a;
            border-top: 1.5px solid #0f172a;
            padding-top: 14px;
            margin-top: 14px;
            margin-bottom: 20px;
        }

        /* Empty State */
        .empty-cart {
            text-align: center;
            padding: 80px 24px;
            color: #64748b;
        }
        .empty-cart .icon { font-size: 54px; margin-bottom: 14px; }
        .btn-order-start {
            display: inline-block;
            margin-top: 20px;
            background: #15803d;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 20px;
        }

        /* Bottom Fixed Bar (Mobile Only) */
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
        .btn-lanjutkan {
            background: #15803d;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 900;
            padding: 12px 28px;
            border-radius: 10px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(21, 128, 61, 0.35);
            transition: 0.15s;
            text-align: center;
            display: inline-block;
        }
        .btn-lanjutkan:hover { background: #166534; }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Topbar -->
        <header class="cart-topbar">
            <a href="{{ route('pelanggan.katalog') }}" class="cart-title">
                🛒 Keranjang Belanja
            </a>
            <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU" class="cart-logo">
        </header>

        @if(empty($keranjang))
            <div class="empty-cart">
                <div class="icon">🛒</div>
                <h3 style="color:#0f172a; margin-bottom:8px; font-size:18px;">Keranjang Masih Kosong</h3>
                <p style="font-size: 14px;">Yuk pilih aneka menu jus buah segar favoritmu sekarang!</p>
                <a href="{{ route('pelanggan.katalog') }}" class="btn-order-start">+ Pilih Menu Jus Segar</a>
            </div>
        @else
            <div class="cart-layout">
                <!-- Cart Items (Left Column) -->
                <div class="cart-items">
                    @foreach($keranjang as $key => $item)
                        @php
                            $namaLower = strtolower($item['nama_produk']);
                            $imgUrl = 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=200&auto=format&fit=crop&q=80';
                            if (str_contains($namaLower, 'kentang')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=200&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'mangga')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1546173159-315724a31696?w=200&auto=format&fit=crop&q=80';
                            } elseif (str_contains($namaLower, 'alpukat')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=200&auto=format&fit=crop&q=80';
                            }
                        @endphp

                        <div class="cart-card">
                            <div class="item-thumb">
                                <img src="{{ $imgUrl }}" alt="{{ $item['nama_produk'] }}">
                            </div>
                            <div class="item-info">
                                <div>
                                    <div class="item-name">{{ $item['nama_produk'] }}</div>
                                    <div class="item-sub">{{ $item['catatan'] ?: 'Fresh jus alami pilihan.' }}</div>
                                </div>
                                <div class="item-row">
                                    <div class="item-price">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>

                                    <!-- Stepper (- 1 +) -->
                                    <div class="stepper">
                                        <form method="POST" action="{{ route('pelanggan.keranjang.update') }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="key" value="{{ $key }}">
                                            <input type="hidden" name="aksi" value="kurang">
                                            <button type="submit" class="step-btn step-minus">-</button>
                                        </form>

                                        <span class="step-val">{{ $item['qty'] }}</span>

                                        <form method="POST" action="{{ route('pelanggan.keranjang.update') }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="key" value="{{ $key }}">
                                            <input type="hidden" name="aksi" value="tambah">
                                            <button type="submit" class="step-btn step-plus">+</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div style="margin-top: 10px;">
                        <a href="{{ route('pelanggan.katalog') }}" style="color: #15803d; font-weight: 700; text-decoration: none; font-size: 13px;">
                            &larr; Tambah Menu Jus Lainnya
                        </a>
                    </div>
                </div>

                <!-- Desktop Sidebar Summary Card (Right Column) -->
                <div class="summary-sidebar">
                    <div class="summary-title">Rincian Pembayaran</div>
                    <div class="summary-line">
                        <span>Total Item:</span>
                        <span>{{ count($keranjang) }} macam</span>
                    </div>
                    @foreach($keranjang as $item)
                        <div class="summary-line">
                            <span>{{ $item['nama_produk'] }} (x{{ $item['qty'] }})</span>
                            <span>Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="summary-total-line">
                        <span>TOTAL PEMBAYARAN:</span>
                        <span style="color:#15803d;">RP {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('pelanggan.checkout') }}" class="btn-lanjutkan" style="width: 100%; box-sizing: border-box;">
                        LANJUTKAN KE CHECKOUT &rarr;
                    </a>
                </div>
            </div>

            <!-- Bottom Fixed Bar (Mobile Only) -->
            <div class="bottom-checkout-bar">
                <div class="total-box">
                    <div class="total-label">TOTAL PEMBAYARAN</div>
                    <div class="total-val">RP {{ number_format($totalHarga, 0, ',', '.') }}</div>
                </div>
                <a href="{{ route('pelanggan.checkout') }}" class="btn-lanjutkan">LANJUTKAN</a>
            </div>
        @endif
    </div>

</body>
</html>
