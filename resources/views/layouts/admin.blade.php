<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8FAF6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Owner Panel') - OMYU Fresh Juice</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_omyu.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS with custom configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#F4FBF0',
                            100: '#E6F7DD',
                            200: '#CEEEBC',
                            300: '#AEE195',
                            400: '#88C957', // OMYU Lime primary
                            500: '#72B540',
                            600: '#5A9431',
                            700: '#457327',
                            800: '#385C22',
                            900: '#1B4332', // Deep forest slate
                            950: '#0C2318',
                        },
                        forest: '#143621',
                        accent: '#FBBF24',
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(27, 67, 50, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02)',
                        'card': '0 10px 30px -4px rgba(27, 67, 50, 0.07)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        html { font-size: 13px; } /* ← SCALE GLOBAL: kecilkan angka ini untuk perkecil semua UI */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F0;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    <!-- OMYU Admin V35 Animations -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="h-full text-slate-800 bg-[#F8FAF6] admin-ambient-bg antialiased selection:bg-brand-300 selection:text-brand-950 overflow-hidden" x-data="{ moreMenuOpen: false }">

    <!-- Page Entrance Reveal Overlay -->
    <div class="admin-page-overlay"></div>

    <div class="h-full h-screen h-[100dvh] overflow-hidden flex flex-col lg:flex-row">

        <!-- Sidebar Navigation (Desktop Only - Disabled on Mobile) -->
        <aside class="hidden lg:flex lg:flex-col w-56 lg:h-screen lg:shrink-0 bg-brand-900 text-white shadow-2xl lg:shadow-none">
            
            <!-- Brand Logo Header -->
            <div class="px-4 py-4 border-b border-brand-800/80 flex items-center justify-between">
                <a href="{{ route('panel.admin.beranda') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md p-1 flex items-center justify-center border border-white/15 shadow-inner transition-transform group-hover:scale-105">
                        <img src="{{ asset('images/logo_omyu.png') }}" alt="OMYU Logo" class="w-full h-full object-contain drop-shadow">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-extrabold tracking-tight text-white font-sans">OMYU</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-brand-400 text-brand-950 uppercase tracking-wider">Owner</span>
                        </div>
                        <p class="text-[10px] text-brand-200 font-medium">Fresh Juice & Healthy Bar</p>
                    </div>
                </a>
            </div>

            <!-- Outlet Status Indicator -->
            <div class="mx-3 mt-3 px-3 py-2 rounded-xl bg-emerald-950/60 border border-emerald-500/20 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <div class="text-xs font-semibold text-emerald-200">Outlet Operasional</div>
                </div>
                <span class="text-[10px] bg-emerald-500/20 text-emerald-300 font-bold px-2 py-0.5 rounded-full">LIVE</span>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="flex-1 px-3 py-3 space-y-4 overflow-y-auto">

                <!-- Modul Utama -->
                <div>
                    <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300/70">
                        Eksekutif & Analitik
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('panel.admin.beranda') }}"
                           class="admin-sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('panel.admin.beranda') ? 'active-pill bg-brand-400 text-brand-950 shadow-md font-bold' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('panel.admin.beranda') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/5 text-brand-300' }}">
                                <i class="fa-solid fa-chart-pie text-sm"></i>
                            </div>
                            <span>Dashboard Utama</span>
                            <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.beranda') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-white/70' }}">O-01</span>
                        </a>
                    </div>
                </div>

                <!-- Modul Operasional -->
                <div>
                    <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300/70">
                        Operasional Menu & Stok
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('panel.admin.penjualan.index') }}"
                           class="admin-sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('panel.admin.penjualan.*') ? 'active-pill bg-brand-400 text-brand-950 shadow-md font-bold' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('panel.admin.penjualan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/5 text-brand-300' }}">
                                <i class="fa-solid fa-receipt text-sm"></i>
                            </div>
                            <span>Penjualan & Pesanan</span>
                            <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.penjualan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-white/70' }}">O-02</span>
                        </a>

                        <a href="{{ route('panel.admin.resep.index') }}"
                           class="admin-sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('panel.admin.resep.*') ? 'active-pill bg-brand-400 text-brand-950 shadow-md font-bold' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('panel.admin.resep.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/5 text-brand-300' }}">
                                <i class="fa-solid fa-blender text-sm"></i>
                            </div>
                            <span>Resep, HPP & Margin</span>
                            <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.resep.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-white/70' }}">O-03</span>
                        </a>

                        <a href="{{ route('panel.admin.bahan.index') }}"
                           class="admin-sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('panel.admin.bahan.*') ? 'active-pill bg-brand-400 text-brand-950 shadow-md font-bold' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('panel.admin.bahan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/5 text-brand-300' }}">
                                <i class="fa-solid fa-boxes-stacked text-sm"></i>
                            </div>
                            <span>Persediaan Bahan</span>
                            <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.bahan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-white/70' }}">O-04</span>
                        </a>
                    </div>
                </div>

                <!-- Modul Keuangan & SDM -->
                <div>
                    <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300/70">
                        Finansial & Karyawan
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('panel.admin.keuangan.laba_rugi') }}"
                           class="admin-sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('panel.admin.keuangan.laba_rugi') ? 'active-pill bg-brand-400 text-brand-950 shadow-md font-bold' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('panel.admin.keuangan.laba_rugi') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/5 text-brand-300' }}">
                                <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                            </div>
                            <span>Laporan Laba Rugi</span>
                            <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.keuangan.laba_rugi') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-white/70' }}">O-05</span>
                        </a>

                        <a href="{{ route('panel.admin.keuangan.buku_kas') }}"
                           class="admin-sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('panel.admin.keuangan.buku_kas') ? 'active-pill bg-brand-400 text-brand-950 shadow-md font-bold' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('panel.admin.keuangan.buku_kas') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/5 text-brand-300' }}">
                                <i class="fa-solid fa-book-bookmark text-sm"></i>
                            </div>
                            <span>Buku Mutasi Kas</span>
                        </a>

                        <a href="{{ route('panel.admin.karyawan.index') }}"
                           class="admin-sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('panel.admin.karyawan.*') ? 'active-pill bg-brand-400 text-brand-950 shadow-md font-bold' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('panel.admin.karyawan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/5 text-brand-300' }}">
                                <i class="fa-solid fa-users-gear text-sm"></i>
                            </div>
                            <span>Manajemen Karyawan</span>
                            <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.karyawan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-white/70' }}">O-09</span>
                        </a>
                    </div>
                </div>

            </nav>

            <!-- User Profile & Logout Section -->
            <div class="p-3 border-t border-brand-800/80 bg-brand-950/40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        @if(auth()->user()->foto_url)
                            <img src="{{ auth()->user()->foto_url }}" alt="Foto profil {{ auth()->user()->nama }}" class="w-10 h-10 rounded-xl object-cover shadow-sm shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-400 to-lime-200 text-brand-950 flex items-center justify-center font-extrabold text-base shadow-sm shrink-0">
                                {{ strtoupper(substr(auth()->user()->nama ?? 'O', 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-white truncate leading-tight">{{ auth()->user()->nama ?? 'Owner OMYU' }}</p>
                            <p class="text-xs text-brand-300/80 truncate">Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('keluar') }}" class="shrink-0">
                        @csrf
                        <button type="submit" 
                                title="Keluar dari Sistem"
                                onclick="return confirm('Apakah Anda yakin ingin keluar dari panel admin?')"
                                class="w-9 h-9 rounded-xl bg-white/10 hover:bg-rose-500/20 hover:text-rose-400 text-white/70 flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">

            <!-- Top Header Bar (Fixed at Top) -->
            <header class="shrink-0 bg-white/90 backdrop-blur-md sticky top-0 z-30 border-b border-slate-200/80 shadow-xs">
                <div class="px-3 sm:px-5 lg:px-6 py-2.5 sm:py-3 flex items-center justify-between gap-2 sm:gap-4">
                    
                    <!-- Left: Brand Logo (Mobile) + Page Heading -->
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <a href="{{ route('panel.admin.beranda') }}" class="lg:hidden w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-brand-900 p-1 flex items-center justify-center shrink-0 shadow-xs border border-brand-800">
                            <img src="{{ asset('images/logo_omyu.png') }}" alt="OMYU Logo" class="w-full h-full object-contain drop-shadow">
                        </a>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <h1 class="text-sm sm:text-base lg:text-lg font-extrabold text-slate-900 tracking-tight font-sans whitespace-nowrap">
                                    @yield('page_title', 'Dashboard')
                                </h1>
                                @hasSection('badge')
                                    <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-brand-100 text-brand-800 shrink-0">
                                        @yield('badge')
                                    </span>
                                @endif
                            </div>
                            <p class="hidden sm:block text-xs sm:text-sm text-slate-500 mt-0.5 truncate">
                                @yield('page_subtitle', 'Sistem Manajemen Operasional & Finansial OMYU Fresh Juice')
                            </p>
                        </div>
                    </div>

                    <!-- Right: Info Pill, Actions & Mobile Logout -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                        <!-- Live Date Badge -->
                        <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-100/80 border border-slate-200/60 text-xs font-semibold text-slate-700">
                            <i class="fa-regular fa-calendar-days text-brand-600"></i>
                            <span>{{ Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                        </div>

                        <!-- Header Actions (e.g. Buttons) -->
                        <div class="header-actions shrink-0 flex items-center gap-1.5 sm:gap-2">
                            @yield('header_actions')
                        </div>

                        <!-- Mobile Logout Button -->
                        <form method="POST" action="{{ route('keluar') }}" class="lg:hidden shrink-0">
                            @csrf
                            <button type="submit" 
                                    title="Keluar dari Sistem"
                                    onclick="return confirm('Apakah Anda yakin ingin keluar dari panel admin?')"
                                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center transition-colors shadow-xs">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xs sm:text-sm"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </header>

            <!-- Scrollable Content Viewport -->
            <div class="flex-1 min-w-0 overflow-y-auto overflow-x-hidden">

                <!-- Alert Flash Messages -->
                <main class="flex-1 p-3 sm:p-4 lg:p-5 pb-28 lg:pb-6">

                @if(session('sukses'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 shadow-xs flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-check text-base"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-emerald-900">Operasi Berhasil</h4>
                                <p class="text-xs sm:text-sm text-emerald-700 mt-0.5">{{ session('sukses') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('galat'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 shadow-xs flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-rose-900">Terjadi Kesalahan</h4>
                                <p class="text-xs sm:text-sm text-rose-700 mt-0.5">{{ session('galat') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-800 p-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 shadow-xs">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-circle-exclamation text-base"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-amber-900">Mohon Periksa Formulir Kembali</h4>
                                <ul class="list-disc list-inside text-xs sm:text-sm text-amber-800 mt-1 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')

            </main>

                <!-- Footer -->
                <footer class="bg-white border-t border-slate-200/80 px-6 py-4 pb-28 lg:pb-4 text-center text-xs text-slate-500">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                        <p>© {{ date('Y') }} <strong>OMYU Fresh Juice & Healthy Bar</strong>. Hak Cipta Dilindungi.</p>
                        <p class="text-slate-400">Modul Eksekutif & Manajemen Bisnis (Owner)</p>
                    </div>
                </footer>

            </div>

        </div>

    </div>

    <!-- Mobile Bottom Navigation Bar (5 Items: Dashboard | Pesanan | Lainnya | Laba Rugi | Buku Kas) -->
    <nav class="admin-bottom-bar lg:hidden">
        <div class="grid grid-cols-5 items-center w-full px-1.5 py-1.5">
            
            <!-- 1. Dashboard -->
            <a href="{{ route('panel.admin.beranda') }}"
               class="admin-bottom-tab flex flex-col items-center justify-center py-1.5 px-0.5 rounded-xl text-center {{ request()->routeIs('panel.admin.beranda') ? 'active' : 'text-slate-300 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie text-sm mb-0.5 {{ request()->routeIs('panel.admin.beranda') ? 'text-brand-950' : 'text-brand-300' }}"></i>
                <span class="text-[9.5px] font-bold tracking-tight whitespace-nowrap">Dashboard</span>
            </a>

            <!-- 2. Pesanan -->
            <a href="{{ route('panel.admin.penjualan.index') }}"
               class="admin-bottom-tab flex flex-col items-center justify-center py-1.5 px-0.5 rounded-xl text-center {{ request()->routeIs('panel.admin.penjualan.*') ? 'active' : 'text-slate-300 hover:text-white' }}">
                <i class="fa-solid fa-receipt text-sm mb-0.5 {{ request()->routeIs('panel.admin.penjualan.*') ? 'text-brand-950' : 'text-brand-300' }}"></i>
                <span class="text-[9.5px] font-bold tracking-tight whitespace-nowrap">Pesanan</span>
            </a>

            <!-- 3. Lainnya (Di Tengah) -->
            @php
                $isMoreActive = request()->routeIs('panel.admin.resep.*') || request()->routeIs('panel.admin.bahan.*') || request()->routeIs('panel.admin.karyawan.*');
            @endphp
            <button type="button"
                    @click="moreMenuOpen = !moreMenuOpen"
                    class="admin-bottom-tab flex flex-col items-center justify-center py-1.5 px-0.5 rounded-xl text-center relative {{ $isMoreActive ? 'active' : 'text-slate-300 hover:text-white' }}">
                @if($isMoreActive)
                    <span class="absolute top-1 right-2.5 w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                @endif
                <i class="fa-solid fa-layer-group text-sm mb-0.5 {{ $isMoreActive ? 'text-brand-950' : 'text-brand-300' }}" :class="moreMenuOpen ? 'scale-110' : ''" style="transition: transform 0.2s ease;"></i>
                <span class="text-[9.5px] font-bold tracking-tight whitespace-nowrap">Lainnya</span>
            </button>

            <!-- 4. Laba Rugi -->
            <a href="{{ route('panel.admin.keuangan.laba_rugi') }}"
               class="admin-bottom-tab flex flex-col items-center justify-center py-1.5 px-0.5 rounded-xl text-center {{ request()->routeIs('panel.admin.keuangan.laba_rugi') ? 'active' : 'text-slate-300 hover:text-white' }}">
                <i class="fa-solid fa-file-invoice-dollar text-sm mb-0.5 {{ request()->routeIs('panel.admin.keuangan.laba_rugi') ? 'text-brand-950' : 'text-brand-300' }}"></i>
                <span class="text-[9.5px] font-bold tracking-tight whitespace-nowrap">Laba Rugi</span>
            </a>

            <!-- 5. Buku Kas -->
            <a href="{{ route('panel.admin.keuangan.buku_kas') }}"
               class="admin-bottom-tab flex flex-col items-center justify-center py-1.5 px-0.5 rounded-xl text-center {{ request()->routeIs('panel.admin.keuangan.buku_kas') ? 'active' : 'text-slate-300 hover:text-white' }}">
                <i class="fa-solid fa-book-bookmark text-sm mb-0.5 {{ request()->routeIs('panel.admin.keuangan.buku_kas') ? 'text-brand-950' : 'text-brand-300' }}"></i>
                <span class="text-[9.5px] font-bold tracking-tight whitespace-nowrap">Buku Kas</span>
            </a>

        </div>
    </nav>

    <!-- Bottom Sheet Popup Menu Lainnya (Mobile) -->
    <div x-cloak
         x-show="moreMenuOpen"
         class="fixed inset-0 z-50 lg:hidden flex flex-col justify-end"
         role="dialog"
         aria-modal="true">
        
        <!-- Backdrop Blur Overlay -->
        <div x-show="moreMenuOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="moreMenuOpen = false"
             class="fixed inset-0 bg-brand-950/70 backdrop-blur-sm"></div>

        <!-- Sheet Panel Modal -->
        <div x-show="moreMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             @click.outside="moreMenuOpen = false"
             class="relative bg-brand-900 text-white rounded-t-3xl shadow-2xl border-t border-brand-700/80 p-4 pb-28 max-h-[85vh] overflow-y-auto">
            
            <!-- Handle Drag Indicator -->
            <div class="w-12 h-1.5 bg-brand-700/80 rounded-full mx-auto mb-3.5"></div>

            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-brand-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-brand-400 text-brand-950 flex items-center justify-center font-bold text-sm shadow-sm">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-white">Menu Lainnya</h3>
                        <p class="text-[11px] text-brand-300">Modul Operasional, Bahan & Karyawan</p>
                    </div>
                </div>
                <button type="button" @click="moreMenuOpen = false" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Menu List -->
            <div class="py-3 space-y-2">
                <!-- Resep, HPP & Margin -->
                <a href="{{ route('panel.admin.resep.index') }}"
                   class="flex items-center gap-3.5 p-3 rounded-2xl transition-all {{ request()->routeIs('panel.admin.resep.*') ? 'bg-brand-400 text-brand-950 font-bold shadow-md' : 'bg-brand-950/60 hover:bg-brand-950 text-white border border-brand-800/80' }}">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ request()->routeIs('panel.admin.resep.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-brand-300' }}">
                        <i class="fa-solid fa-blender text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold">Resep, HPP & Margin</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.resep.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-brand-300' }}">O-03</span>
                        </div>
                        <p class="text-xs {{ request()->routeIs('panel.admin.resep.*') ? 'text-brand-950/80' : 'text-slate-400' }} truncate">Formula jus & kalkulasi food cost</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs {{ request()->routeIs('panel.admin.resep.*') ? 'text-brand-950' : 'text-white/40' }}"></i>
                </a>

                <!-- Persediaan Bahan -->
                <a href="{{ route('panel.admin.bahan.index') }}"
                   class="flex items-center gap-3.5 p-3 rounded-2xl transition-all {{ request()->routeIs('panel.admin.bahan.*') ? 'bg-brand-400 text-brand-950 font-bold shadow-md' : 'bg-brand-950/60 hover:bg-brand-950 text-white border border-brand-800/80' }}">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ request()->routeIs('panel.admin.bahan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-brand-300' }}">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold">Persediaan Bahan</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.bahan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-brand-300' }}">O-04</span>
                        </div>
                        <p class="text-xs {{ request()->routeIs('panel.admin.bahan.*') ? 'text-brand-950/80' : 'text-slate-400' }} truncate">Monitoring stok fisik & batas minimum</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs {{ request()->routeIs('panel.admin.bahan.*') ? 'text-brand-950' : 'text-white/40' }}"></i>
                </a>

                <!-- Manajemen Karyawan -->
                <a href="{{ route('panel.admin.karyawan.index') }}"
                   class="flex items-center gap-3.5 p-3 rounded-2xl transition-all {{ request()->routeIs('panel.admin.karyawan.*') ? 'bg-brand-400 text-brand-950 font-bold shadow-md' : 'bg-brand-950/60 hover:bg-brand-950 text-white border border-brand-800/80' }}">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ request()->routeIs('panel.admin.karyawan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-brand-300' }}">
                        <i class="fa-solid fa-users-gear text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold">Manajemen Karyawan</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-md font-bold {{ request()->routeIs('panel.admin.karyawan.*') ? 'bg-brand-950/20 text-brand-950' : 'bg-white/10 text-brand-300' }}">O-09</span>
                        </div>
                        <p class="text-xs {{ request()->routeIs('panel.admin.karyawan.*') ? 'text-brand-950/80' : 'text-slate-400' }} truncate">Akun staf barista & hak akses kasir</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs {{ request()->routeIs('panel.admin.karyawan.*') ? 'text-brand-950' : 'text-white/40' }}"></i>
                </a>
            </div>

            <!-- Profile & Logout row inside modal -->
            <div class="pt-3 border-t border-brand-800 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-brand-400 text-brand-950 font-bold flex items-center justify-center text-xs shrink-0">
                        {{ strtoupper(substr(auth()->user()->nama ?? 'O', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->nama ?? 'Owner OMYU' }}</p>
                        <p class="text-[10px] text-brand-300/80 truncate">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('keluar') }}" class="shrink-0">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin keluar dari panel admin?')"
                            class="px-3 py-1.5 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 text-xs font-bold flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- OMYU Admin V35 Interactive Scripts -->
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>
