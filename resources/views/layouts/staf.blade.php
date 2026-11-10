<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Staf Operasional') - OMYU Juice</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 0; background: #f8fafc; color: #1e293b; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 18px; font-weight: 800; color: #6aa332; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .brand span { color: #0f172a; font-weight: 500; font-size: 14px; }
        .user-nav { display: flex; align-items: center; gap: 16px; font-size: 13px; }
        .user-badge { background: #f1f5f9; padding: 5px 12px; border-radius: 20px; font-weight: 600; color: #475569; }
        .btn-keluar { background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .btn-keluar:hover { background: #fecaca; }
        
        .main-nav { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 8px 24px; display: flex; gap: 8px; overflow-x: auto; }
        .main-nav a { text-decoration: none; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; color: #64748b; white-space: nowrap; transition: 0.15s; }
        .main-nav a:hover { background: #f1f5f9; color: #0f172a; }
        .main-nav a.active { background: #6aa332; color: #ffffff; }

        .container { max-width: 1200px; margin: 0 auto; padding: 24px; }
        
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; font-weight: 500; }
        .alert-sukses { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .card { background: #ffffff; padding: 18px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .card h3 { margin: 0 0 6px; font-size: 13px; color: #64748b; font-weight: 600; }
        .card .angka { font-size: 24px; font-weight: 800; margin: 0; color: #0f172a; }
        .card .desc { font-size: 12px; color: #94a3b8; margin-top: 4px; }

        table { width: 100%; border-collapse: collapse; background: #ffffff; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; }
        th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        th { background: #f8fafc; font-weight: 700; color: #475569; }
        tr:hover { background: #fafafa; }

        .badge { padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; display: inline-block; text-transform: uppercase; }
        .badge-baru { background: #dbeafe; color: #1d4ed8; }
        .badge-proses { background: #fef9c3; color: #a16207; }
        .badge-siap { background: #e0e7ff; color: #4338ca; }
        .badge-selesai { background: #dcfce7; color: #15803d; }
        .badge-batal { background: #fee2e2; color: #b91c1c; }
        .badge-lunas { background: #dcfce7; color: #15803d; }
        .badge-menunggu { background: #fef3c7; color: #b45309; }

        .btn { padding: 7px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; border: none; cursor: pointer; }
        .btn-primary { background: #6aa332; color: white; }
        .btn-primary:hover { background: #5c8f2b; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 4px 8px; font-size: 11px; }

        .filter-bar { background: #ffffff; padding: 16px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filter-bar input, .filter-bar select { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; }
    </style>
    @yield('styles')
</head>
<body>

    <div class="topbar">
        <a href="{{ route('panel.beranda') }}" class="brand">
            OMYU JUICE <span>| Panel Staf</span>
        </a>
        <div class="user-nav">
            <span class="user-badge">👤 {{ auth()->user()->nama }} ({{ ucfirst(auth()->user()->peran) }})</span>
            <a href="{{ route('panel.staf.profil.index') }}" class="btn btn-secondary btn-sm">Profil & Sandi</a>
            @if(auth()->user()->adalahAdmin())
                <a href="{{ route('panel.admin.beranda') }}" class="btn btn-secondary btn-sm" style="background:#0f172a; color:#fff;">Panel Owner</a>
            @endif
            <form method="POST" action="{{ route('keluar') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-keluar">Keluar</button>
            </form>
        </div>
    </div>

    <div class="main-nav">
        <a href="{{ route('panel.beranda') }}" class="{{ request()->routeIs('panel.beranda') ? 'active' : '' }}">
            📊 Dashboard (K-02)
        </a>
        <a href="{{ route('panel.staf.kasir.index') }}" class="{{ request()->routeIs('panel.staf.kasir.*') ? 'active' : '' }}">
            🛒 Kasir POS (K-07)
        </a>
        <a href="{{ route('panel.staf.pesanan.index') }}" class="{{ request()->routeIs('panel.staf.pesanan.*') ? 'active' : '' }}">
            📋 Pesanan Masuk (K-03)
        </a>
        <a href="{{ route('panel.staf.stok.index') }}" class="{{ request()->routeIs('panel.staf.stok.*') ? 'active' : '' }}">
            📦 Cek & Atur Stok (K-09)
        </a>
        <a href="{{ route('panel.staf.aktivitas.index') }}" class="{{ request()->routeIs('panel.staf.aktivitas.*') ? 'active' : '' }}">
            📝 Riwayat Aktivitas (K-14)
        </a>
    </div>

    <div class="container">
        @if(session('sukses'))
            <div class="alert alert-sukses">✓ {{ session('sukses') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">⚠ {{ session('error') }}</div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">ℹ {{ session('info') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>

