<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman BMN</title>
    <!-- Use the same Tailwind config/script as legacy or verify if we have a layout -->
    <!-- Ideally we should use the same resources as app layout but this page is standalone -->
    <script src="https://cdn.tailwindcss.com"></script>
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

    <script>
        // Check and apply theme immediately
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="dark:bg-slate-900 min-h-screen flex items-center justify-center p-4 transition-colors duration-200 bg-slate-50">


    <div class="w-full max-w-md">
        <!-- Logo BPS -->
        <div class="text-center mb-8">
            <div
                class="bg-blue-600 dark:bg-blue-700 rounded-xl w-16 h-16 mx-auto flex items-center justify-center shadow-sm border border-blue-700 dark:border-blue-600 mb-4 transition-colors">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">Sistem Peminjaman BMN</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Badan Pusat Statistik</p>
        </div>

        <!-- Login Card -->
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 transition-colors">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6 transition-colors">Login</h2>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="nip"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">NIP</label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required autofocus
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-900 dark:text-white placeholder-slate-400 @error('nip') border-red-500 @enderror"
                        placeholder="Masukkan NIP Anda">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-900 dark:text-white placeholder-slate-400 @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 shadow-sm flex justify-center items-center">
                    Access Dashboard
                </button>
            </form>

            <!-- Info -->
            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 transition-colors">
                <div
                    class="rounded-lg bg-slate-50 dark:bg-slate-900 p-4 border border-slate-200 dark:border-slate-700 transition-colors">
                    <p
                        class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wide transition-colors">
                        Demo Credentials</p>
                    <div class="text-xs text-slate-500 dark:text-slate-500 space-y-1.5 font-mono">
                        <div class="flex justify-between">
                            <span>Admin NIP:</span>
                            <span class="text-slate-700 dark:text-slate-300">198001012006041001</span>
                        </div>
                        <div class="flex justify-between">
                            <span>User NIP:</span>
                            <span class="text-slate-700 dark:text-slate-300">199001012015041001</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-200 dark:border-slate-700 pt-1.5 mt-1.5">
                            <span>Password:</span>
                            <span class="text-slate-700 dark:text-slate-300">password123</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-8">
            &copy; {{ date('Y') }} Sistem Peminjaman BMN
        </p>
    </div>

</body>

</html>