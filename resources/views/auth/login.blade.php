<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIAP</title>
    <link rel="icon" href="{{ asset('bps_logo.png') }}" type="image/png">
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = { darkMode: 'class' };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/theme-fallback.css') }}">
    <script src="{{ asset('js/theme.js') }}"></script>
    <style>
        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .float-in {
            animation: floatIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes floatBob {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-6px); }
        }
        .float-bob {
            animation: floatBob 4s ease-in-out infinite;
        }
        @media (prefers-reduced-motion: reduce) {
            .float-in, .float-bob {
                animation: none;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white">
    <!-- Faux "current page" layer -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-slate-100 to-slate-200 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950/30"></div>
        <div class="absolute -top-28 -left-20 h-72 w-72 rounded-full bg-blue-300/60 blur-3xl dark:bg-blue-900/40"></div>
        <div class="absolute -bottom-24 -right-16 h-72 w-72 rounded-full bg-teal-200/55 blur-3xl dark:bg-cyan-900/40"></div>

        <div class="relative h-full w-full px-6 py-8 opacity-90">
            <div class="mx-auto max-w-5xl space-y-6">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-blue-600/80"></div>
                    <div class="h-6 w-40 rounded-lg bg-slate-200/80 dark:bg-slate-800/80"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="h-36 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60"></div>
                    <div class="h-36 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60"></div>
                    <div class="h-36 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60"></div>
                </div>
                <div class="h-56 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60"></div>
            </div>
        </div>
    </div>

    <!-- Overlay + floating login -->
    <div class="absolute inset-0 bg-white/15 dark:bg-slate-950/40 backdrop-blur-[2px]"></div>

    <div class="absolute top-4 right-4 z-20">
        <button id="theme-toggle" type="button"
            aria-label="Ganti tema"
            class="p-2 rounded-lg bg-white/20 dark:bg-slate-800/40 backdrop-blur-sm
                   text-slate-600 dark:text-slate-300
                   hover:bg-white/40 dark:hover:bg-slate-700/60
                   border border-white/30 dark:border-slate-700/50
                   transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z"/>
            </svg>
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </button>
    </div>

    <main class="relative z-10 min-h-screen flex items-center justify-center p-5">
        <div class="w-full max-w-md float-in">
            <div class="text-center mb-6">
                <div class="mx-auto h-14 w-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg float-bob">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <h1 class="mt-3 text-2xl font-bold">SIAP</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 text-center">
                    Sistem Inventaris Aset Perkantoran
                </p>
                <p class="text-xs text-slate-400 dark:text-slate-500 text-center">
                    BPS Kabupaten Jepara
                </p>
            </div>

            <div
                class="rounded-2xl border border-white/40 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/70 shadow-2xl backdrop-blur-xl p-8">
                <h2 class="text-lg font-semibold mb-5">Login</h2>

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="nip" class="block text-sm font-medium mb-1.5">NIP</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required autofocus
                            class="w-full px-4 py-2.5 rounded-lg bg-white/70 dark:bg-slate-950/60 border border-slate-300/70 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 dark:text-white placeholder-slate-400 @error('nip') border-red-500 @enderror"
                            placeholder="Masukkan NIP Anda">
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white/70 dark:bg-slate-950/60 border border-slate-300/70 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 dark:text-white placeholder-slate-400 @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-2.5 shadow-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Masuk
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                &copy; {{ date('Y') }} SIAP
            </p>
        </div>
    </main>
</body>

</html>
