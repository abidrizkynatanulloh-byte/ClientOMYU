<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruksi Pembayaran - OMYU</title>
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
            <div style="font-weight:800; font-size:15px; color:#193808;">Selesaikan Pembayaran</div>
            <a href="/" style="text-decoration:none; color:#193808; font-size:13.5px; font-weight:750; display:inline-flex; align-items:center; gap:6px;">
                &larr; Beranda
            </a>
        </header>
    </div>

    <main class="pay-wrapper">
        <div class="pay-card">
            <div class="order-code-badge">KODE: {{ $pesanan->kode_pesanan }}</div>
            <div style="font-size:13.5px; color:#64748b; font-weight:600;">Total Tagihan Pembayaran:</div>
            <div class="pay-total-amount">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</div>

            <div class="instruction-box">
                <h4>Rekening & Petunjuk Transfer:</h4>
                @if($pesanan->metode_bayar === 'transfer')
                    <p><strong>Bank BCA:</strong> 123-456-7890 a/n OMYU JUICE</p>
                    <p><strong>Bank Mandiri:</strong> 987-654-3210 a/n OMYU JUICE</p>
                @elseif($pesanan->metode_bayar === 'qris')
                    <p>Silakan scan QRIS OMYU melalui Dana, GoPay, OVO, ShopeePay, atau BCA Mobile:</p>
                    <div style="text-align: center; margin: 16px 0;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('QRIS-OMYU-' . $pesanan->kode_pesanan) }}" alt="QRIS OMYU" style="border: 2px solid #cbd5e1; border-radius: 14px; padding: 6px; background:#fff;">
                    </div>
                @else
                    <p>Pembayaran Tunai di Lokasi Booth saat pesanan siap diambil.</p>
                @endif
            </div>

            <form method="POST" action="{{ route('pelanggan.pembayaran.konfirmasi', $pesanan->kode_pesanan) }}" enctype="multipart/form-data">
                @csrf
                <div style="text-align: left; margin-bottom: 20px;">
                    <label style="display: block; font-weight: 750; font-size: 13px; margin-bottom: 8px; color: #193808;">Upload Bukti Transfer (Opsional):</label>
                    <input type="file" name="bukti_transfer" accept="image/*" style="font-size: 13px; width: 100%;">
                </div>

                <button type="submit" class="btn-confirm-glow" style="display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                    <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Saya Sudah Bayar (Konfirmasi)
                </button>
            </form>
        </div>
    </main>

    <script src="{{ asset('js/pelanggan-anim.js') }}?v={{ filemtime(public_path('js/pelanggan-anim.js')) }}"></script>
</body>
</html>
