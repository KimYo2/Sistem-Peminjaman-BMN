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
            <div class="mx-auto max-w-5xl space-y-4">
                <!-- Faux navbar -->
                <div class="flex items-center gap-4">
                    <div class="h-9 w-9 rounded-xl bg-blue-600/80"></div>
                    <div class="h-5 w-32 rounded-lg bg-slate-200/80 dark:bg-slate-700/60"></div>
                    <div class="ml-auto flex gap-2">
                        <div class="h-5 w-16 rounded-lg bg-slate-200/60 dark:bg-slate-700/50"></div>
                        <div class="h-5 w-16 rounded-lg bg-slate-200/60 dark:bg-slate-700/50"></div>
                    </div>
                </div>
                <!-- Faux KPI stat cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="h-28 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 p-4 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-400/60 shrink-0"></div>
                        <div class="space-y-2 mt-0.5">
                            <div class="h-3 w-24 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                            <div class="h-5 w-16 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                        </div>
                    </div>
                    <div class="h-28 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 p-4 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-400/60 shrink-0"></div>
                        <div class="space-y-2 mt-0.5">
                            <div class="h-3 w-24 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                            <div class="h-5 w-16 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                        </div>
                    </div>
                    <div class="h-28 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 p-4 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-400/60 shrink-0"></div>
                        <div class="space-y-2 mt-0.5">
                            <div class="h-3 w-24 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                            <div class="h-5 w-16 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                        </div>
                    </div>
                    <div class="h-28 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 p-4 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-400/60 shrink-0"></div>
                        <div class="space-y-2 mt-0.5">
                            <div class="h-3 w-24 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                            <div class="h-5 w-16 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                        </div>
                    </div>
                </div>
                <!-- Faux chart + list -->
                <div class="grid grid-cols-3 gap-3">
                    <!-- Bar chart skeleton -->
                    <div class="col-span-2 h-48 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 p-4 flex flex-col justify-end gap-2">
                        <div class="h-3 w-28 rounded bg-slate-200/70 dark:bg-slate-700/60 mb-2"></div>
                        <div class="flex items-end gap-2 h-28">
                            <div class="flex-1 rounded-t-lg bg-blue-300/50 dark:bg-blue-700/50" style="height:60%"></div>
                            <div class="flex-1 rounded-t-lg bg-blue-300/50 dark:bg-blue-700/50" style="height:85%"></div>
                            <div class="flex-1 rounded-t-lg bg-blue-300/50 dark:bg-blue-700/50" style="height:45%"></div>
                            <div class="flex-1 rounded-t-lg bg-blue-300/50 dark:bg-blue-700/50" style="height:100%"></div>
                            <div class="flex-1 rounded-t-lg bg-blue-300/50 dark:bg-blue-700/50" style="height:70%"></div>
                            <div class="flex-1 rounded-t-lg bg-blue-300/50 dark:bg-blue-700/50" style="height:55%"></div>
                        </div>
                    </div>
                    <!-- List skeleton -->
                    <div class="col-span-1 h-48 rounded-2xl bg-white/85 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 p-4 space-y-3">
                        <div class="h-3 w-20 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                        @foreach([1,2,3,4] as $_)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-200/70 dark:bg-slate-700/60 shrink-0"></div>
                            <div class="flex-1 space-y-1">
                                <div class="h-2.5 rounded bg-slate-200/70 dark:bg-slate-700/60"></div>
                                <div class="h-2 w-2/3 rounded bg-slate-200/60 dark:bg-slate-700/50"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
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
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h1 class="mt-3 text-3xl font-extrabold tracking-tight">SIAP</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 text-center font-medium">
                    Sistem Inventaris Aset Perkantoran
                </p>
                <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1
                             rounded-full text-xs font-medium
                             bg-blue-50 dark:bg-blue-900/30
                             text-blue-700 dark:text-blue-300
                             border border-blue-100 dark:border-blue-800/50">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    BPS Kabupaten Jepara
                </span>
            </div>

            <div
                class="rounded-2xl border border-white/40 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/70 shadow-2xl backdrop-blur-xl p-8">
                <div class="h-1 w-12 rounded-full bg-blue-500 mb-5"></div>
                <h2 class="text-lg font-semibold mb-1">Masuk ke SIAP</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-5">
                    Gunakan NIP dan password yang telah diberikan admin.
                </p>

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="nip" class="block text-sm font-medium mb-1.5">NIP</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required autofocus
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-white/70 dark:bg-slate-950/60 border border-slate-300/70 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 dark:text-white placeholder-slate-400 @error('nip') border-red-500 @enderror"
                                placeholder="Masukkan NIP Anda">
                        </div>
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-1.5">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" id="password" name="password" required
                                class="w-full px-4 pr-10 py-2.5 rounded-lg bg-white/70 dark:bg-slate-950/60 border border-slate-300/70 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 dark:text-white placeholder-slate-400 @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password">
                            <button type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                                :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-2.5 shadow-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Masuk
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="text-center mt-6 space-y-1">
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    &copy; {{ date('Y') }} SIAP &mdash; Sistem Inventaris Aset Perkantoran
                </p>
                <p class="text-xs text-slate-300 dark:text-slate-600">
                    Badan Pusat Statistik Kabupaten Jepara
                </p>
            </div>
        </div>
    </main>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
