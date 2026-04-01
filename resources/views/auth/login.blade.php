<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman BMN</title>
    <link rel="icon" href="{{ asset('bps_logo.png') }}" type="image/png">
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = { darkMode: 'class' };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/theme-fallback.css') }}">
    <script src="{{ asset('js/theme.js') }}"></script>
</head>

<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white overflow-hidden">
    <!-- Faux "current page" layer -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950"></div>
        <div class="absolute -top-28 -left-20 h-72 w-72 rounded-full bg-blue-200/70 blur-3xl dark:bg-blue-900/40"></div>
        <div class="absolute -bottom-24 -right-16 h-72 w-72 rounded-full bg-cyan-200/60 blur-3xl dark:bg-cyan-900/40"></div>

        <div class="relative h-full w-full px-6 py-8 opacity-60">
            <div class="mx-auto max-w-5xl space-y-6">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-blue-600/80"></div>
                    <div class="h-6 w-40 rounded-lg bg-slate-200/80 dark:bg-slate-800/80"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="h-36 rounded-2xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/60 dark:border-slate-700/60"></div>
                    <div class="h-36 rounded-2xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/60 dark:border-slate-700/60"></div>
                    <div class="h-36 rounded-2xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/60 dark:border-slate-700/60"></div>
                </div>
                <div class="h-56 rounded-2xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/60 dark:border-slate-700/60"></div>
            </div>
        </div>
    </div>

    <!-- Overlay + floating login -->
    <div class="absolute inset-0 bg-slate-900/35 dark:bg-slate-950/60 backdrop-blur-[3px]"></div>

    <main class="relative z-10 min-h-screen flex items-center justify-center p-5">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <div class="mx-auto h-14 w-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <h1 class="mt-3 text-2xl font-bold">Sistem Peminjaman BMN</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Badan Pusat Statistik</p>
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
                        class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 shadow-md">
                        Access Dashboard
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-200/70 dark:border-slate-700/70">
                    <div class="rounded-lg bg-white/60 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-700/60 p-4">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wide">
                            Demo Credentials
                        </p>
                        <div class="text-xs text-slate-500 dark:text-slate-400 space-y-1.5 font-mono">
                            <div class="flex justify-between">
                                <span>Admin NIP:</span>
                                <span class="text-slate-700 dark:text-slate-200">198001012006041001</span>
                            </div>
                            <div class="flex justify-between">
                                <span>User NIP:</span>
                                <span class="text-slate-700 dark:text-slate-200">199001012015041001</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-200 dark:border-slate-700 pt-1.5 mt-1.5">
                                <span>Password:</span>
                                <span class="text-slate-700 dark:text-slate-200">password123</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                &copy; {{ date('Y') }} Sistem Peminjaman BMN
            </p>
        </div>
    </main>
</body>

</html>
