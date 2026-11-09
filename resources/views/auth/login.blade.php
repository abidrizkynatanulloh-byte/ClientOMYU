<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Staf - OMYU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --hijau: #6aa332;
            --hijau-tua: #3d7a1c;
            --latar: #eaf8df;
            --garis: #b9d9a6;
        }
        * { box-sizing: border-box; }
        html { background: var(--latar); }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px 150px;
            font-family: 'Nunito', system-ui, sans-serif;
            color: var(--hijau-tua);
        }

        /* ===== HIASAN LATAR ===== */
        .hias { position: fixed; pointer-events: none; z-index: -1; }
        .hias-atas  { top: 0; left: 0; width: min(62vw, 380px); height: auto; }
        .hias-bawah { bottom: 0; left: 0; width: 100%; height: 150px; }
        .hias-toko  { bottom: 0; right: 0; width: min(34vw, 150px); height: auto; }

        /* ===== POSISI LOGO: atur di sini ===== */
        .logo { text-align: center; margin-bottom: 28px; }
        .logo img { width: 220px; max-width: 70%; height: auto; }
        /* ===================================== */

        .kartu {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 28px;
            padding: 28px 24px 22px;
            box-shadow: 0 12px 32px rgba(61, 122, 28, .12);
        }
        .kartu h1 { margin: 0; text-align: center; font-size: 30px; font-weight: 800; }
        .kartu .sub { margin: 4px 0 22px; text-align: center; font-size: 15px; color: #4d8a2f; }

        .galat {
            background: #fdecec; color: #b3261e;
            border-radius: 12px; padding: 10px 14px;
            font-size: 14px; margin-bottom: 14px;
        }

        .kolom {
            display: flex; align-items: center;
            border: 1.5px solid var(--garis);
            border-radius: 999px;
            padding: 0 16px;
            margin-bottom: 14px;
            background: #fff;
        }
        .kolom:focus-within { border-color: var(--hijau); box-shadow: 0 0 0 3px rgba(106,163,50,.18); }
        .kolom svg { width: 22px; height: 22px; flex: none; stroke: var(--hijau-tua); fill: none; stroke-width: 2; }
        .kolom input {
            flex: 1;
            min-width: 0;
            width: 100%;
            border: 0; outline: 0; background: transparent;
            padding: 15px 12px; font: inherit; font-size: 16px; color: #2d4a1d;
        }
        .kolom button {
            flex: none;
            border: 0; background: transparent; cursor: pointer; padding: 4px; display: flex;
        }

        .baris { display: flex; align-items: center; justify-content: space-between; margin: 4px 2px 18px; font-size: 15px; }
        .baris label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .baris input[type=checkbox] { width: 20px; height: 20px; accent-color: var(--hijau); }

        .tombol {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            border: 0; border-radius: 999px;
            padding: 16px;
            background: var(--hijau); color: #fff;
            font: inherit; font-size: 18px; font-weight: 700;
            cursor: pointer;
            transition: background .15s;
        }
        .tombol:hover { background: var(--hijau-tua); }
        .tombol svg { width: 22px; height: 22px; stroke: #fff; fill: none; stroke-width: 2.2; }

        .pemisah {
            display: flex; align-items: center; gap: 12px;
            margin-top: 22px; font-size: 14px; color: #4d8a2f;
        }
        .pemisah::before, .pemisah::after { content: ""; flex: 1; height: 1px; background: var(--garis); }
    </style>
</head>
<body>

    <!-- lekukan pojok kiri atas -->
    <svg class="hias hias-atas" viewBox="0 0 360 260" aria-hidden="true">
        <path d="M0 0 H330 C312 62 240 98 170 114 C90 134 34 172 0 255 Z" fill="#dcf1c4"/>
        <path d="M0 0 H255 C242 50 178 84 120 100 C60 118 20 152 0 208 Z" fill="#c3e2a5"/>
        <ellipse cx="84" cy="222" rx="16" ry="26" transform="rotate(35 84 222)" fill="#a8dc7a"/>
    </svg>

    <!-- bukit dan daun di bawah -->
    <svg class="hias hias-bawah" viewBox="0 0 400 150" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0 150 V84 C60 56 120 66 182 92 C252 122 322 70 400 52 V150 Z" fill="#d6f0c1"/>
        <path d="M0 150 V118 C70 94 132 108 202 128 C272 146 342 118 400 104 V150 Z" fill="#c4e6a9"/>
        <ellipse cx="34" cy="128" rx="14" ry="30" transform="rotate(-25 34 128)" fill="#9fd36c"/>
        <ellipse cx="62" cy="136" rx="12" ry="26" transform="rotate(30 62 136)" fill="#b4e082"/>
    </svg>

    <!-- ikon toko pojok kanan bawah -->
    <svg class="hias hias-toko" viewBox="0 0 150 150" aria-hidden="true">
        <rect x="26" y="56" width="98" height="94" fill="#c8e6ab"/>
        <path d="M14 56 L30 22 H120 L136 56 Z" fill="#b6dc91"/>
        <path d="M30 22 H50 L44 56 H14 Z M70 22 H90 L90 56 H66 Z M110 22 H120 L136 56 H112 Z" fill="#d6efbf"/>
        <rect x="40" y="80" width="30" height="70" fill="#b0d98a"/>
        <rect x="84" y="82" width="28" height="28" fill="#d6efbf"/>
        <ellipse cx="130" cy="136" rx="10" ry="20" transform="rotate(25 130 136)" fill="#9fd36c"/>
    </svg>

    <div class="logo">
        <img src="{{ asset('images/logo-omyu.png') }}" alt="OMYU - Less Sugar, More Fruits"
             onerror="this.style.display='none'">
    </div>

    <div class="kartu">
        <h1>Login Staf</h1>
        <p class="sub">Masuk untuk mengakses dashboard</p>

        @if ($errors->any())
            <div class="galat">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.proses') }}">
            @csrf

            <div class="kolom">
                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="username" required autofocus>
            </div>

            <div class="kolom">
                <svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                <input type="password" id="password" name="password" placeholder="Password" autocomplete="current-password" required>
                <button type="button" id="lihat" aria-label="Tampilkan password">
                    <svg viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>

            <div class="baris">
                <label><input type="checkbox" name="ingat"> Ingat saya</label>
            </div>

            <button type="submit" class="tombol">
                Masuk
                <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
        </form>

        <div class="pemisah">Khusus untuk Staf OMYU</div>
    </div>

    <script>
        const kolomPassword = document.getElementById('password');
        document.getElementById('lihat').addEventListener('click', function () {
            kolomPassword.type = kolomPassword.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>