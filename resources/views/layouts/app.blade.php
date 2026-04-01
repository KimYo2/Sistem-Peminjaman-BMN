<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman BMN')</title>
    <link rel="icon" href="{{ asset('bps_logo.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            darkMode: 'class'
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/theme-fallback.css') }}">
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">

    <!-- Header -->
    <header
        x-data="{ mobileOpen: false }"
        class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <!-- Left Side: Hamburger, Logo & Nav -->
                <div class="flex items-center">
                    <!-- Hamburger Button (mobile only) -->
                    @auth
                    <button
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen ? 'true' : 'false'"
                        aria-label="Buka menu navigasi"
                        class="sm:hidden mr-2 p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200 dark:focus:ring-slate-700"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    @endauth

                    <!-- Logo -->
                    <a href="{{ Auth::check() && Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                        class="mr-8 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 transition-colors flex items-center gap-2">
                        <div class="bg-blue-600 rounded-lg p-1.5 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-lg text-slate-800 dark:text-white hidden sm:block">Pinjam BMN</span>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden sm:flex sm:space-x-6">
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.barang.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.barang.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Barang
                                </a>
                                <a href="{{ route('admin.opname.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.opname.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Opname
                                </a>
                                <a href="{{ route('admin.histori.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.histori.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Histori
                                </a>
                                <a href="{{ route('admin.tiket.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.tiket.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Tiket
                                </a>
                                <a href="{{ route('admin.users.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    User
                                </a>
                                <a href="{{ route('admin.kategori.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.kategori.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Kategori
                                </a>
                                <a href="{{ route('admin.ruangan.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.ruangan.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Ruangan
                                </a>
                            @else
                                <a href="{{ route('user.dashboard') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('user.dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('user.scan') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('user.scan') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Pinjam Barang
                                </a>
                                <a href="{{ route('user.histori.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('user.histori.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Riwayat Saya
                                </a>
                                <a href="{{ route('return.index') }}"
                                    class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('return.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Kembalikan
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side: Actions -->
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" type="button"
                        aria-label="Ganti tema"
                        class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200 dark:focus:ring-slate-700">
                        <!-- Sun Icon -->
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                        <!-- Moon Icon -->
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>

                    @auth
                        @php
                            $user = Auth::user();
                            $displayName = $user->name ?? $user->nip ?? 'User';
                            $isAdmin = ($user->role ?? '') === 'admin';
                            $label = $isAdmin ? 'Admin - ' . $displayName : $displayName;
                            $initial = mb_strtoupper(mb_substr($displayName, 0, 1));
                        @endphp

                        {{-- Notification Bell --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button"
                                aria-label="Lihat notifikasi"
                                class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200 dark:focus:ring-slate-700"
                                title="Notifikasi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadNotifCount > 0)
                                    <span class="absolute top-1 right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-800">
                                        {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                                    </span>
                                @endif
                            </button>

                            {{-- Dropdown --}}
                            <div x-show="open"
                                @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 z-50"
                                style="display: none; top: 100%;">

                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-slate-800 dark:text-white">Notifikasi</span>
                                    @if($unreadNotifCount > 0)
                                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $unreadNotifCount }} belum dibaca</span>
                                    @endif
                                </div>

                                @if($latestNotifs->isEmpty())
                                    <div class="px-4 py-6 text-center">
                                        <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <p class="text-xs text-slate-400 dark:text-slate-500">Tidak ada notifikasi baru</p>
                                    </div>
                                @else
                                    <div class="divide-y divide-slate-100 dark:divide-slate-700 max-h-72 overflow-y-auto">
                                        @foreach($latestNotifs as $notif)
                                            <a href="{{ route('notifikasi.read', $notif->id) }}"
                                                class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                                <div class="shrink-0 mt-0.5">
                                                    @if($notif->type === 'overdue')
                                                        <div class="w-7 h-7 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        </div>
                                                    @elseif($notif->type === 'approval')
                                                        <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        </div>
                                                    @elseif($notif->type === 'waitlist')
                                                        <div class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        </div>
                                                    @else
                                                        <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ $notif->judul }}</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed mt-0.5">{{ $notif->pesan }}</p>
                                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                                </div>
                                                <span class="shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-1.5"></span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-700">
                                    <a href="{{ route('notifikasi.index') }}"
                                        class="block text-center text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                        Lihat semua notifikasi →
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- User Avatar --}}
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-sm font-semibold">
                                {{ $initial }}
                            </div>
                            <span class="hidden sm:block text-sm font-medium">{{ $label }}</span>
                        </div>
                    @endauth

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            @click.prevent="if(confirm('Yakin ingin keluar?')) $el.closest('form').submit()"
                            aria-label="Keluar dari sistem"
                            class="p-2 rounded-lg text-red-600 hover:bg-slate-100 dark:text-red-500 dark:hover:bg-slate-700 transition-colors"
                            title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>




                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        @auth
            <!-- Backdrop -->
            <div
                x-show="mobileOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="mobileOpen = false"
                class="sm:hidden fixed inset-0 z-40 bg-black/50"
                style="display: none;"
            ></div>

            <!-- Drawer Panel -->
            <nav
                x-show="mobileOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                @click.outside="mobileOpen = false"
                role="navigation"
                class="sm:hidden fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-800 shadow-xl flex flex-col"
                style="display: none;"
            >
                <!-- Drawer Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <div class="bg-blue-600 rounded-lg p-1.5 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="font-bold text-lg text-slate-800 dark:text-white">Pinjam BMN</span>
                    </div>
                    <button @click="mobileOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Drawer Nav Links -->
                <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">🏠</span> Dashboard
                        </a>
                        <a href="{{ route('admin.barang.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.barang.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">📦</span> Barang
                        </a>
                        <a href="{{ route('admin.opname.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.opname.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">🔍</span> Stock Opname
                        </a>
                        <a href="{{ route('admin.histori.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.histori.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">📋</span> Histori
                        </a>
                        <a href="{{ route('admin.tiket.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.tiket.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">🎫</span> Tiket Kerusakan
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">👥</span> Manajemen User
                        </a>
                        <a href="{{ route('admin.kategori.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.kategori.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">🏷️</span> Kategori
                        </a>
                        <a href="{{ route('admin.ruangan.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.ruangan.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">🚪</span> Ruangan
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">🏠</span> Dashboard
                        </a>
                        <a href="{{ route('user.scan') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('user.scan') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">📷</span> Pinjam Barang (Scan)
                        </a>
                        <a href="{{ route('user.histori.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('user.histori.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">📋</span> Riwayat Saya
                        </a>
                        <a href="{{ route('return.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('return.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            <span class="text-lg">🔄</span> Kembalikan Barang
                        </a>
                    @endif
                </div>

                <!-- Drawer Footer: User Info -->
                <div class="border-t border-slate-200 dark:border-slate-700 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-sm font-semibold">
                            {{ $initial }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 dark:text-white truncate">{{ $displayName }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isAdmin ? 'Administrator' : 'User' }}</p>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth
    </header>

    <!-- Main Content -->
    <main class="w-full">
        @yield('content')
    </main>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/qr-scan.js') }}"></script>
    @stack('scripts')
</body>

</html>
