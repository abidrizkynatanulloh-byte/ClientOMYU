<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Pelanggan - OMYU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700;800;900&family=Nunito:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}?v={{ filemtime(public_path('css/pelanggan.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/pelanggan-anim.css') }}?v={{ filemtime(public_path('css/pelanggan-anim.css')) }}">
</head>
<body>
    <div class="top-scroll-shield"></div>
    <div class="topbar-wrapper">
        <header class="fixed-topbar">
            <a href="/" class="brand-container">
                <img src="{{ asset('images/logo_omyu.png') }}" alt="OMYU" class="brand-logo-img">
                <span class="brand-text">MYU</span>
            </a>
            <div style="font-weight:800;font-size:15px;color:#193808;">Akun Pelanggan</div>
            <a href="{{ route('pelanggan.katalog') }}" style="text-decoration:none;color:#193808;font-size:13.5px;font-weight:750;">Lihat Menu</a>
        </header>
    </div>

    @php
        $mode = request('mode', old('mode'));
        $teleponVal = preg_replace('/\D+/', '', (string) old('telepon', $telepon ?? request('telepon', '')));
    @endphp

    <main class="pay-wrapper" style="max-width:560px;">
        <div class="pay-card" style="text-align:left;">
            <div class="order-code-badge">AKSES PESAN</div>
            <h1 style="font-family:'Fredoka',sans-serif;font-size:28px;color:#193808;margin-bottom:6px;">Masuk untuk pesan</h1>
            <p style="font-size:13px;color:#64748b;font-weight:700;margin-bottom:18px;line-height:1.6;">Kamu bisa pesan tanpa login (tersimpan di perangkat ini). Login opsional pakai nomor HP + password agar riwayat aman, tidak tercampur, dan bisa diakses dari device lain.</p>

            @if(session('galat'))
                <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 12px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:14px;">{{ session('galat') }}</div>
            @endif
            @if(session('sukses'))
                <div style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:10px 12px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:14px;">{{ session('sukses') }}</div>
            @endif
            @if($errors->any())
                <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 12px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:14px;">{{ $errors->first() }}</div>
            @endif

            @if(!$mode)
                <form method="POST" action="{{ route('pelanggan.login.cek') }}">
                    @csrf
                    <div class="input-pill" style="margin-bottom:18px;">
                        <label>NOMOR HP</label>
                        <input type="text" name="telepon" value="{{ $teleponVal }}" placeholder="Contoh: 081234567890" required inputmode="numeric">
                    </div>
                    <button type="submit" class="btn-bayar-glow" style="width:100%;">Lanjutkan</button>
                </form>
            @elseif($mode === 'login')
                <form method="POST" action="{{ route('pelanggan.login.proses') }}">
                    @csrf
                    <input type="hidden" name="mode" value="login">
                    <div class="input-pill" style="margin-bottom:14px;">
                        <label>NOMOR HP</label>
                        <input type="text" name="telepon" value="{{ $teleponVal }}" required inputmode="numeric">
                    </div>
                    <div class="input-pill" style="margin-bottom:18px;">
                        <label>PASSWORD</label>
                        <input type="password" name="password" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" class="btn-bayar-glow" style="width:100%;">Masuk</button>
                </form>
                <div style="margin-top:12px;font-size:12px;font-weight:700;color:#64748b;text-align:center;">
                    Nomor belum punya akun? <a href="{{ route('pelanggan.login', ['telepon' => $teleponVal, 'mode' => 'daftar']) }}" style="color:#4d8822;text-decoration:none;">Daftar</a>
                </div>
            @else
                <form method="POST" action="{{ route('pelanggan.daftar.proses') }}">
                    @csrf
                    <input type="hidden" name="mode" value="daftar">
                    <div class="input-pill" style="margin-bottom:14px;">
                        <label>NAMA</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama kamu" required>
                    </div>
                    <div class="input-pill" style="margin-bottom:14px;">
                        <label>NOMOR HP</label>
                        <input type="text" name="telepon" value="{{ $teleponVal }}" required inputmode="numeric">
                    </div>
                    <div class="input-pill" style="margin-bottom:14px;">
                        <label>PASSWORD</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="input-pill" style="margin-bottom:18px;">
                        <label>KONFIRMASI PASSWORD</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                    <button type="submit" class="btn-bayar-glow" style="width:100%;">Daftar & Masuk</button>
                </form>
                <div style="margin-top:12px;font-size:12px;font-weight:700;color:#64748b;text-align:center;">
                    Sudah punya akun? <a href="{{ route('pelanggan.login', ['telepon' => $teleponVal, 'mode' => 'login']) }}" style="color:#4d8822;text-decoration:none;">Masuk</a>
                </div>
            @endif
        </div>
    </main>

    <script src="{{ asset('js/pelanggan-anim.js') }}?v={{ filemtime(public_path('js/pelanggan-anim.js')) }}"></script>
</body>
</html>
