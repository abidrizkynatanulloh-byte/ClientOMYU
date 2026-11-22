<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMYU - Healthy Drink, Happy Life</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700;800;900&family=Nunito:wght@700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif; }
        
        body {
            background: #eef7e5 url("{{ asset('images/background.png') }}") no-repeat center top fixed;
            background-size: cover;
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }
        @media (min-width: 992px) {
            body {
                height: 100vh;
                overflow: hidden; /* Pas 1 layar full di desktop tanpa perlu scroll */
            }
        }

        /* Top Scroll Shield */
        .top-scroll-shield {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 12px;
            z-index: 999;
            background: #eef7e5 url("{{ asset('images/background.png') }}") no-repeat center top fixed;
            background-size: cover;
        }

        /* Floating Capsule Topbar (Lebih Compact & Elegan) */
        .topbar-wrapper {
            position: fixed;
            top: 5px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            z-index: 1000;
            padding: 0 24px;
        }

        .fixed-topbar {
            width: 98%;
            max-width: 1180px;
            height: 52px;
            background: #d1d1d1ba;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px 0 16px;
            box-shadow: 0 8px 25px rgba(25, 56, 8, 0.1), 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #ffffff00;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 0;
            text-decoration: none;
            color: #183d12;
        }
        .brand-logo-img {
            height: 38px;
            width: auto;
            object-fit: contain;
            transition: transform 0.25s ease;
            filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.06));
            flex-shrink: 0;
        }
        .brand-container:hover .brand-logo-img {
            transform: rotate(8deg) scale(1.08);
        }
        .brand-infinity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #a2db67;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38761d;
            box-shadow: 0 2px 8px rgba(56, 118, 29, 0.12);
            transition: all 0.25s ease;
            flex-shrink: 0;
        }
        .brand-container:hover .brand-infinity-icon {
            transform: rotate(15deg) scale(1.06);
            color: #193808;
            border-color: #4d8822;
        }
        .brand-text {
            font-family: 'Fredoka', 'Nunito', 'Segoe UI', sans-serif;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #183d12;
            line-height: 1;
            margin-left: -20px; /* ← ATUR JARAK DI SINI (minus = tumpuk ke logo, 0 = rapet, positif = jauh) */
            margin-top: 6px;
        }
        .brand-logo {
            height: 48px;
            object-fit: contain;
        }

        /* Center Nav Menu */
        .nav-center {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-item {
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            color: #1e3f16;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 30px;
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            color: #2f620e;
            background: rgba(223, 242, 200, 0.45);
        }
        .nav-item.active {
            background: #dff2c8;
            color: #183d12;
            font-weight: 800;
            box-shadow: 0 2px 8px rgba(70, 126, 26, 0.16);
        }
        .mobile-bottom-nav {
            display: none !important;
        }

        .web-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            flex-shrink: 0;
        }

        /* Right Actions */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-pill {
            background: #f1f5f9a9;
            border: 1px solid #000000;
            border-radius: 30px;
            padding: 5px 14px;
            display: flex;
            align-items: center;
            width: 175px;
            transition: all 0.2s;
        }
        .search-pill:focus-within {
            border-color: #4d8822;
            background: #ffffff;
            box-shadow: 0 0 0 2px rgba(77, 136, 34, 0.15);
            width: 205px;
        }
        .search-pill input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 11.5px;
            color: #334155;
            width: 100%;
        }
        .search-pill .search-icon {
            font-size: 12px;
            color: #64748b;
            cursor: pointer;
            border: none;
            background: none;
        }

        .icon-circle-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffc4;
            border: 1px solid #0000009d;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #1e293b;
            font-size: 14px;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.04);
            transition: all 0.2s;
        }
        .icon-circle-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        .cart-red-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background: #ef4444;
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            border-radius: 50%;
            width: 15px;
            height: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #ffffff;
        }

        /* Main Container (Pas 1 Layar Penuh di Desktop) */
        .main-wrapper {
            max-width: 1180px;
            margin: 0 auto;
            padding: 72px 24px 16px;
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        @media (min-width: 992px) {
            .main-wrapper {
                height: 100vh;
                padding-bottom: 20px;
            }
        }

        /* Hero Section */
        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            align-items: center;
            position: relative;
            margin-bottom: 12px;
        }
        @media (max-width: 992px) {
            .hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
                margin-bottom: 20px;
            }
            .hero-left {
                align-items: center;
                padding-right: 0;
            }
            .hero-headline {
                align-items: center;
            }
        }

        .hero-left {
            padding-right: 16px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .pure-fruit-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(180deg, #cbeaa0 0%, #b8e285 100%);
            color: #173f11;
            font-size: 11px;
            font-weight: 900;
            padding: 5px 14px;
            border-radius: 30px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
            border: 1.5px solid #a2db67;
            box-shadow: 0 3px 8px rgba(77, 136, 34, 0.15);
            transform: rotate(-3deg);
            transform-origin: left center;
        }
        .pure-fruit-pill svg {
            flex-shrink: 0;
        }

        .hero-headline {
            font-family: 'Fredoka', 'Nunito', 'Segoe UI', sans-serif;
            font-size: 44px;
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -0.5px;
            margin-bottom: 14px;
            transform: rotate(-2.5deg);
            transform-origin: left center;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }
        @media (max-width: 768px) {
            .hero-headline { font-size: 30px; }
        }

        .hl-line {
            display: inline-flex;
            align-items: center;
            position: relative;
        }
        .hl-dark {
            color: #183d12;
            text-shadow: 0 1px 2px rgba(24, 61, 18, 0.1);
        }
        .hl-light {
            color: #318500;
            display: inline-flex;
            align-items: center;
            text-shadow: 0 1px 2px rgba(91, 181, 38, 0.15);
            margin-left: 24px;
        }

        /* 3 Splash rays on right of Rendah Gula, */
        .splash-rays {
            display: inline-flex;
            align-items: center;
            margin-left: 8px;
            transform: translateY(-8px);
        }

        .hero-subtitle {
            font-size: 13.5px;
            color: #1a3812;
            line-height: 1.52;
            margin-bottom: 16px;
            max-width: 460px;
            font-weight: 600;
            position: relative;
        }
        @media (max-width: 992px) {
            .hero-subtitle { margin: 0 auto 18px; }
        }

        .brush-swoosh-wrap {
            display: inline-block;
            position: relative;
        }
        .brush-swoosh-svg {
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 100%;
            height: 8px;
            pointer-events: none;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        @media (max-width: 992px) {
            .hero-actions { justify-content: center; }
        }

        .btn-order-glow {
            background: linear-gradient(135deg, #4d8822 0%, #2f620e 100%);
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            padding: 10px 24px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 6px 18px rgba(47, 98, 14, 0.35);
            transition: all 0.2s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-order-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(47, 98, 14, 0.45);
        }

        .btn-video-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #193808;
            font-size: 12px;
            font-weight: 800;
            transition: 0.2s;
        }
        .play-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            color: #2f620e;
            font-size: 11px;
            padding-left: 2px;
        }
        .btn-video-pill:hover .play-circle {
            background: #4d8822;
            color: #ffffff;
        }

        /* Hero Right Visual (es.png) */
        .hero-right {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .es-img {
            max-height: 250px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            filter: drop-shadow(0 12px 24px rgba(25, 56, 8, 0.22));
            animation: floatJuice 4s ease-in-out infinite;
        }
        @keyframes floatJuice {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .doodle-tag {
            position: absolute;
            top: 5px;
            right: 0px;
            color: #ffffff;
            text-shadow: 0 2px 6px rgba(0,0,0,0.3);
            font-weight: 900;
            font-size: 12px;
            line-height: 1.2;
            text-align: right;
            transform: rotate(4deg);
        }

        /* 3-Pills Keunggulan */
        .features-capsule {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            padding: 10px 28px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.9);
            margin-bottom: 12px;
        }
        @media (max-width: 768px) {
            .features-capsule {
                grid-template-columns: 1fr;
                border-radius: 20px;
                gap: 12px;
                padding: 14px 18px;
            }
        }
        .feat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 2px 14px;
        }
        .feat-item:not(:last-child) {
            border-right: 1px solid #e2e8f0;
        }
        @media (max-width: 768px) {
            .feat-item:not(:last-child) { border-right: none; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
        }
        .feat-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .bg-red-pill { background: #fee2e2; }
        .bg-orange-pill { background: #fef3c7; }
        .bg-yellow-pill { background: #fef08a; }
        .feat-text h4 {
            font-size: 13px;
            font-weight: 850;
            color: #0f172a;
            margin-bottom: 1px;
        }
        .feat-text p {
            font-size: 10.5px;
            color: #64748b;
            font-weight: 600;
        }

        /* Bottom 2-Cards Grid */
        .bottom-cards-grid {
            display: grid;
            grid-template-columns: 1fr 1.35fr;
            gap: 16px;
        }
        @media (max-width: 900px) {
            .bottom-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Card 1: Booth & Info */
        .booth-card {
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.8);
            display: grid;
            grid-template-columns: 100px 1fr;
            position: relative;
        }
        @media (max-width: 600px) {
            .booth-card { grid-template-columns: 1fr; }
        }
        .booth-photo-side {
            background: #0f172a;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            padding: 12px 10px;
        }
        .booth-photo-side img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.65;
        }
        .booth-chalk-text {
            position: relative;
            z-index: 2;
            color: #ffffff;
            font-size: 11.5px;
            font-weight: 800;
            line-height: 1.25;
            font-style: italic;
        }
        .booth-info-side {
            padding: 14px 18px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .booth-info-side h3 {
            font-size: 13.5px;
            font-weight: 850;
            color: #193808;
            margin-bottom: 4px;
        }
        .booth-info-side p {
            font-size: 11px;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 10px;
        }
        .btn-hubungi-wa {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 5px 14px;
            border-radius: 20px;
            text-decoration: none;
            color: #0f172a;
            font-size: 11px;
            font-weight: 800;
            width: fit-content;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            transition: 0.2s;
        }
        .btn-hubungi-wa:hover {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        /* Card 2: Standar Mutu OMYU */
        .mutu-card-box {
            background: #eef7de;
            border: 1px solid #d4ebba;
            border-radius: 18px;
            padding: 14px 20px;
            position: relative;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mutu-content {
            max-width: 440px;
        }
        .mutu-header-title {
            font-size: 13px;
            font-weight: 900;
            color: #193808;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }
        .mutu-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .mutu-item-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 11px;
            line-height: 1.4;
            color: #2f4f16;
        }
        .mutu-item-row .mutu-check {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #2e7d17;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 900;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .mutu-item-row strong {
            color: #143507;
        }

        .seal-badge-box {
            margin-left: 14px;
            flex-shrink: 0;
            text-align: center;
        }
        .seal-circle-svg {
            width: 78px;
            height: 78px;
            background: #205c0d;
            border: 3px dashed #f59e0b;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(32, 92, 13, 0.3);
        }
        .seal-circle-svg .seal-100 {
            font-size: 15px;
            font-weight: 950;
            color: #ffffff;
            line-height: 1;
        }
        .seal-circle-svg .seal-sub {
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #fef08a;
            margin-top: 1px;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            html {
                scroll-behavior: smooth;
            }
            .topbar-wrapper {
                padding: 0 12px;
                top: 8px;
            }
            .fixed-topbar {
                height: 48px;
                padding: 0 12px;
                border-radius: 30px;
                background: rgba(255, 255, 255, 0.88);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.6);
            }
            .brand-logo-img {
                height: 32px;
            }
            .brand-text {
                font-size: 17px;
                margin-left: -16px;
                margin-top: 4px;
            }
            .search-pill {
                width: 140px;
                padding: 4px 10px;
            }
            .search-pill input {
                font-size: 11px;
            }
            .icon-circle-btn {
                width: 32px;
                height: 32px;
            }

            /* Sembunyikan Nav Tengah di Topbar pada Mobile */
            .nav-center {
                display: none !important;
            }

            /* NAV BOTTOM (Floating Dock Melayang di Bagian Bawah Layar HP) */
            .mobile-bottom-nav {
                display: flex !important;
                position: fixed !important;
                bottom: 14px !important;
                bottom: calc(14px + env(safe-area-inset-bottom, 0px)) !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                width: calc(100% - 28px) !important;
                max-width: 420px !important;
                height: 58px !important;
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(20px) !important;
                -webkit-backdrop-filter: blur(20px) !important;
                border-radius: 35px !important;
                padding: 4px 8px !important;
                justify-content: space-around !important;
                align-items: center !important;
                box-shadow: 0 10px 30px rgba(25, 56, 8, 0.22), 0 2px 10px rgba(0, 0, 0, 0.08) !important;
                border: 1.5px solid rgba(255, 255, 255, 0.95) !important;
                z-index: 99999 !important;
                gap: 4px !important;
            }

            .mobile-nav-item {
                flex: 1;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 3px !important;
                padding: 5px 3px !important;
                border-radius: 18px !important;
                text-decoration: none !important;
                color: #4b6343 !important;
                min-width: 0 !important;
                transition: all 0.2s ease !important;
            }

            .mobile-nav-item .mobile-nav-icon {
                width: 20px !important;
                height: 20px !important;
                display: block !important;
                transition: transform 0.2s !important;
            }

            .mobile-nav-item .mobile-nav-label {
                font-size: 10px !important;
                font-weight: 700 !important;
                line-height: 1 !important;
                white-space: nowrap !important;
                letter-spacing: -0.2px !important;
            }

            .mobile-nav-item.active {
                background: #dff2c8 !important;
                color: #183d12 !important;
                font-weight: 800 !important;
                box-shadow: 0 2px 8px rgba(70, 126, 26, 0.18) !important;
            }

            .mobile-nav-item.active .mobile-nav-icon {
                transform: translateY(-1px) !important;
                stroke-width: 2.6 !important;
            }

            /* Ruang bawah untuk konten utama agar tidak tertutup nav bottom */
            .main-wrapper {
                padding: 68px 14px 90px;
                min-height: auto;
                height: auto;
            }

            /* HERO SECTION: Tulisan & Gambar Mengecil Menyesuaikan */
            .hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
                margin-bottom: 14px;
                gap: 8px;
            }
            .hero-left {
                align-items: center;
                padding-right: 0;
            }
            .pure-fruit-pill {
                font-size: 10px;
                padding: 4px 12px;
                margin-bottom: 8px;
            }
            .hero-headline {
                font-size: 25px;
                line-height: 1.15;
                align-items: center;
                transform: none;
                margin-bottom: 8px;
            }
            .hl-line {
                justify-content: center;
            }
            .hl-light {
                margin-left: 0;
            }
            .splash-rays {
                margin-left: 6px;
                transform: translateY(-3px);
            }
            .splash-rays svg {
                width: 22px;
                height: 18px;
            }
            .hero-subtitle {
                font-size: 12px;
                line-height: 1.45;
                margin: 0 auto 12px;
                max-width: 92%;
            }
            .hero-actions {
                justify-content: center;
            }
            .btn-order-glow {
                font-size: 12px;
                padding: 8px 20px;
            }

            /* GAMBAR ES MENYESUAIKAN / MENGECIL */
            .hero-right {
                margin-top: 2px;
            }
            .es-img {
                max-height: 170px;
            }
            .doodle-tag {
                font-size: 10px;
                top: -4px;
                right: 15px;
            }

            /* 3-PILLS KEUNGGULAN MENYESUAIKAN */
            .features-capsule {
                grid-template-columns: 1fr;
                border-radius: 18px;
                gap: 8px;
                padding: 10px 14px;
                margin-bottom: 12px;
            }
            .feat-item {
                gap: 10px;
                padding: 4px 6px;
            }
            .feat-item:not(:last-child) {
                border-right: none;
                border-bottom: 1px solid #f1f5f9;
                padding-bottom: 6px;
            }
            .feat-circle {
                width: 30px;
                height: 30px;
            }
            .feat-circle svg {
                width: 15px;
                height: 15px;
            }
            .feat-text h4 {
                font-size: 12px;
            }
            .feat-text p {
                font-size: 10px;
            }

            /* BOTTOM CARDS GRID: MENYESUAIKAN HP */
            .bottom-cards-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .booth-card {
                grid-template-columns: 85px 1fr;
                border-radius: 16px;
            }
            .booth-photo-side {
                padding: 10px 8px;
            }
            .booth-chalk-text {
                font-size: 10px;
            }
            .booth-info-side {
                padding: 10px 14px;
            }
            .booth-info-side h3 {
                font-size: 12px;
                margin-bottom: 3px;
            }
            .booth-info-side p {
                font-size: 10.5px;
                line-height: 1.35;
                margin-bottom: 8px;
            }
            .btn-hubungi-wa {
                font-size: 10.5px;
                padding: 4px 12px;
            }

            .mutu-card-box {
                flex-direction: row;
                gap: 10px;
                padding: 12px 14px;
                text-align: left;
                border-radius: 16px;
            }
            .mutu-header-title {
                font-size: 11.5px;
                margin-bottom: 6px;
            }
            .mutu-item-row {
                font-size: 10px;
                gap: 6px;
            }
            .mutu-item-row .mutu-check {
                width: 13px;
                height: 13px;
            }
            .seal-badge-box {
                margin-left: 0;
            }
            .seal-circle-svg {
                width: 60px;
                height: 60px;
            }
            .seal-circle-svg .seal-100 {
                font-size: 12.5px;
            }
            .seal-circle-svg .seal-sub {
                font-size: 6.5px;
            }
        }

        /* Extra Small Screens (<= 380px) */
        @media (max-width: 380px) {
            .mobile-bottom-nav {
                height: 52px !important;
                padding: 3px 4px !important;
            }
            .mobile-nav-item {
                padding: 3px 2px !important;
            }
            .mobile-nav-item .mobile-nav-icon {
                width: 17px !important;
                height: 17px !important;
            }
            .mobile-nav-item .mobile-nav-label {
                font-size: 9px !important;
            }
            .hero-headline {
                font-size: 21px;
            }
            .es-img {
                max-height: 140px;
            }
            .search-pill {
                width: 115px;
            }
            .brand-logo-img {
                height: 28px;
            }
            .brand-text {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Pelindung Celah Atas -->
    <div class="top-scroll-shield"></div>

    <!-- Floating Capsule Topbar -->
    <div class="topbar-wrapper">
        <header class="fixed-topbar">
            <!-- Logo OMYU -->
            <a href="/" class="brand-container">
                <img src="{{ asset('images/logo_omyu.png') }}" alt="OMYU" class="brand-logo-img">
                <span class="brand-text">MYU</span>
            </a>

            <!-- Center Nav Links (Desktop Topbar) -->
            <nav class="nav-center">
                <a href="/" class="nav-item active">
                    Beranda
                </a>
                <a href="{{ route('pelanggan.katalog') }}" class="nav-item">
                    Menu
                </a>
                <a href="#tentang-kami" class="nav-item">
                    Tentang Kami
                </a>
                <a href="https://wa.me/6285807106570?text=Halo%20OMYU,%20saya%20ingin%20tanya%20menu" target="_blank" class="nav-item">
                    Kontak
                </a>
            </nav>

            <!-- Right Action Items -->
            <div class="nav-right">
                <!-- Search Input -->
                <form method="GET" action="{{ route('pelanggan.katalog') }}" class="search-pill">
                    <input type="text" name="cari" placeholder="Cari menu favorit...">
                    <button type="submit" class="search-icon" aria-label="Cari">
                        <svg class="web-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>

                <!-- Cart Icon with Badge -->
                <a href="{{ route('pelanggan.keranjang') }}" class="icon-circle-btn" title="Keranjang Belanja">
                    <svg class="web-icon" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-red-badge">{{ count(session('keranjang', [])) }}</span>
                </a>
            </div>
        </header>
    </div>

    <!-- Main Content Area: Pas 1 Layar Penuh -->
    <main class="main-wrapper">

        <!-- Hero Section -->
        <section class="hero-grid">
            <div class="hero-left">
                <!-- 100% Buah Murni Pill with Leaf -->
                <div class="pure-fruit-pill">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="#183d12">
                        <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/>
                    </svg>
                    <span>100% BUAH MURNI</span>
                </div>
                
                <!-- Headline with exact color split and splash doodle -->
                <h1 class="hero-headline">
                    <span class="hl-line hl-dark">Sensasi Segar</span>
                    <span class="hl-line hl-light"> Rendah Gula,
                        <span class="splash-rays">
                            <svg width="32" height="26" viewBox="0 0 32 26" fill="none">
                                <path d="M4 22C6 16 9 10 13 4" stroke="#183d12" stroke-width="3.5" stroke-linecap="round"/>
                                <path d="M17 23C18 16 20 10 23 5" stroke="#415d3d" stroke-width="3.5" stroke-linecap="round"/>
                                <path d="M27 24C28 19 29 15 31 11" stroke="#183d12" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </span>
                    <span class="hl-line hl-dark">Manis Alami.</span>
                </h1>

                <!-- Subtitle with exact 3-line wrap and green brush underline -->
                <p class="hero-subtitle">
                    Perasan buah asli pilihan tanpa pemanis buatan<br>
                    untuk gaya hidup sehat Anda. Pesan online tanpa<br>
                    antre dan ambil di <span class="brush-swoosh-wrap">outlet OMYU!
                        <svg class="brush-swoosh-svg" viewBox="0 0 100 8" fill="none">
                            <path d="M2 5C30 2 70 3 98 5" stroke="#3b7d1e" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </p>

                <div class="hero-actions">
                    <a href="{{ route('pelanggan.katalog') }}" class="btn-order-glow">
                        <svg class="web-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        Pesan Sekarang &rarr;
                    </a>
                </div>
            </div>

            <div class="hero-right">
                <img src="{{ asset('images/es.png') }}" alt="OMYU Jus Buah Segar" class="es-img">
                <div class="doodle-tag">
                    Segar<br>Setiap Saat!
                </div>
            </div>
        </section>

        <!-- Features Bar (3 Pills) -->
        <section class="features-capsule">
            <div class="feat-item">
                <div class="feat-circle bg-red-pill">
                    <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#ef4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"></path>
                        <path d="M10 2c1 .5 2 2 2 5"></path>
                    </svg>
                </div>
                <div class="feat-text">
                    <h4>100% Buah Segar</h4>
                    <p>Tanpa esens sintetis</p>
                </div>
            </div>

            <div class="feat-item">
                <div class="feat-circle bg-orange-pill">
                    <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#f97316" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6l-2 14H8L6 6h12z"></path>
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="10" y1="2" x2="14" y2="6"></line>
                    </svg>
                </div>
                <div class="feat-text">
                    <h4>Atur Manismu</h4>
                    <p>0%, 25%, 50%, Normal</p>
                </div>
            </div>

            <div class="feat-item">
                <div class="feat-circle bg-yellow-pill">
                    <svg class="web-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#ca8a04" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </div>
                <div class="feat-text">
                    <h4>Ambil Kilat</h4>
                    <p>Tanpa antre & ribet</p>
                </div>
            </div>
        </section>

        <!-- Bottom 2-Cards Grid -->
        <section class="bottom-cards-grid">
            <!-- Card 1: Layanan Booth & Informasi -->
            <div class="booth-card" id="kontak">
                <div class="booth-photo-side">
                    <img src="https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=300&auto=format&fit=crop&q=80" alt="Booth OMYU">
                    <div class="booth-chalk-text">
                        Layanan Booth<br>& Informasi
                    </div>
                </div>
                <div class="booth-info-side">
                    <h3>Hubungi & Kunjungi Booth OMYU</h3>
                    <p>
                        Temukan segelas kesegaran alami 100% buah murni di booth kami atau hubungi tim barista kami untuk pesanan katering, acara spesial, dan lainnya.
                    </p>
                    <a href="https://wa.me/6285807106570?text=Halo%20OMYU,%20saya%20ingin%20tanya%20menu" target="_blank" class="btn-hubungi-wa">
                        <svg class="web-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                        Hubungi Kami &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 2: Standar Mutu OMYU -->
            <div class="mutu-card-box" id="tentang-kami">
                <div class="mutu-content">
                    <div class="mutu-header-title">
                        <svg class="web-icon" viewBox="0 0 24 24" width="15" height="15" fill="#f59e0b">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        STANDAR MUTU OMYU
                    </div>
                    <div class="mutu-list">
                        <div class="mutu-item-row">
                            <span class="mutu-check">
                                <svg viewBox="0 0 24 24" width="10" height="10" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                            <div>
                                <strong>Tanpa Sirup Pemanis Buatan:</strong> Rasa manis alami asli dari kematangan buah atau opsi madu alami.
                            </div>
                        </div>
                        <div class="mutu-item-row">
                            <span class="mutu-check">
                                <svg viewBox="0 0 24 24" width="10" height="10" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                            <div>
                                <strong>Higienis Booth Terjaga:</strong> Seluruh buah dicuci bersih dengan ozone water dan peralatan steril.
                            </div>
                        </div>
                        <div class="mutu-item-row">
                            <span class="mutu-check">
                                <svg viewBox="0 0 24 24" width="10" height="10" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                            <div>
                                <strong>Kesegaran Terjamin:</strong> Disajikan langsung dengan bahan berkualitas terbaik.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 100% Natural Seal Badge -->
                <div class="seal-badge-box">
                    <div class="seal-circle-svg">
                        <span class="seal-100">100%</span>
                        <span class="seal-sub">NATURAL</span>
                        <span style="font-size: 7px; letter-spacing: 0.5px; opacity: 0.8;">OMYU JUICE</span>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Floating Bottom Navigation (Mobile Only) -->
    <nav class="mobile-bottom-nav">
        <a href="/" class="mobile-nav-item active">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="mobile-nav-label">Beranda</span>
        </a>
        <a href="{{ route('pelanggan.katalog') }}" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6l-2 14H8L6 6h12z"></path>
                <line x1="4" y1="6" x2="20" y2="6"></line>
                <line x1="10" y1="2" x2="14" y2="6"></line>
            </svg>
            <span class="mobile-nav-label">Menu</span>
        </a>
        <a href="#tentang-kami" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span class="mobile-nav-label">Tentang Kami</span>
        </a>
        <a href="https://wa.me/6285807106570?text=Halo%20OMYU,%20saya%20ingin%20tanya%20menu" target="_blank" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
            </svg>
            <span class="mobile-nav-label">Kontak</span>
        </a>
    </nav>

</body>
</html>
