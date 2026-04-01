<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAP — Sistem Inventaris Aset Perkantoran</title>
    <link rel="icon" href="{{ asset('bps_logo.png') }}" type="image/png">
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = { darkMode: 'class' };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/theme-fallback.css') }}">
    <script src="{{ asset('js/theme.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes floatBob {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-10px) rotate(-2deg); }
        }
        .fade-slide-up {
            opacity: 0;
            animation: fadeSlideUp 0.5s ease-out forwards;
        }
        .delay-0 { animation-delay: 0ms; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .float-bob {
            animation: floatBob 3s ease-in-out infinite;
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-200">

    {{-- ══════════════════════════════════════════
         SECTION 1 — NAVBAR
    ══════════════════════════════════════════ --}}
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2">
                    <div class="bg-blue-600 rounded-lg p-1.5 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-slate-800 dark:text-white">SIAP</span>
                </a>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Dark mode toggle --}}
                    <button id="theme-toggle" type="button"
                        class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200 dark:focus:ring-slate-700">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- Login button --}}
                    <a href="{{ route('login') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══════════════════════════════════════════
         SECTION 2 — HERO
    ══════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900">
        {{-- Ambient blobs --}}
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200/40 dark:bg-blue-900/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-200/30 dark:bg-cyan-900/15 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left content --}}
                <div>
                    {{-- Badge pill --}}
                    <div class="fade-slide-up delay-0 mb-6">
                        <span class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium px-4 py-1.5 rounded-full border border-blue-200 dark:border-blue-800">
                            <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                            Sistem Inventaris Digital BPS
                        </span>
                    </div>

                    {{-- Headline --}}
                    <h1 class="fade-slide-up delay-100 text-4xl sm:text-5xl font-bold text-slate-900 dark:text-white leading-tight mb-6">
                        Kelola Aset Kantor<br>
                        Lebih <span class="text-blue-600">Mudah &amp; Terstruktur</span>
                    </h1>

                    {{-- Subtitle --}}
                    <p class="fade-slide-up delay-200 text-lg text-slate-600 dark:text-slate-400 max-w-xl mb-8 leading-relaxed">
                        Sistem peminjaman dan inventarisasi Barang Milik Negara (BMN) berbasis QR Code untuk kantor Badan Pusat Statistik. Catat, pantau, dan kelola aset dengan akurat.
                    </p>

                    {{-- Buttons --}}
                    <div class="fade-slide-up delay-300 flex flex-wrap gap-3 mb-10">
                        <a href="{{ route('login') }}"
                            class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
                            Masuk ke Sistem
                        </a>
                        <a href="#fitur"
                            class="border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 px-6 py-3 rounded-lg font-semibold transition-colors duration-150">
                            Pelajari Fitur ↓
                        </a>
                    </div>

                    {{-- Inline stats --}}
                    <div class="fade-slide-up delay-400 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Inventaris Terpusat
                        </span>
                        <span class="hidden sm:block w-px h-4 bg-slate-300 dark:bg-slate-600"></span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            QR Code Based
                        </span>
                        <span class="hidden sm:block w-px h-4 bg-slate-300 dark:bg-slate-600"></span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Laporan Real-time
                        </span>
                    </div>
                </div>

                {{-- Right: floating mock card --}}
                <div class="hidden lg:flex justify-center">
                    <div class="float-bob bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-6 w-80">
                        {{-- Card header --}}
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Ringkasan Inventaris</h3>
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>

                        {{-- Stat rows --}}
                        <div class="space-y-3 mb-5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400">Total Barang</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-100">142</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400">Dipinjam</span>
                                <span class="font-semibold text-amber-600 dark:text-amber-400">18</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400">Tersedia</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">124</span>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1.5">
                                <span>Ketersediaan</span>
                                <span>87%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 87%"></div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <p class="text-xs text-slate-400 dark:text-slate-500">Terakhir diperbarui: hari ini</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         SECTION 3 — FITUR UTAMA
    ══════════════════════════════════════════ --}}
    <section id="fitur" class="py-20 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section heading --}}
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">Fitur Utama Sistem</h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Dirancang untuk kebutuhan operasional kantor BPS sehari-hari</p>
            </div>

            {{-- Feature grid --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Feature 1 --}}
                <div x-data="{ shown: false }" x-init="const ob = new IntersectionObserver(([e]) => { if(e.isIntersecting){ shown=true; ob.disconnect(); }}, {threshold:0.1}); ob.observe($el);"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-500">
                    <svg class="w-8 h-8 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Peminjaman via QR Code</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Scan QR label pada barang untuk meminjam tanpa perlu input manual.</p>
                </div>

                {{-- Feature 2 --}}
                <div x-data="{ shown: false }" x-init="const ob = new IntersectionObserver(([e]) => { if(e.isIntersecting){ shown=true; ob.disconnect(); }}, {threshold:0.1}); ob.observe($el);"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-500" style="transition-delay:75ms">
                    <svg class="w-8 h-8 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Manajemen Inventaris</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Catat seluruh Barang Milik Negara dengan detail kondisi, lokasi, dan kategori.</p>
                </div>

                {{-- Feature 3 --}}
                <div x-data="{ shown: false }" x-init="const ob = new IntersectionObserver(([e]) => { if(e.isIntersecting){ shown=true; ob.disconnect(); }}, {threshold:0.1}); ob.observe($el);"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-500" style="transition-delay:150ms">
                    <svg class="w-8 h-8 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Alur Pengembalian</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Proses pengembalian barang dengan pencatatan kondisi dan tiket kerusakan otomatis.</p>
                </div>

                {{-- Feature 4 --}}
                <div x-data="{ shown: false }" x-init="const ob = new IntersectionObserver(([e]) => { if(e.isIntersecting){ shown=true; ob.disconnect(); }}, {threshold:0.1}); ob.observe($el);"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-500" style="transition-delay:225ms">
                    <svg class="w-8 h-8 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Dashboard & Laporan</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Pantau statistik peminjaman, barang terpopuler, dan laporan yang bisa diekspor.</p>
                </div>

                {{-- Feature 5 --}}
                <div x-data="{ shown: false }" x-init="const ob = new IntersectionObserver(([e]) => { if(e.isIntersecting){ shown=true; ob.disconnect(); }}, {threshold:0.1}); ob.observe($el);"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-500" style="transition-delay:300ms">
                    <svg class="w-8 h-8 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Riwayat & Audit Log</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Setiap aksi tercatat lengkap untuk keperluan audit dan akuntabilitas.</p>
                </div>

                {{-- Feature 6 --}}
                <div x-data="{ shown: false }" x-init="const ob = new IntersectionObserver(([e]) => { if(e.isIntersecting){ shown=true; ob.disconnect(); }}, {threshold:0.1}); ob.observe($el);"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-500" style="transition-delay:375ms">
                    <svg class="w-8 h-8 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Stock Opname Digital</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Lakukan pengecekan fisik inventaris dengan scan QR dan hasilkan laporan opname.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         SECTION 4 — ALUR PENGGUNAAN
    ══════════════════════════════════════════ --}}
    <section class="py-20 bg-slate-50 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section heading --}}
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">Cara Kerja Sistem</h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Proses peminjaman selesai dalam hitungan detik</p>
            </div>

            {{-- Steps --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-7 gap-6 lg:gap-0 items-start">
                {{-- Step 1 --}}
                <div class="lg:col-span-1 text-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mx-auto mb-3">1</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Scan QR Barang</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Arahkan kamera ke label QR pada barang yang ingin dipinjam.</p>
                </div>

                {{-- Arrow 1 --}}
                <div class="hidden lg:flex lg:col-span-1 items-center justify-center pt-3">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>

                {{-- Step 2 --}}
                <div class="lg:col-span-1 text-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mx-auto mb-3">2</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Ajukan Peminjaman</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Isi detail dan kirim pengajuan. Admin akan memproses persetujuan.</p>
                </div>

                {{-- Arrow 2 --}}
                <div class="hidden lg:flex lg:col-span-1 items-center justify-center pt-3">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>

                {{-- Step 3 --}}
                <div class="lg:col-span-1 text-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mx-auto mb-3">3</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Gunakan Barang</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Setelah disetujui, barang siap digunakan sesuai batas waktu.</p>
                </div>

                {{-- Arrow 3 --}}
                <div class="hidden lg:flex lg:col-span-1 items-center justify-center pt-3">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>

                {{-- Step 4 --}}
                <div class="lg:col-span-1 text-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mx-auto mb-3">4</div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Kembalikan & Laporkan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Kembalikan barang dan laporkan kondisinya melalui sistem.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         SECTION 5 — CTA FOOTER
    ══════════════════════════════════════════ --}}
    <section class="py-20 bg-blue-600">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Mulai Mengelola Inventaris?</h2>
            <p class="text-blue-100 mb-8 text-lg">Masuk dengan NIP dan password yang diberikan administrator.</p>
            <a href="{{ route('login') }}"
                class="inline-block bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                Masuk ke Sistem →
            </a>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         SECTION 6 — FOOTER BAR
    ══════════════════════════════════════════ --}}
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-sm text-slate-500 dark:text-slate-400">&copy; {{ date('Y') }} SIAP &mdash; BPS Kabupaten Jepara</p>
            <p class="text-sm text-slate-500 dark:text-slate-400">Dibuat untuk keperluan internal BPS</p>
        </div>
    </footer>

</body>
</html>
