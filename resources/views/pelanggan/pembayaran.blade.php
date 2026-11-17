<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran - OMYU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background: #f4f6f8; color: #1e293b; padding-bottom: 40px; }

        .app-container { max-width: 480px; margin: 0 auto; background: #ffffff; min-height: 100vh; box-shadow: 0 4px 20px rgba(0,0,0,0.06); position: relative; }

        /* Topbar */
        .pay-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            position: sticky;
            top: 0;
            background: #ffffff;
            z-index: 50;
        }
        .pay-title { font-size: 15px; font-weight: 800; color: #0f172a; }
        .pay-logo { height: 26px; object-fit: contain; }

        .pay-content { padding: 18px 16px; display: flex; flex-direction: column; gap: 16px; }

        .pay-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            text-align: center;
        }
        .order-badge {
            display: inline-block;
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 800;
            color: #475569;
            margin-bottom: 8px;
        }
        .total-amount {
            font-size: 26px;
            font-weight: 900;
            color: #15803d;
            margin: 6px 0 16px;
        }

        .pay-instruction {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            text-align: left;
            margin-bottom: 16px;
        }
        .pay-instruction h4 { font-size: 13px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        .pay-instruction p { font-size: 12px; color: #475569; line-height: 1.5; margin-bottom: 4px; }

        .btn-confirm-bayar {
            width: 100%;
            background: #15803d;
            color: #ffffff;
            border: none;
            font-size: 14px;
            font-weight: 900;
            padding: 14px;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(21, 128, 61, 0.35);
            transition: 0.15s;
        }
        .btn-confirm-bayar:hover { background: #166534; }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Topbar -->
        <header class="pay-topbar">
            <span class="pay-title">Pembayaran Pesanan</span>
            <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU" class="pay-logo">
        </header>

        <div class="pay-content">
            <div class="pay-card">
                <div class="order-badge">KODE: {{ $pesanan->kode_pesanan }}</div>
                <div style="font-size: 12px; color: #64748b;">Total Tagihan Pembayaran:</div>
                <div class="total-amount">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</div>

                <div class="pay-instruction">
                    <h4>Rekening & Petunjuk Transfer:</h4>
                    @if($pesanan->metode_bayar === 'transfer')
                        <p><strong>Bank BCA:</strong> 123-456-7890 a/n OMYU JUICE</p>
                        <p><strong>Bank Mandiri:</strong> 987-654-3210 a/n OMYU JUICE</p>
                    @elseif($pesanan->metode_bayar === 'qris')
                        <p>Silakan scan QRIS OMYU melalui Dana, GoPay, OVO, ShopeePay, atau BCA Mobile.</p>
                        <div style="text-align: center; margin: 12px 0;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode('QRIS-OMYU-' . $pesanan->kode_pesanan) }}" alt="QRIS OMYU" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 4px;">
                        </div>
                    @else
                        <p>Pembayaran Tunai di Lokasi Booth saat pesanan siap diambil.</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('pelanggan.pembayaran.konfirmasi', $pesanan->kode_pesanan) }}" enctype="multipart/form-data">
                    @csrf
                    <div style="text-align: left; margin-bottom: 16px;">
                        <label style="display: block; font-weight: 700; font-size: 12px; margin-bottom: 6px; color: #334155;">Upload Bukti Transfer (Opsional):</label>
                        <input type="file" name="bukti_transfer" accept="image/*" style="font-size: 12px; width: 100%;">
                    </div>

                    <button type="submit" class="btn-confirm-bayar">✅ Saya Sudah Bayar</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
