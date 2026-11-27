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
        
        html {
            background: #eef7e5;
        }

        body {
            background: transparent;
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* BACKGROUND BLUR (sedikit) - ubah angka blur(5px) di bawah ini sesuai selera.
           0 = tanpa blur | 1-2px = sangat halus | 4px+ = makin blur.
           Gambar background dipindah ke ::before supaya konten tetap tajam. */
        body::before {
            content: "";
            position: fixed;
            inset: -15px;
            z-index: -1;
            background: #eef7e5 url("{{ asset('images/background.png') }}") no-repeat center top;
            background-size: cover;
            filter: blur(5px);
            -webkit-filter: blur(5px);
            pointer-events: none;
        }
        @media (min-width: 992px) {
            body {
                min-height: 100vh;
                overflow-x: hidden;
                overflow-y: auto;
            }
        }

        /* Top Scroll Shield (tinggi terlihat 12px, tapi diperbesar 15px ke atas/samping
           supaya blur tidak memudar di pinggir) */
        .top-scroll-shield {
            position: fixed;
            top: -15px;
            left: -15px;
            right: -15px;
            height: 27px;
            z-index: 999;
            background: #eef7e5 url("{{ asset('images/background.png') }}") no-repeat center top fixed;
            background-size: cover;
            filter: blur(5px);
            -webkit-filter: blur(5px);
            pointer-events: none;
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
            background: #d1d1d1f2;
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
            justify-content: flex-start;
            gap: 12px;
        }
        @media (min-width: 992px) {
            .main-wrapper {
                min-height: 100vh;
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

        /* Promo Banner Slider */
        .promo-slider {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            margin-bottom: 4px;
            /* BACKGROUND BANNER - putih semi transparan. Ubah 0.45 sesuai selera:
               0 = transparan penuh, 1 = putih solid. */
            background: rgba(255, 255, 255, 0.59);
            border: none;
            box-shadow: none;
        }
        .promo-track {
            display: flex;
            transition: transform 0.65s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform;
        }
        .promo-slide {
            min-width: 100%;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            align-items: center;
            gap: 16px;
            padding: 10px 8px 28px;
            background: transparent;
        }
        .promo-content {
            padding: 8px 6px;
        }
        .promo-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.4px;
            margin-bottom: 8px;
            background: rgba(255,255,255,0.72);
            color: #166534;
            border: 1px solid rgba(255,255,255,0.75);
        }
        .promo-title {
            font-family: 'Fredoka', 'Nunito', sans-serif;
            font-size: 30px;
            line-height: 1.1;
            font-weight: 900;
            color: #000000;
            margin-bottom: 8px;
        }
        .promo-desc {
            font-size: 13px;
            color: #475569;
            font-weight: 700;
            line-height: 1.55;
            margin-bottom: 14px;
            max-width: 520px;
        }
        .promo-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 900;
            color: #fff;
            background: linear-gradient(135deg, #4d8822 0%, #2f620e 100%);
            box-shadow: 0 6px 16px rgba(47, 98, 14, 0.25);
        }
        .promo-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 180px;
        }
        .promo-image {
            width: 100%;
            max-width: 360px;
            max-height: 220px;
            height: auto;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 10px 22px rgba(25,56,8,0.18));
        }
        
        .promo-dots {
            position: absolute;
            left: 50%;
            bottom: 6px;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 2;
        }
        .promo-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            border: none;
            background: rgba(255,255,255,0.65);
            cursor: pointer;
            padding: 0;
            transition: all .25s ease;
        }
        .promo-dot.active {
            width: 22px;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        @media (max-width: 768px) {
            .promo-slide {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 8px 4px 30px;
                text-align: center;
            }
            .promo-content { padding: 0; }
            .promo-title { font-size: 22px; }
            .promo-desc { font-size: 12px; margin: 0 auto 12px; }
            .promo-visual { min-height: 140px; }
            .promo-image { max-height: 170px; max-width: 280px; }
        }

        /* Menu Favorit (Paling Banyak Dipesan) */
        .favorit-section {
            margin-top: 22px;
            padding: 18px 20px 22px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.75);
            box-shadow: 0 8px 30px rgba(25, 56, 8, 0.06);
        }
        .favorit-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }
        .favorit-badge {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 999px;
            background: rgba(223, 242, 200, 0.9);
            color: #166534;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }
        .favorit-title {
            font-family: 'Fredoka', 'Nunito', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: #183d12;
            line-height: 1.15;
        }
        .favorit-subtitle {
            margin-top: 3px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
        }
        .favorit-lihat {
            flex-shrink: 0;
            font-size: 12px;
            font-weight: 900;
            color: #4d8822;
            text-decoration: none;
            white-space: nowrap;
        }
        .favorit-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .favorit-card {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 12px;
            border-radius: 18px;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(223, 242, 200, 0.9);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .favorit-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 26px rgba(25, 56, 8, 0.12);
        }
        .favorit-img-box {
            height: 110px;
            border-radius: 14px;
            overflow: hidden;
            background: #eef7e5;
        }
        .favorit-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .favorit-rank {
            position: absolute;
            top: 18px;
            left: 18px;
            padding: 4px 9px;
            border-radius: 999px;
            background: linear-gradient(135deg, #4d8822 0%, #2f620e 100%);
            color: #ffffff;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.3px;
        }
        .favorit-nama {
            margin-top: 10px;
            min-height: 34px;
            font-size: 13px;
            font-weight: 900;
            line-height: 1.3;
            color: #0f172a;
        }
        .favorit-harga {
            margin-top: 3px;
            font-size: 13px;
            font-weight: 900;
            color: #4d8822;
        }
        .favorit-rating {
            margin-top: 8px;
            font-size: 10px;
            font-weight: 800;
            color: #f59e0b;
        }
        .favorit-meta {
            margin-top: 9px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
        }
        .favorit-pesan {
            padding: 4px 9px;
            border-radius: 999px;
            background: #dff2c8;
            color: #183d12;
            white-space: nowrap;
        }
        .favorit-order-form {
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .favorit-order-form .favorit-pesan {
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
        }
        .favorit-order-form .favorit-pesan:hover { background: #c9e9a6; }
        .favorit-order-form .favorit-pesan:disabled { opacity: 0.5; cursor: not-allowed; }
        .qty-control {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            white-space: nowrap;
        }
        .qty-control input {
            width: 62px;
            height: 30px;
            padding: 4px 6px;
            border: 1px solid #d7e8c4;
            border-radius: 10px;
            background: #ffffff;
            color: #183d12;
            font-family: inherit;
            font-size: 12px;
            font-weight: 900;
            text-align: center;
        }
        .favorit-stok {
            color: #4d8822;
        }
        .favorit-habis {
            margin-top: 10px;
            font-size: 11px;
            font-weight: 800;
            color: #dc2626;
        }
        .favorit-kosong {
            grid-column: 1 / -1;
            padding: 28px 16px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 16px;
        }
        @media (max-width: 900px) {
            .favorit-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 520px) {
            .favorit-section { padding: 16px 12px 18px; }
            .favorit-grid { gap: 10px; }
            .favorit-img-box { height: 90px; }
            .favorit-title { font-size: 20px; }
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
        .mutu-sosmed {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
        }
        .mutu-sosmed-label {
            width: 100%;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.3px;
            color: #193808;
        }
        .sosmed-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid #d4ebba;
            color: #2f4f16;
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
            transition: transform .18s ease, background .18s ease;
        }
        .sosmed-link:hover {
            background: #ffffff;
            transform: translateY(-2px);
        }
        .sosmed-link svg {
            flex-shrink: 0;
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
                scroll-padding-top: 76px;
                scroll-padding-bottom: 90px;
            }
            section[id] {
                scroll-margin-top: 76px;
                scroll-margin-bottom: 90px;
            }
            .topbar-wrapper {
                padding: 0 12px;
                top: 8px;
            }
            .fixed-topbar {
                height: 48px;
                padding: 0 12px;
                border-radius: 30px;
                background: rgba(255, 255, 255, 0.96);
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
                left: 14px !important;
                right: 14px !important;
                width: auto !important;
                max-width: 420px !important;
                margin: 0 auto !important;
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
                transform: none !important;
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
                padding: 76px 14px calc(100px + env(safe-area-inset-bottom, 0px));
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
            .mutu-sosmed {
                gap: 6px;
                margin-top: 10px;
            }
            .sosmed-link {
                padding: 5px 8px;
                font-size: 9px;
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
    <link rel="stylesheet" href="{{ asset('css/pelanggan-anim.css') }}?v={{ filemtime(public_path('css/pelanggan-anim.css')) }}">
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
                <a href="/" class="nav-item active" data-menu-aktif="beranda">
                    Beranda
                </a>
                <a href="{{ route('pelanggan.katalog') }}" class="nav-item">
                    Menu
                </a>
                <a href="#tentang-kami" class="nav-item" data-menu-aktif="tentang-kami">
                    Tentang Kami
                </a>
                <a href="https://wa.me/6285807106570?text=Halo%20OMYU,%20saya%20ingin%20tanya%20menu" target="_blank" class="nav-item">
                    Kontak
                </a>
                <a href="{{ route('pelanggan.lacak') }}" class="nav-item {{ request()->routeIs('pelanggan.lacak') ? 'active' : '' }}">
                    Lacak Pesanan
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

                
            </div>

            <div class="hero-right">
                <img src="{{ asset('images/es.png') }}" alt="OMYU Jus Buah Segar" class="es-img">
                <div class="doodle-tag">
                    Segar<br>Setiap Saat!
                </div>
            </div>
        </section>

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


                <!-- GANTI GAMBAR BANNER (SISI KANAN) DI SINI:
             1. Taruh file gambar di: public/images/banner/
                Contoh: banner-1.png, banner-2.png, banner-3.png
             2. Ganti hanya src pada <img class="promo-image"> di tiap slide.
             3. Teks kiri tetap, gambar yang diganti hanya sisi kanan. -->
        <section class="promo-slider" id="promoSlider" aria-label="Promo OMYU">
            <div class="promo-track" id="promoTrack">
                <div class="promo-slide">
                    <div class="promo-content">
                        <span class="promo-badge">🍊 PROMO SEGAR</span>
                        <h2 class="promo-title">Segar Setiap Hari, 100% Buah Asli</h2>
                        <p class="promo-desc">Pilih menu favoritmu dan rasakan jus segar tanpa pemanis buatan. Dibuat saat dipesan, langsung fresh!</p>
                        <a href="{{ route('pelanggan.katalog') }}" class="promo-cta">Lihat Menu <span>→</span></a>
                    </div>
                    <div class="promo-visual">
                        <img class="promo-image" src="{{ asset('images/banner/banner1.png') }}" alt="Banner Promo 1">
                    </div>
                </div>

                <div class="promo-slide">
                    <div class="promo-content">
                        <span class="promo-badge">🔥 FAVORIT PELANGGAN</span>
                        <h2 class="promo-title">Pesan Langsung, Lebih Cepat!</h2>
                        <p class="promo-desc">Klik menu, lihat detailnya, lalu pilih <strong>Tambah Keranjang</strong> atau <strong>Pesan Langsung</strong>.</p>
                        <a href="{{ route('pelanggan.katalog') }}" class="promo-cta">Pesan Sekarang <span>→</span></a>
                    </div>
                    <div class="promo-visual">
                        <img class="promo-image" src="{{ asset('images/banner/banner2.png') }}" alt="Banner Promo 2">
                    </div>
                </div>

                <div class="promo-slide">
                    <div class="promo-content">
                        <span class="promo-badge">💚 SEHAT & PRAKTIS</span>
                        <h2 class="promo-title">Ambil di Outlet, Tanpa Ribet</h2>
                        <p class="promo-desc">Pesan online, bayar, lalu ambil pesananmu di outlet OMYU. Cocok buat yang aktif dan butuh minuman sehat.</p>
                        <a href="{{ route('pelanggan.lacak') }}" class="promo-cta">Lacak Pesanan <span>→</span></a>
                    </div>
                    <div class="promo-visual">
                        <img class="promo-image" src="{{ asset('images/banner/banner3.png') }}" alt="Banner Promo 3">
                    </div>
                </div>
            </div>
            <div class="promo-dots" id="promoDots" aria-label="Navigasi banner">
                <button type="button" class="promo-dot active" data-slide="0" aria-label="Banner 1"></button>
                <button type="button" class="promo-dot" data-slide="1" aria-label="Banner 2"></button>
                <button type="button" class="promo-dot" data-slide="2" aria-label="Banner 3"></button>
            </div>
        </section>

        <!-- Menu Favorit: diurutkan dari jumlah cup pesanan selesai terbanyak -->
        <section class="favorit-section" id="menu-favorit">
            <div class="favorit-header">
                <div>
                    <span class="favorit-badge">🔥 PALING BANYAK DIPESAN</span>
                    <h2 class="favorit-title">Menu Favorit Pelanggan</h2>
                    <p class="favorit-subtitle">Pilihan terlaris dari pesanan yang sudah selesai. Langsung pesan favoritmu!</p>
                </div>
                <a href="{{ route('pelanggan.katalog') }}" class="favorit-lihat">Lihat semua menu &rarr;</a>
            </div>

            <div class="favorit-grid">
                @forelse($menuFavorit as $menu)
                    @php
                        $gambarUrl = $menu->gambarUrl(600);
                        $terjual = (int) ($menu->jumlah_terjual ?? 0);
                        $pilihanPotongan = [2000, 3000, 4000, 5000];
                        $potongan = $pilihanPotongan[$menu->id % count($pilihanPotongan)];
                        if ($menu->harga <= 12000 && $potongan > 3000) {
                            $potongan = 2000;
                        }
                        $strikethroughPrice = $menu->harga + $potongan;
                        $hematRibuan = round($potongan / 1000);

                        $ratingScore = $menu->bintangRataRata();
                        $bintangPenuh = (int) round($ratingScore);
                        $bintangTampil = str_repeat('★', $bintangPenuh).str_repeat('☆', 5 - $bintangPenuh);
                    @endphp
                    <div class="favorit-card">
                        @if($loop->first && $terjual > 0)
                            <span class="favorit-rank">#1 TERLARIS</span>
                        @endif
                        <div class="favorit-img-box">
                            <img src="{{ $gambarUrl }}" alt="{{ $menu->nama }}" loading="lazy">
                            @if($menu->stok > 0)
                                <div class="product-img-overlay-btn" onclick="event.stopPropagation();">
                                    <button type="button" class="btn-add-cart-enhanced btn-img-add-cart" onclick="window.tambahKeranjangAnimasi(this, event)">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="21" r="1"></circle>
                                            <circle cx="20" cy="21" r="1"></circle>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                        </svg>
                                        <span>+ Keranjang</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="favorit-nama">{{ $menu->nama }}</div>
                        <div class="product-price-row" style="margin-bottom:4px;">
                            <span class="product-price-main">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <span class="product-price-strike">Rp {{ number_format($strikethroughPrice, 0, ',', '.') }}</span>
                            <span class="product-discount-tag">Hemat {{ $hematRibuan }}rb</span>
                        </div>
                        <div class="favorit-rating">{{ $bintangTampil }} {{ number_format($ratingScore, 1, ',', '.') }} ({{ $menu->jumlahUlasan() }} ulasan)</div>
                        <div class="favorit-meta">
                            <span>{{ $terjual > 0 ? $terjual.'x dipesan' : 'Menu pilihan' }}</span>
                            <span class="favorit-stok">Stok: {{ $menu->stok }} cup</span>
                        </div>
                        @if($menu->stok > 0)
                            <form method="POST" action="{{ route('pelanggan.keranjang.tambah') }}" class="favorit-order-form">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $menu->id }}">
                                <input type="hidden" name="langsung" value="1">
                                <label class="qty-control">
                                    <span>Jumlah</span>
                                    <input type="number" name="qty" value="1" min="1" max="{{ $menu->stok }}" inputmode="numeric" aria-label="Jumlah {{ $menu->nama }}">
                                </label>
                                <button type="submit" class="favorit-pesan">Pesan Langsung</button>
                            </form>
                        @else
                            <div class="favorit-habis">Stok Habis</div>
                        @endif
                    </div>
                @empty
                    <div class="favorit-kosong">Belum ada menu favorit untuk ditampilkan.</div>
                @endforelse
            </div>
        </section>

        <!-- Features Bar (3 Pills) -->
        
        

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

                    <!-- SOSIAL MEDIA - GANTI href DAN @handle DI BAWAH INI SESUAI AKUN OMYU -->
                    <div class="mutu-sosmed">
                        <span class="mutu-sosmed-label">IKUTI KAMI:</span>
                        <a href="https://www.instagram.com/omyu.official/" target="_blank" rel="noopener noreferrer" class="sosmed-link">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#d9466a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.5" cy="6.5" r="1" fill="#d9466a" stroke="none"></circle></svg>
                            &#64;omyu.official
                        </a>
                        <a href="https://www.facebook.com/omyu.official/" target="_blank" rel="noopener noreferrer" class="sosmed-link">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="#1877f2"><path d="M13 22v-9h3l.5-3.5H13V7.2c0-1 .3-1.7 1.8-1.7H17V2.3c-.3 0-1.4-.1-2.6-.1-2.6 0-4.4 1.6-4.4 4.5V9.5H7V13h3v9z"></path></svg>
                            OMYU Official
                        </a>
                        <a href="https://www.tiktok.com/@omyu.official" target="_blank" rel="noopener noreferrer" class="sosmed-link">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="#111827"><path d="M16.5 2c.3 2.2 1.6 3.6 3.8 3.9v3c-1.5.1-2.9-.4-4.1-1.2v6.6c0 4.2-3.4 7.6-7.6 7.6S1 18.5 1 14.3s3.4-7.6 7.6-7.6c.5 0 1 0 1.5.1v3.1c-.5-.2-1-.3-1.5-.3-2.6 0-4.6 2.1-4.6 4.7s2 4.7 4.6 4.7 4.6-2.1 4.6-4.7V2h3.8z"></path></svg>
                            &#64;omyu.official
                        </a>
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
        <a href="/" class="mobile-nav-item active" data-menu-aktif="beranda">
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
        <a href="#tentang-kami" class="mobile-nav-item" data-menu-aktif="tentang-kami">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span class="mobile-nav-label">Tentang Kami</span>
        </a>
        <a href="{{ route('pelanggan.lacak') }}" class="mobile-nav-item {{ request()->routeIs('pelanggan.lacak') ? 'active' : '' }}">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                <path d="M8 11h6"></path>
            </svg>
            <span class="mobile-nav-label">Lacak</span>
        </a>
        <a href="https://wa.me/6285807106570?text=Halo%20OMYU,%20saya%20ingin%20tanya%20menu" target="_blank" class="mobile-nav-item">
            <svg class="mobile-nav-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
            </svg>
            <span class="mobile-nav-label">Kontak</span>
        </a>
    </nav>

    <script src="{{ asset('js/pelanggan-anim.js') }}?v={{ filemtime(public_path('js/pelanggan-anim.js')) }}"></script>
    <script src="{{ asset('js/soft-refresh.js') }}?v={{ filemtime(public_path('js/soft-refresh.js')) }}"></script>
    <script>
        (function () {
            const track = document.getElementById('promoTrack');
            const slider = document.getElementById('promoSlider');
            if (!track || !slider) return;
            const slides = Array.from(track.children);
            const dots = Array.from(document.querySelectorAll('.promo-dot'));
            let index = 0;
            let timer = null;

            function render() {
                track.style.transform = 'translateX(-' + (index * 100) + '%)';
                dots.forEach((d, i) => d.classList.toggle('active', i === index));
            }
            function goTo(i) {
                index = (i + slides.length) % slides.length;
                render();
            }
            function start() {
                stop();
                timer = setInterval(() => goTo(index + 1), 4000);
            }
            function stop() {
                if (timer) clearInterval(timer);
                timer = null;
            }

            dots.forEach((dot) => dot.addEventListener('click', () => { goTo(parseInt(dot.dataset.slide, 10) || 0); start(); }));
            slider.addEventListener('mouseenter', stop);
            slider.addEventListener('mouseleave', start);
            slider.addEventListener('touchstart', stop, { passive: true });
            slider.addEventListener('touchend', start, { passive: true });

            render();
            start();
        })();

        (function () {
            const menuKontekstual = document.querySelectorAll('[data-menu-aktif]');
            const tautanTentangKami = document.querySelectorAll('a[href="#tentang-kami"]');
            const bagianTentangKami = document.getElementById('tentang-kami');
            let penggunaSudahScroll = konteksSaatIni() === 'tentang-kami';

            function terapkanMenuAktif(konteks) {
                menuKontekstual.forEach((menu) => {
                    const aktif = menu.dataset.menuAktif === konteks;
                    menu.classList.toggle('active', aktif);

                    if (aktif) {
                        menu.setAttribute('aria-current', 'location');
                    } else {
                        menu.removeAttribute('aria-current');
                    }
                });
            }

            function konteksSaatIni() {
                return window.location.hash === '#tentang-kami' ? 'tentang-kami' : 'beranda';
            }

            function konteksDariPosisiScroll() {
                const batas = bagianTentangKami.getBoundingClientRect();
                const terlihat = batas.top < window.innerHeight - 100 && batas.bottom > 80;

                return terlihat ? 'tentang-kami' : 'beranda';
            }

            terapkanMenuAktif(konteksSaatIni());

            window.addEventListener('scroll', () => {
                if (window.scrollY > 0) {
                    penggunaSudahScroll = true;
                }
            }, { passive: true });

            window.addEventListener('hashchange', () => {
                penggunaSudahScroll = true;
                terapkanMenuAktif(konteksSaatIni());
            });

            tautanTentangKami.forEach((tautan) => {
                tautan.addEventListener('click', () => {
                    penggunaSudahScroll = true;
                    terapkanMenuAktif('tentang-kami');
                });
            });

            if (!bagianTentangKami) {
                return;
            }

            if (!('IntersectionObserver' in window)) {
                window.addEventListener('scroll', () => {
                    if (penggunaSudahScroll) {
                        terapkanMenuAktif(konteksDariPosisiScroll());
                    }
                }, { passive: true });
                return;
            }

            const pengamatBagian = new IntersectionObserver((entries) => {
                if (!penggunaSudahScroll) {
                    return;
                }

                terapkanMenuAktif(entries[0].isIntersecting ? 'tentang-kami' : 'beranda');
            }, { rootMargin: '-80px 0px -100px 0px', threshold: 0.2 });

            pengamatBagian.observe(bagianTentangKami);
        })();
    </script>
</body>
</html>
