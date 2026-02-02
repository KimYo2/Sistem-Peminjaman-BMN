<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman BMN')</title>
    <link rel="icon" href="{{ asset('bps_logo.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="stylesheet" href="/src/assets/css/light-mode-override.css?v=3"> -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            // Dark mode removed
            theme: {
                extend: {
                    colors: {
                        slate: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>




    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">

    <!-- Header -->
    <header
        class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <!-- Left Side: Logo & Nav -->
                <div class="flex items-center">
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

                    <!-- Nav Links -->
                    <div class="hidden sm:flex sm:space-x-6">
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.barang.index') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.barang.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Barang
                                </a>
                                <a href="{{ route('admin.histori.index') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.histori.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Histori
                                </a>
                                <a href="{{ route('admin.tiket.index') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.tiket.*') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Tiket
                                </a>
                            @else
                                <a href="{{ route('user.dashboard') }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : '' }}">
                                    Dashboard
                                </a>
                                <!-- Add User specific links here if needed -->
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side: Actions -->
                <div class="flex items-center gap-3">
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
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
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>

</html>