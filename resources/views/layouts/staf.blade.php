<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Panel Staf') — OMYU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700;800&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        <script src="{{ \Illuminate\Support\Facades\Vite::asset('resources/js/staf-scanner.js') }}"></script>
    @endif
    <!-- OMYU Staf V35 Interactive Animations -->
    <link rel="stylesheet" href="{{ asset('css/staf-anim.css') }}?v={{ filemtime(public_path('css/staf-anim.css')) }}">
    <style>
        /* ============================================================
           OMYU STAF LAYOUT  — Mobile-first → Desktop sidebar layout
           ============================================================ */
        :root {
            --g:  #198754;   /* hijau utama   */
            --gd: #0f5132;   /* hijau tua     */
            --gb: #f0fdf4;   /* hijau bg      */
            --gl: #bbf7d0;   /* hijau border  */
            --bg: #f4f8f4;   /* latar         */
            --w:  #ffffff;
            --b:  #0f172a;   /* teks gelap    */
            --m:  #334155;   /* teks tengah   */
            --s:  #64748b;   /* teks abu      */
            --br: #e2e8f0;   /* garis         */
            --r:  #ef4444;   /* merah         */
            --sw: 230px;     /* lebar sidebar */
            --th: 60px;      /* tinggi topbar */
            --rr: 14px;      /* border-radius */
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
            max-width: 100%;
        }
        body {
            font-family: 'Nunito', system-ui, sans-serif;
            background: var(--bg);
            color: var(--b);
            min-height: 100vh;
            overflow-x: hidden;
            max-width: 100%;
            width: 100%;
            /* space for fixed topbar + mobile bottom nav */
            padding-top: var(--th);
            padding-bottom: 72px;
        }
        a { text-decoration: none; color: inherit; }
        a:active, button:active { transform: scale(.97); }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        }

        /* ===== TOPBAR ===== */
        .topbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
            height: var(--th);
            background: var(--w);
            border-bottom: 1px solid var(--br);
            display: flex; align-items: center;
            padding: 0 16px;
            gap: 12px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        .topbar-brand {
            display: flex; align-items: center;
            text-decoration: none;
        }

        /* ===== BRAND LOGO LOCKUP (OMYU) ===== */
        .brand-lockup {
            display: inline-flex;
            flex-direction: column;
            text-decoration: none;
            line-height: 1;
        }
        .brand-logo-row {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            line-height: 1;
        }
        .brand-fruit-icon {
            height: 28px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
        }
        .brand-myu {
            font-family: 'Fredoka', 'Nunito', system-ui, sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #7cb342;
            letter-spacing: 0.5px;
            line-height: 1;
            display: inline-block;
        }
        .brand-tagline {
            font-family: 'Nunito', system-ui, sans-serif;
            font-size: 8.5px;
            font-weight: 800;
            color: #475569;
            letter-spacing: 0.3px;
            margin-top: 2px;
            white-space: nowrap;
            text-transform: capitalize;
        }

        .sidebar-brand .brand-fruit-icon {
            height: 32px;
        }
        .sidebar-brand .brand-myu {
            font-size: 30px;
        }
        .sidebar-brand .brand-tagline {
            font-size: 9.5px;
            margin-top: 3px;
        }
        .topbar-spacer { flex: 1; }
        .topbar-btn {
            position: relative;
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: none; border: none; cursor: pointer; color: var(--m);
            transition: background .15s;
        }
        .topbar-btn:hover { background: #f1f5f9; }
        .topbar-avatar { overflow: hidden; background: #e8f5e9; color: #166534; font-size: 14px; font-weight: 900; border: 2px solid #dcfce7; }
        .topbar-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .notif-dot {
            position: absolute; top: 3px; right: 3px;
            min-width: 16px; height: 16px; padding: 0 4px;
            display: flex; align-items: center; justify-content: center;
            background: var(--r); color: #fff;
            border-radius: 999px; border: 1.5px solid #fff;
            font-size: 9px; font-weight: 900; line-height: 1;
            animation: notifPulse 2.4s ease-out infinite;
        }
        @keyframes notifPulse {
            0%   { box-shadow: 0 0 0 0 rgba(239,68,68,.45); }
            70%  { box-shadow: 0 0 0 7px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 10px 14px; border-radius: 12px;
            font-size: 13px; font-weight: 600; margin-bottom: 12px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-sukses { background: #dcfce7; color: #15803d; border: 1px solid var(--gl); }
        .alert-error  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info   { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

        /* ===== CONTENT AREA (mobile) ===== */
        .page-wrapper {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        .page-content {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
            padding: 16px 14px 20px;
        }

        /* ===== MOBILE BOTTOM NAV ===== */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 60;
            background: var(--w);
            border-top: 1px solid var(--br);
            display: flex; justify-content: space-around;
            padding: 5px 4px calc(5px + env(safe-area-inset-bottom));
            box-shadow: 0 -2px 12px rgba(0,0,0,.04);
        }
        .bnav-tab {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 2px;
            padding: 4px 2px;
            font-size: 9.5px; font-weight: 700; color: var(--s);
            border-radius: 10px; border: none; background: none; cursor: pointer;
            transition: color .15s, background .15s;
        }
        .bnav-tab.active { color: var(--g); }
        .bnav-tab svg { width: 22px; height: 22px; stroke-width: 2; }

        /* Center scan tab */
        .bnav-scan {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 2px;
            padding: 4px 2px;
            font-size: 9.5px; font-weight: 700; color: var(--s);
            border: 0; background: none; cursor: pointer;
            transition: color .15s ease;
        }
        .bnav-scan.active { color: var(--g); }
        .bnav-scan-icon {
            display: flex; align-items: center; justify-content: center;
            width: 46px; height: 46px; margin-top: -20px;
            border-radius: 50%;
            background: var(--g); color: #fff;
            border: 3px solid var(--w);
            box-shadow: 0 6px 16px -4px rgba(25,135,84,.6);
            transition: transform .15s ease, box-shadow .2s ease;
        }
        .bnav-scan:active .bnav-scan-icon { transform: scale(.9); }
        .bnav-scan svg { width: 23px; height: 23px; stroke-width: 2; }

        /* Menu Lainnya sheet */
        .more-backdrop {
            position: fixed; inset: 0; z-index: 55;
            background: rgba(15,23,42,.32);
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity .18s ease, visibility .18s ease;
        }
        .more-backdrop.show { opacity: 1; visibility: visible; pointer-events: auto; }
        .more-sheet {
            position: fixed; left: 10px; right: 10px;
            bottom: calc(64px + env(safe-area-inset-bottom));
            z-index: 61; padding: 10px;
            background: var(--w); border: 1px solid var(--br);
            border-radius: 16px;
            box-shadow: 0 18px 42px -14px rgba(15,23,42,.34);
            opacity: 0; visibility: hidden; transform: translateY(8px) scale(.98);
            transform-origin: bottom right;
            transition: opacity .18s ease, transform .18s ease, visibility .18s ease;
        }
        .more-sheet.show { opacity: 1; visibility: visible; transform: none; }
        .more-sheet-head {
            padding: 3px 9px 8px;
            font-size: 10.5px; font-weight: 900; color: var(--s);
            letter-spacing: .08em; text-transform: uppercase;
        }
        .more-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 9px; border-radius: 11px;
            color: var(--b); text-decoration: none;
            font-size: 13px; font-weight: 800;
        }
        .more-item.active { background: var(--gb); color: var(--g); }
        .more-item svg { width: 18px; height: 18px; stroke-width: 2; flex: none; }
        .more-label { flex: 1; }
        .more-chev { width: 14px !important; height: 14px !important; color: var(--s); }

        /* ===== DROPDOWN (profile + notif) ===== */
        .dropdown {
            display: none; position: absolute; z-index: 70;
            background: var(--w); border: 1px solid var(--br);
            border-radius: var(--rr);
            box-shadow: 0 16px 40px -12px rgba(15,23,42,.22);
        }
        .dropdown.show { display: block; animation: ddIn .15s ease; }
        @keyframes ddIn { from { opacity:0; transform:translateY(-6px) scale(.98); } to { opacity:1; transform:none; } }

        /* Profile dropdown */
        .profile-dd { top: calc(var(--th) + 4px); right: 12px; width: 230px; padding: 10px; }
        .profile-dd-head {
            padding: 6px 10px 10px; margin-bottom: 4px;
            border-bottom: 1px solid #f1f5f9;
        }
        .profile-dd-user { display: flex; align-items: center; gap: 10px; }
        .profile-dd-avatar { width: 36px; height: 36px; border-radius: 50%; overflow: hidden; background: #e8f5e9; color: #166534; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 900; flex-shrink: 0; border: 1px solid #d7e4dc; }
        .profile-dd-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .profile-dd-name { font-size: 14px; font-weight: 800; color: var(--b); }
        .profile-dd-sub  { font-size: 11px; color: var(--s); margin-top: 2px; }
        .profile-dd a, .profile-dd button {
            display: flex; align-items: center; gap: 8px;
            width: 100%; text-align: left;
            padding: 8px 10px; border-radius: 8px;
            font-size: 13px; font-weight: 600; color: var(--m);
            background: none; border: none; cursor: pointer;
            transition: background .12s, transform .12s;
        }
        .profile-dd a:hover, .profile-dd button:hover { background: #f1f5f9; transform: translateX(2px); }
        .profile-dd a.dd-active { background: var(--gb); color: #15803d; font-weight: 700; }
        .profile-dd hr { border: none; border-top: 1px solid #f1f5f9; margin: 5px 0; }
        .profile-dd .dd-logout { color: var(--r) !important; }

        /* Notif dropdown — modern */
        .notif-dd {
            top: calc(var(--th) + 8px); right: 12px; width: 372px;
            padding: 0; border-radius: 18px; overflow: hidden;
            box-shadow: 0 24px 60px -18px rgba(15,23,42,.28), 0 2px 8px rgba(15,23,42,.04);
        }
        .notif-dd-head {
            display: flex; align-items: center; gap: 11px;
            padding: 15px 16px 13px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 72%);
            border-bottom: 1px solid #eef2f6;
        }
        .notif-head-icon {
            flex-shrink: 0; width: 38px; height: 38px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            background: var(--g); color: #fff;
            box-shadow: 0 6px 14px -6px rgba(25,135,84,.7);
        }
        .notif-head-icon svg { width: 19px; height: 19px; }
        .notif-head-copy { flex: 1; min-width: 0; }
        .notif-dd-title { font-size: 14.5px; font-weight: 900; color: var(--b); letter-spacing: -.2px; }
        .notif-dd-sub   { font-size: 11px; font-weight: 600; color: var(--s); margin-top: 2px; }
        .notif-clear-btn {
            flex-shrink: 0; display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 11px; border: 1px solid var(--gl); border-radius: 999px;
            background: #fff; color: #15803d;
            font-size: 10.5px; font-weight: 800; cursor: pointer;
            transition: background .18s ease, transform .15s ease, box-shadow .18s ease;
        }
        .notif-clear-btn svg { width: 13px; height: 13px; }
        .notif-clear-btn:hover { background: var(--gb); box-shadow: 0 4px 10px -4px rgba(25,135,84,.4); }
        .notif-clear-btn:active { transform: scale(.95); }

        .notif-scroll {
            max-height: min(420px, 58vh); overflow-y: auto; padding: 8px;
            scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
        }
        .notif-scroll::-webkit-scrollbar { width: 6px; }
        .notif-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .notif-row {
            position: relative; display: flex; gap: 11px;
            padding: 11px 12px; margin-bottom: 4px;
            border: 1px solid transparent; border-radius: 13px;
            text-decoration: none;
            transition: background .18s ease, border-color .18s ease, transform .18s ease, box-shadow .18s ease, opacity .18s ease;
        }
        .notif-row:hover {
            background: #f8fafc; border-color: #e2e8f0;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px -10px rgba(15,23,42,.35);
        }
        .notif-row:active { transform: scale(.99); }
        .notif-row.read { opacity: .7; }
        .notif-row.read:hover { opacity: 1; }
        .notif-row.read .notif-row-title { font-weight: 700; }
        .notif-unread {
            position: absolute; left: 0; top: 14px; bottom: 14px; width: 3px;
            border-radius: 0 3px 3px 0; background: var(--g);
        }
        .notif-row.warning .notif-unread { background: #f59e0b; }
        .notif-row.danger  .notif-unread { background: var(--r); }
        .notif-row.info    .notif-unread { background: #3b82f6; }
        .notif-row.read .notif-unread { display: none; }
        .notif-icon {
            flex-shrink: 0; width: 34px; height: 34px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            background: #f1f5f9; color: #475569;
        }
        .notif-icon svg { width: 17px; height: 17px; }
        .notif-row.warning .notif-icon { background: #fffbeb; color: #d97706; }
        .notif-row.danger  .notif-icon { background: #fef2f2; color: #dc2626; }
        .notif-row.success .notif-icon { background: #f0fdf4; color: #16a34a; }
        .notif-row.info    .notif-icon { background: #eff6ff; color: #2563eb; }
        .notif-row-body { flex: 1; min-width: 0; }
        .notif-row-title { font-size: 12.5px; font-weight: 800; color: #0f172a; line-height: 1.35; }
        .notif-row-text  { font-size: 11.5px; color: var(--s); margin-top: 3px; line-height: 1.45; }
        .notif-row-meta  { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 7px; align-items: center; }
        .notif-tag {
            font-size: 9.5px; font-weight: 800; letter-spacing: .2px; padding: 3px 8px;
            border-radius: 999px; background: #f1f5f9; color: #475569;
        }
        .notif-row.warning .notif-tag { background: #fef3c7; color: #b45309; }
        .notif-row.danger  .notif-tag { background: #fee2e2; color: #b91c1c; }
        .notif-row.success .notif-tag { background: #dcfce7; color: #15803d; }
        .notif-row.info    .notif-tag { background: #e0f2fe; color: #0369a1; }
        .notif-time { font-size: 10px; font-weight: 600; color: #94a3b8; }
        .notif-empty-box { padding: 30px 18px; text-align: center; }
        .notif-empty-icon {
            width: 52px; height: 52px; margin: 0 auto 12px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: var(--gb); color: var(--g);
        }
        .notif-empty-icon svg { width: 26px; height: 26px; }
        .notif-empty-box p { font-size: 13px; font-weight: 900; color: var(--b); }
        .notif-empty-box span { display: block; margin-top: 4px; font-size: 11px; color: #94a3b8; line-height: 1.45; }
        .notif-footer-link {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 12px; font-size: 11.5px; font-weight: 900; color: var(--g);
            border-top: 1px solid #eef2f6; background: #fbfdfb;
            transition: background .18s ease, gap .18s ease;
        }
        .notif-footer-link:hover { background: #f0fdf4; gap: 10px; }
        .notif-footer-link svg { width: 14px; height: 14px; }
        @media (max-width: 520px) {
            .notif-dd { left: 10px; right: 10px; width: auto; border-radius: 16px; }
            .notif-scroll { max-height: min(52vh, 380px); }
        }

        .help-fab {
            position: fixed; right: 14px; left: auto; bottom: calc(76px + env(safe-area-inset-bottom)); z-index: 65;
            width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(255,255,255,.35); border-radius: 50%;
            background: var(--g); color: #fff;
            box-shadow: 0 6px 18px -6px rgba(25,135,84,.65); cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .help-fab:hover { background: var(--gd); box-shadow: 0 8px 22px -6px rgba(25,135,84,.75); }
        .help-fab:active { transform: scale(.94); }
        .help-fab svg { width: 20px; height: 20px; }
        .help-fab::after {
            content: 'Bantuan'; position: absolute; right: 50%; bottom: calc(100% + 8px);
            transform: translateX(50%) translateY(3px);
            padding: 5px 8px; border-radius: 7px; background: var(--b); color: #fff;
            font-size: 10px; font-weight: 800; white-space: nowrap;
            opacity: 0; pointer-events: none; transition: opacity .15s ease, transform .15s ease;
        }
        .help-fab:hover::after, .help-fab:focus-visible::after { opacity: 1; transform: translateX(50%); }
        .help-panel {
            display: none; position: fixed; right: 14px; left: auto;
            bottom: calc(128px + env(safe-area-inset-bottom)); z-index: 66;
            width: min(320px, calc(100vw - 28px)); max-height: min(60vh, 440px);
            overflow: auto; padding: 14px;
            background: var(--w); border: 1px solid var(--br); border-radius: 14px;
            box-shadow: 0 18px 44px -14px rgba(15,23,42,.3);
        }
        .help-panel.show { display: block; animation: ddIn .15s ease; }
        .help-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .help-panel-title { display: flex; align-items: center; gap: 9px; }
        .help-panel-badge { width: 30px; height: 30px; border-radius: 9px; background: var(--gb); color: var(--g); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 900; }
        .help-panel-title strong { display: block; font-size: 14px; font-weight: 900; color: var(--b); }
        .help-panel-title small { display: block; font-size: 10px; color: var(--s); margin-top: 2px; }
        .help-close { width: 26px; height: 26px; border: 0; border-radius: 8px; background: #f1f5f9; color: var(--s); font-size: 16px; line-height: 1; cursor: pointer; }
        .help-section-label { margin: 12px 0 6px; font-size: 9px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; color: #94a3b8; }
        .help-links { display: grid; gap: 4px; }
        .help-link { display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 9px; background: #f8fafc; color: var(--m); font-size: 12px; font-weight: 700; text-decoration: none; }
        .help-link:hover { background: var(--gb); color: var(--gd); }
        .help-faq { display: grid; gap: 4px; }
        .help-faq details { padding: 7px 9px; border: 1px solid var(--br); border-radius: 9px; font-size: 12px; }
        .help-faq summary { cursor: pointer; font-weight: 800; color: var(--b); }
        .help-faq p { margin: 7px 0 0; color: var(--s); line-height: 1.5; }
        .help-panel-foot { display: flex; gap: 8px; margin-top: 14px; }
        .help-panel-foot a { flex: 1; text-align: center; padding: 9px 8px; border-radius: 9px; font-size: 11px; font-weight: 900; text-decoration: none; }
        .help-primary { background: var(--g); color: #fff; }
        .help-secondary { background: #f1f5f9; color: var(--m); }

        /* ===== DESKTOP (>= 768px) ===== */
        @media (min-width: 768px) {
            body { padding-bottom: 0; background: #f1f5f9; }

            /* Sidebar */
            .sidebar {
                position: fixed; top: 0; left: 0; bottom: 0; z-index: 45;
                width: var(--sw);
                background: var(--w);
                border-right: 1px solid var(--br);
                display: flex; flex-direction: column;
                padding: 0 0 20px;
                box-shadow: 2px 0 16px rgba(15,23,42,.04);
            }
            .sidebar-brand {
                display: block;
                padding: 16px 16px 14px;
                border-bottom: 1px solid #f1f5f9;
            }
            .sidebar-section {
                padding: 16px 14px 6px;
                font-size: 10px; font-weight: 900; letter-spacing: 1px;
                text-transform: uppercase; color: #94a3b8;
            }
            .sidebar-nav { flex: 1; padding: 0 10px; display: flex; flex-direction: column; gap: 2px; }
            .snav-link {
                display: flex; align-items: center; gap: 10px;
                padding: 10px 12px; border-radius: 10px;
                font-size: 13px; font-weight: 700; color: var(--s);
                transition: background .15s, color .15s, transform .12s;
            }
            .snav-link svg { width: 18px; height: 18px; flex-shrink: 0; stroke-width: 2; }
            .snav-link:hover { background: #f1f5f9; color: var(--b); transform: translateX(3px); }
            .snav-link.active {
                background: var(--g); color: #fff;
                box-shadow: 0 6px 14px -6px rgba(25,135,84,.55);
            }
            .snav-link.active svg { stroke: #fff; }
            .sidebar-divider { border: none; border-top: 1px solid #f1f5f9; margin: 8px 10px; }
            .sidebar-footer {
                padding: 10px 22px 0;
                font-size: 10.5px; font-weight: 700; color: #94a3b8;
            }

            body {
                padding-bottom: 0;
                padding-left: var(--sw);
                background: #f1f5f9;
                box-sizing: border-box;
            }

            /* Topbar offset */
            .topbar {
                left: var(--sw);
                right: 0;
                margin-left: 0;
                width: auto;
                max-width: none;
                padding: 0 24px;
                box-sizing: border-box;
            }

            /* Main content offset */
            .page-wrapper {
                margin-left: 0;
                width: 100%;
                max-width: 100%;
                min-width: 0;
                box-sizing: border-box;
            }
            .page-content {
                max-width: 1300px;
                padding: 24px 28px 48px;
                width: 100%;
                box-sizing: border-box;
            }

            /* Hide mobile bottom nav */
            .bottom-nav, .more-backdrop, .more-sheet { display: none !important; }

            .help-fab { left: calc(var(--sw) + 18px); right: auto; bottom: 18px; width: 42px; height: 42px; }
            .help-panel { left: calc(var(--sw) + 18px); right: auto; bottom: 70px; }

            /* Dropdown positional fix */
            .profile-dd { right: 20px; }
            .notif-dd   { right: 72px; }
        }

        @media (min-width: 1280px) {
            :root { --sw: 248px; }
        }
        @media (min-width: 1600px) {
            .page-content { max-width: 1400px; }
        }

        /* Mobile topbar — hide sidebar brand on mobile */
        .sidebar { display: none; }
        @media (min-width: 768px) { .sidebar { display: flex; } }

        /* ===== MODERN INTERACTIVE UI ENHANCEMENTS ===== */
        .btn, .btn-primary, .btn-hijau, .btn-selesai, .btn-mulai, .btn-scan, .btn-mini, .quick-link, .stat-card, .metric, .metric-card, .order-card, .prod-card, .bahan-card, .bnav-tab, .filter-chip, .filter-pills a {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease !important;
        }

        /* Card interactive hover lift & soft glow */
        .stat-card:hover, .quick-link:hover, .order-card:hover, .prod-card:hover, .bahan-card:hover, .metric-card:hover, .metric:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -8px rgba(25, 135, 84, 0.18), 0 3px 8px rgba(0,0,0,0.04) !important;
        }

        /* Tactile button press feedback */
        .btn:active, .quick-link:active, .stat-card:active, .metric:active, .metric-card:active, .bnav-tab:active {
            transform: scale(0.97) !important;
        }

        /* Button hover sheen */
        .btn:hover, .btn-primary:hover, .btn-hijau:hover, .btn-selesai:hover, .btn-mulai:hover {
            filter: brightness(1.05);
            box-shadow: 0 6px 18px -4px rgba(25, 135, 84, 0.42);
        }

        /* Form input interactive glow */
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: var(--g) !important;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.18) !important;
        }

        /* Modals: backdrop blur & spring entrance */
        .modal-overlay {
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            background: rgba(15, 23, 42, 0.65) !important;
            transition: opacity 0.22s ease, visibility 0.22s ease;
        }
        .modal-card {
            transition: transform 0.28s cubic-bezier(0.34, 1.45, 0.64, 1), opacity 0.22s ease;
            transform: scale(0.93) translateY(16px);
        }
        .modal-overlay.active .modal-card {
            transform: scale(1) translateY(0);
        }

        /* Live pulsating status dot */
        .pulse-live-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulseLiveRing 1.8s cubic-bezier(0.45, 0, 0.55, 1) infinite;
            margin-right: 5px;
            vertical-align: middle;
        }
        @keyframes pulseLiveRing {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        /* Toast notifications container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .omyu-toast {
            pointer-events: auto;
            min-width: 250px;
            max-width: 380px;
            padding: 12px 18px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 16px 36px -10px rgba(0,0,0,0.35);
            animation: toastSlideIn 0.26s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.25s ease;
        }
        .omyu-toast.sukses { background: #0f5132; border: 1.5px solid #22c55e; }
        .omyu-toast.error  { background: #991b1b; border: 1.5px solid #f87171; }
        .omyu-toast.info   { background: #1e3a8a; border: 1.5px solid #60a5fa; }
        .omyu-toast.fade-out { opacity: 0; transform: translateY(-10px) scale(0.95); }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateY(-14px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Button loading state */
        .btn.btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.85;
            cursor: wait !important;
        }
        .spinner-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spinCircle 0.6s linear infinite;
            margin-right: 6px;
            vertical-align: middle;
        }
        @keyframes spinCircle {
            to { transform: rotate(360deg); }
        }

        /* Lightbox Image Preview Modal */
        .lightbox-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 99998;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.22s ease;
        }
        .lightbox-modal.active {
            display: flex;
            opacity: 1;
        }
        .lightbox-box {
            position: relative;
            max-width: 90vw;
            max-height: 85vh;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            animation: zoomModalIn 0.25s cubic-bezier(0.34, 1.4, 0.64, 1);
        }
        .lightbox-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
        }
        .lightbox-close {
            background: none;
            border: none;
            font-size: 22px;
            color: #64748b;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 8px;
            transition: background 0.15s;
        }
        .lightbox-close:hover { background: #fee2e2; color: #dc2626; }
        .lightbox-img {
            max-width: 85vw;
            max-height: 72vh;
            object-fit: contain;
            display: block;
            background: #f1f5f9;
        }
        @keyframes zoomModalIn {
            from { transform: scale(0.92); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>
<body>
    <!-- Page Entrance Reveal Overlay -->
    <div class="staf-page-overlay"></div>

    {{-- ===== DESKTOP SIDEBAR ===== --}}
    <aside class="sidebar">
        <a href="{{ route('panel.beranda') }}" class="sidebar-brand">
            <div class="brand-logo-row">
                <img src="{{ asset('images/logo_icon.png') }}" alt="O" class="brand-fruit-icon">
                <span class="brand-myu">MYU</span>
            </div>
        </a>

        <div class="sidebar-section">Menu Utama</div>
        <nav class="sidebar-nav">
            <a href="{{ route('panel.beranda') }}"
               class="snav-link {{ request()->routeIs('panel.beranda') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('panel.staf.pesanan.index') }}"
               class="snav-link {{ request()->routeIs('panel.staf.pesanan.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Pesanan Masuk
            </a>

            <a href="{{ route('panel.staf.pesanan.scan') }}"
               class="snav-link {{ request()->routeIs('panel.staf.pesanan.scan') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M4 8V6a2 2 0 0 1 2-2h2"></path>
                    <path d="M16 4h2a2 2 0 0 1 2 2v2"></path>
                    <path d="M20 16v2a2 2 0 0 1-2 2h-2"></path>
                    <path d="M8 20H6a2 2 0 0 1-2-2v-2"></path>
                    <path d="M8 8h8v8H8z"></path>
                </svg>
                Scan QR Pengambilan
            </a>

            <a href="{{ route('panel.staf.produksi.index') }}"
               class="snav-link {{ request()->routeIs('panel.staf.produksi.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M7 2h10l-2 11a4 4 0 0 1-4 3h0a4 4 0 0 1-4-3L5 2z"></path>
                    <path d="M10 16v4"></path><path d="M7 20h10"></path>
                </svg>
                Produksi / KDS
            </a>

            <a href="{{ route('panel.staf.stok.index') }}"
               class="snav-link {{ request()->routeIs('panel.staf.stok.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                    <path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22V12"></path>
                </svg>
                Stok &amp; Bahan
            </a>

            <hr class="sidebar-divider">

            <a href="{{ route('panel.staf.kasir.index') }}"
               class="snav-link {{ request()->routeIs('panel.staf.kasir.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <path d="M6 8h12"></path><path d="M6 12h4v4H6z"></path>
                    <path d="M14 12h4"></path><path d="M14 16h4"></path>
                </svg>
                Kasir POS
            </a>

            <a href="{{ route('panel.staf.aktivitas.index') }}"
               class="snav-link {{ request()->routeIs('panel.staf.aktivitas.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Riwayat Aktivitas
            </a>
        </nav>

        @if(auth()->user()->adalahAdmin())
            <hr class="sidebar-divider">
            <a href="{{ route('panel.admin.beranda') }}" class="snav-link" style="margin: 0 10px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M20 21a8 8 0 1 0-16 0"></path>
                    <path d="m16 11 2 2 4-4"></path>
                </svg>
                Panel Admin
            </a>
        @endif

        <div class="sidebar-footer">Panel Staf OMYU</div>
    </aside>

    {{-- ===== TOPBAR ===== --}}
    <header class="topbar">
        {{-- Mobile: brand logo --}}
        <a href="{{ route('panel.beranda') }}" class="topbar-brand">
            <div class="brand-lockup">
                <div class="brand-logo-row">
                    <img src="{{ asset('images/logo_icon.png') }}" alt="O" class="brand-fruit-icon">
                    <span class="brand-myu">MYU</span>
                </div>
                <div class="brand-tagline">Less Sugar, More Fruits</div>
            </div>
        </a>
        <div class="topbar-spacer"></div>

        {{-- Notifikasi --}}
        <button type="button" class="topbar-btn" id="notifBell" onclick="toggleNotif()" title="Notifikasi">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            @if($notifStafCount > 0)
                <span class="notif-dot" id="notifBadge">{{ $notifStafCount > 9 ? '9+' : $notifStafCount }}</span>
            @endif
        </button>

        {{-- Profil --}}
        <button type="button" class="topbar-btn topbar-avatar" id="profileBtn" onclick="toggleProfile()" title="Profil & Menu">
            @if(auth()->user()->foto_url)
                <img src="{{ auth()->user()->foto_url }}" alt="Foto profil {{ auth()->user()->nama }}">
            @else
                {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
            @endif
        </button>

        {{-- Profile Dropdown --}}
        <div class="dropdown profile-dd" id="profileDd">
            <div class="profile-dd-head">
                <div class="profile-dd-user">
                    <div class="profile-dd-avatar">
                        @if(auth()->user()->foto_url)
                            <img src="{{ auth()->user()->foto_url }}" alt="Foto profil {{ auth()->user()->nama }}">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="profile-dd-name">{{ auth()->user()->nama }}</div>
                        <div class="profile-dd-sub">{{ auth()->user()->email }} &middot; {{ ucfirst(auth()->user()->peran) }}</div>
                    </div>
                </div>
            </div>
            <a href="{{ route('panel.staf.profil.index') }}" class="{{ request()->routeIs('panel.staf.profil.*') ? 'dd-active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                Profil &amp; Ganti Sandi
            </a>
            <hr>
            <a href="{{ route('panel.staf.kasir.index') }}" class="{{ request()->routeIs('panel.staf.kasir.*') ? 'dd-active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 8h12M6 12h4v4H6zM14 12h4M14 16h4"/></svg>
                Kasir POS
            </a>
            <a href="{{ route('panel.staf.aktivitas.index') }}" class="{{ request()->routeIs('panel.staf.aktivitas.*') ? 'dd-active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Riwayat Aktivitas
            </a>
            @if(auth()->user()->adalahAdmin())
                <hr>
                <a href="{{ route('panel.admin.beranda') }}" style="font-weight:800;color:#0f172a;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/><path d="m16 11 2 2 4-4"/></svg>
                    Panel Admin
                </a>
            @endif
            <hr>
            <a href="/">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Halaman Depan
            </a>
            <form method="POST" action="{{ route('keluar') }}">
                @csrf
                <button type="submit" class="dd-logout"
                        data-confirm="Apakah Anda yakin ingin keluar dari panel staf?"
                        data-confirm-title="Keluar dari Panel Staf?"
                        data-confirm-btn="Ya, Keluar"
                        data-confirm-type="danger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Keluar (Logout)
                </button>
            </form>
        </div>

        {{-- Notif Dropdown --}}
        <div class="dropdown notif-dd" id="notifDd">
            <div class="notif-dd-head">
                <span class="notif-head-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </span>
                <div class="notif-head-copy">
                    <div class="notif-dd-title">Notifikasi</div>
                    <div class="notif-dd-sub" id="notifDdSub">
                        {{ $notifStafCount > 0 ? $notifStafCount.' pembaruan perlu perhatian' : 'Semua dalam kondisi aman' }}
                    </div>
                </div>
                @if($notifStafCount > 0)
                    <button type="button" class="notif-clear-btn" onclick="markAllRead()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
                        Tandai dibaca
                    </button>
                @endif
            </div>
            <div class="notif-scroll">
                @forelse($notifikasiStaf as $notif)
                    <a href="{{ $notif['url'] }}" class="notif-row {{ $notif['tone'] }}">
                        <span class="notif-unread"></span>
                        <span class="notif-icon">
                            @switch($notif['tone'])
                                @case('danger')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                    @break
                                @case('warning')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 15 14"></polyline></svg>
                                    @break
                                @case('success')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
                                    @break
                                @default
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="11" x2="12" y2="16"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            @endswitch
                        </span>
                        <div class="notif-row-body">
                            <div class="notif-row-title">{{ $notif['title'] }}</div>
                            <div class="notif-row-text">{{ $notif['teks'] }}</div>
                            <div class="notif-row-meta">
                                <span class="notif-tag">{{ $notif['tag'] }}</span>
                                <span class="notif-time">{{ $notif['waktu'] ? $notif['waktu']->locale('id')->diffForHumans() : '-' }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="notif-empty-box">
                        <div class="notif-empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <p>Semua beres!</p>
                        <span>Pesanan dan stok bahan dalam kondisi aman.</span>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('panel.staf.aktivitas.index') }}" class="notif-footer-link">
                Lihat semua aktivitas
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </header>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="page-wrapper">
        <main class="page-content">
            @if(session('sukses'))
                <div class="alert alert-sukses">✓ {{ session('sukses') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">⚠ {{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">ℹ {{ session('info') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- ===== MOBILE MENU LAINNYA ===== --}}
    <div class="more-backdrop" id="moreBackdrop"></div>
    <div class="more-sheet" id="moreSheet" role="menu" aria-label="Menu Lainnya">
        <div class="more-sheet-head">Menu Lainnya</div>
        <a href="{{ route('panel.staf.kasir.index') }}" class="more-item {{ request()->routeIs('panel.staf.kasir.*') ? 'active' : '' }}" role="menuitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="14" rx="2"></rect>
                <path d="M7 8h10"></path><path d="M7 12h4"></path><path d="M8 18v2"></path><path d="M16 18v2"></path>
            </svg>
            <span class="more-label">Kasir POS</span>
            <svg class="more-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="9 6 15 12 9 18"></polyline></svg>
        </a>
        <a href="{{ route('panel.staf.aktivitas.index') }}" class="more-item {{ request()->routeIs('panel.staf.aktivitas.*') ? 'active' : '' }}" role="menuitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path>
            </svg>
            <span class="more-label">Riwayat Aktivitas</span>
            <svg class="more-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="9 6 15 12 9 18"></polyline></svg>
        </a>
        <a href="{{ route('panel.staf.stok.index') }}" class="more-item {{ request()->routeIs('panel.staf.stok.*') ? 'active' : '' }}" role="menuitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                <path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22V12"></path>
            </svg>
            <span class="more-label">Stok</span>
            <svg class="more-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="9 6 15 12 9 18"></polyline></svg>
        </a>
    </div>

    {{-- ===== MOBILE BOTTOM NAV ===== --}}
    <nav class="bottom-nav">
        <a href="{{ route('panel.beranda') }}" class="bnav-tab {{ request()->routeIs('panel.beranda') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect>
            </svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('panel.staf.pesanan.index') }}" class="bnav-tab {{ request()->routeIs('panel.staf.pesanan.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Pesanan</span>
        </a>
        <button type="button" class="bnav-scan {{ request()->routeIs('panel.staf.pesanan.scan') ? 'active' : '' }}" onclick="bukaScanQrCepat()" aria-label="Scan QR">
            <span class="bnav-scan-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M4 8V6a2 2 0 0 1 2-2h2"></path>
                    <path d="M16 4h2a2 2 0 0 1 2 2v2"></path>
                    <path d="M20 16v2a2 2 0 0 1-2 2h-2"></path>
                    <path d="M8 20H6a2 2 0 0 1-2-2v-2"></path>
                    <path d="M8 8h8v8H8z"></path>
                </svg>
            </span>
            <span>Scan</span>
        </button>
        <a href="{{ route('panel.staf.produksi.index') }}" class="bnav-tab {{ request()->routeIs('panel.staf.produksi.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M7 2h10l-2 11a4 4 0 0 1-4 3h0a4 4 0 0 1-4-3L5 2z"></path>
                <path d="M10 16v4"></path><path d="M7 20h10"></path>
            </svg>
            <span>Produksi</span>
        </a>
        <button type="button" id="moreBtn" class="bnav-tab {{ request()->routeIs('panel.staf.kasir.*', 'panel.staf.aktivitas.*', 'panel.staf.stok.*') ? 'active' : '' }}" onclick="toggleMenuLainnya()" aria-controls="moreSheet" aria-expanded="false" aria-haspopup="menu" aria-label="Menu Lainnya">
            <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                <circle cx="5" cy="12" r="1.8"></circle><circle cx="12" cy="12" r="1.8"></circle><circle cx="19" cy="12" r="1.8"></circle>
            </svg>
            <span>Lainnya</span>
        </button>
    </nav>

    <button type="button" class="help-fab" id="helpFab" onclick="toggleHelp()" aria-label="Buka pusat bantuan" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M9.6 9a2.6 2.6 0 1 1 3.5 2.5c-.8.3-1.1.9-1.1 1.7"></path><path d="M12 17h.01"></path></svg>
    </button>
    <div class="help-panel" id="helpPanel" role="dialog" aria-label="Pusat Bantuan">
        <div class="help-panel-head">
            <div class="help-panel-title">
                <span class="help-panel-badge">?</span>
                <div>
                    <strong>Pusat Bantuan</strong>
                    <small>Bantuan cepat untuk staf</small>
                </div>
            </div>
            <button type="button" class="help-close" onclick="tutupHelp()" aria-label="Tutup bantuan">&times;</button>
        </div>
        <div class="help-section-label">Panduan cepat</div>
        <div class="help-links">
            <a href="{{ route('panel.staf.kasir.index') }}" class="help-link" onclick="tutupHelp()">Kasir &amp; transaksi baru</a>
            <a href="{{ route('panel.staf.pesanan.scan') }}" class="help-link" onclick="tutupHelp()">Scan QR pengambilan</a>
            <a href="{{ route('panel.staf.produksi.index') }}" class="help-link" onclick="tutupHelp()">Antrean produksi</a>
            <a href="{{ route('panel.staf.stok.index') }}" class="help-link" onclick="tutupHelp()">Cek &amp; catat stok</a>
            <a href="{{ route('panel.staf.profil.index') }}" class="help-link" onclick="tutupHelp()">Foto &amp; kata sandi akun</a>
        </div>
        <div class="help-section-label">Pertanyaan umum</div>
        <div class="help-faq">
            <details>
                <summary>Stok bahan habis?</summary>
                <p>Buka menu Stok, cek status kritis, lalu catat pasokan masuk. Bahan rusak dicatat lewat form Pembuangan agar muncul di riwayat aktivitas.</p>
            </details>
        </div>
        <div class="help-panel-foot">
            <a href="{{ route('panel.staf.bantuan.index') }}" class="help-primary" onclick="tutupHelp()">Pusat Bantuan</a>
            <a href="https://wa.me/6285807106570?text={{ rawurlencode('Halo Admin OMYU, saya butuh bantuan panel staf') }}" target="_blank" rel="noopener" class="help-secondary" onclick="tutupHelp()">Hubungi Admin</a>
        </div>
    </div>

    <script>
        function toggleHelp() {
            const panel = document.getElementById('helpPanel');
            const fab = document.getElementById('helpFab');
            const buka = !panel.classList.contains('show');
            panel.classList.toggle('show', buka);
            fab.setAttribute('aria-expanded', buka ? 'true' : 'false');
        }
        function tutupHelp() {
            document.getElementById('helpPanel').classList.remove('show');
            document.getElementById('helpFab').setAttribute('aria-expanded', 'false');
        }
        function toggleMenuLainnya() {
            const sheet = document.getElementById('moreSheet');
            const backdrop = document.getElementById('moreBackdrop');
            const button = document.getElementById('moreBtn');
            const buka = !sheet.classList.contains('show');

            sheet.classList.toggle('show', buka);
            backdrop.classList.toggle('show', buka);
            button.setAttribute('aria-expanded', buka ? 'true' : 'false');

            if (buka) {
                document.getElementById('profileDd').classList.remove('show');
                document.getElementById('notifDd').classList.remove('show');
                tutupHelp();
            }
        }
        function tutupMenuLainnya() {
            document.getElementById('moreSheet').classList.remove('show');
            document.getElementById('moreBackdrop').classList.remove('show');
            document.getElementById('moreBtn').setAttribute('aria-expanded', 'false');
        }
        function toggleProfile() {
            document.getElementById('notifDd').classList.remove('show');
            document.getElementById('profileDd').classList.toggle('show');
        }
        function toggleNotif() {
            document.getElementById('profileDd').classList.remove('show');
            document.getElementById('notifDd').classList.toggle('show');
        }
        function bukaScanQrCepat() {
            const tujuan = '{{ route('panel.staf.pesanan.scan') }}';

            if (!window.StafScanner || typeof window.StafScanner.bukaQr !== 'function') {
                window.location.href = tujuan;
                return;
            }

            window.StafScanner.bukaQr({
                judul: 'Scan QR Pengambilan',
                pesanAwal: 'Arahkan kamera ke QR pengambilan pesanan...',
                onResult: function (token) {
                    window.location.href = tujuan + '?token=' + encodeURIComponent(token);
                }
            });
        }
        function markAllRead() {
            document.querySelectorAll('#notifDd .notif-row').forEach(r => {
                r.classList.add('read');
                const unread = r.querySelector('.notif-unread');
                if (unread) unread.remove();
            });
            localStorage.setItem('omyu_notif_read', '{{ $notifStafCount }}');
            const badge = document.getElementById('notifBadge');
            if (badge) badge.style.display = 'none';
            const sub = document.getElementById('notifDdSub');
            if (sub) sub.textContent = 'Semua notifikasi sudah dibaca';
            const btn = document.querySelector('#notifDd .notif-clear-btn');
            if (btn) btn.remove();
        }
        // Auto-mark if already read in this session
        (function () {
            const r = parseInt(localStorage.getItem('omyu_notif_read') || '0');
            if ({{ $notifStafCount }} <= r && {{ $notifStafCount }} > 0) markAllRead();
        })();
        // Close dropdowns on outside click
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#profileDd') && !e.target.closest('#profileBtn') && !e.target.closest('.bnav-tab')) {
                document.getElementById('profileDd').classList.remove('show');
            }
            if (!e.target.closest('#notifDd') && !e.target.closest('#notifBell')) {
                document.getElementById('notifDd').classList.remove('show');
            }
            if (!e.target.closest('#helpPanel') && !e.target.closest('#helpFab')) {
                tutupHelp();
            }
            if (!e.target.closest('#moreSheet') && !e.target.closest('#moreBtn')) {
                tutupMenuLainnya();
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            tutupHelp();
            tutupLightbox();
            tutupMenuLainnya();
            document.getElementById('profileDd').classList.remove('show');
            document.getElementById('notifDd').classList.remove('show');
        });

        /* Global Toast Helper */
        window.tampilkanToast = function(pesan, tipe = 'sukses', durasi = 3200) {
            const container = document.getElementById('omyuToastContainer');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = 'omyu-toast ' + tipe;
            const icon = tipe === 'sukses' ? '✓' : (tipe === 'error' ? '⚠' : 'ℹ');
            toast.innerHTML = `<span style="font-size:16px;">${icon}</span> <span>${pesan}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => toast.remove(), 250);
            }, durasi);
        };

        /* Copy to clipboard helper */
        window.salinTeks = function(teks, label = 'Teks berhasil disalin!') {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(teks).then(() => tampilkanToast(label, 'sukses'));
            } else {
                const t = document.createElement('textarea');
                t.value = teks;
                t.style.position = 'fixed';
                t.style.opacity = '0';
                document.body.appendChild(t);
                t.select();
                try {
                    document.execCommand('copy');
                    tampilkanToast(label, 'sukses');
                } catch(err) {}
                t.remove();
            }
        };

        /* Lightbox Preview */
        window.bukaLightbox = function(src, title = 'Bukti Pembayaran') {
            const modal = document.getElementById('omyuLightboxModal');
            const img = document.getElementById('omyuLightboxImg');
            const headTitle = document.getElementById('omyuLightboxTitle');
            if (!modal || !img) return;
            img.src = src;
            if (headTitle) headTitle.textContent = title;
            modal.classList.add('active');
        };

        window.tutupLightbox = function() {
            const modal = document.getElementById('omyuLightboxModal');
            if (modal) modal.classList.remove('active');
        };

        /* Button tactile submit feedback */
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.submitting === 'true') return;
            const btn = form.querySelector('button[type="submit"]:not([data-no-loading])');
            if (btn && !form.target && form.method.toLowerCase() === 'post') {
                const oldHtml = btn.innerHTML;
                btn.classList.add('btn-loading');
                btn.innerHTML = '<span class="spinner-dot"></span> ' + (btn.dataset.loadingText || 'Memproses...');
                setTimeout(() => {
                    btn.innerHTML = oldHtml;
                    btn.classList.remove('btn-loading');
                }, 8000);
            }
        });
    </script>

    <!-- Interactive Toast Container -->
    <div id="omyuToastContainer" class="toast-container" aria-live="polite"></div>

    <!-- Image Lightbox Modal -->
    <div id="omyuLightboxModal" class="lightbox-modal" onclick="if(event.target === this) tutupLightbox()">
        <div class="lightbox-box">
            <div class="lightbox-head">
                <span id="omyuLightboxTitle">Lihat Gambar</span>
                <button type="button" class="lightbox-close" onclick="tutupLightbox()">&times;</button>
            </div>
            <img id="omyuLightboxImg" src="" alt="Pratinjau" class="lightbox-img">
        </div>
    </div>

    <!-- OMYU Modern Interactive Confirmation Modal -->
    <div id="omyuConfirmModal" class="omyu-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" data-soft-refresh-skip>
        <div class="omyu-confirm-backdrop" onclick="window.tutupKonfirmasiModal()"></div>
        <div class="omyu-confirm-box" role="document">
            <button type="button" class="omyu-confirm-close" onclick="window.tutupKonfirmasiModal()" aria-label="Tutup popup">&times;</button>

            <div class="omyu-confirm-icon-wrapper" id="omyuConfirmIconWrapper">
                <div class="omyu-confirm-icon-glow"></div>
                <div class="omyu-confirm-icon-circle" id="omyuConfirmIconCircle">
                    <svg id="omyuConfirmIconSvg" class="omyu-confirm-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                </div>
            </div>

            <h3 class="omyu-confirm-title" id="omyuConfirmTitle">Konfirmasi</h3>

            <div class="omyu-confirm-code-badge" id="omyuConfirmCodeBadge" style="display:none;">
                <span class="badge-icon">🧾</span>
                <span class="badge-text" id="omyuConfirmCodeText"></span>
            </div>

            <p class="omyu-confirm-message" id="omyuConfirmMessage"></p>

            <div class="omyu-confirm-actions">
                <button type="button" class="omyu-confirm-btn-cancel" id="omyuConfirmBtnCancel" onclick="window.tutupKonfirmasiModal()">
                    Batal
                </button>
                <button type="button" class="omyu-confirm-btn-submit" id="omyuConfirmBtnSubmit">
                    <span id="omyuConfirmBtnSubmitText">Ya, Lanjutkan</span>
                </button>
            </div>
        </div>
    </div>
    <!-- OMYU Staf V35 Interactive Scripts -->
    <script src="{{ asset('js/staf-anim.js') }}?v={{ filemtime(public_path('js/staf-anim.js')) }}"></script>
    @yield('scripts')
    @stack('scripts')
    @unless(request()->routeIs('panel.staf.pesanan.scan'))
        <script src="{{ asset('js/soft-refresh.js') }}?v={{ filemtime(public_path('js/soft-refresh.js')) }}"></script>
    @endunless
</body>
</html>
