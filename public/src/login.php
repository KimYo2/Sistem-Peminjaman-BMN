<?php
require_once 'config/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: /src/admin/dashboard.php');
    } else {
        header('Location: /src/user/dashboard.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman BMN</title>
    <link rel="stylesheet" href="/src/assets/css/light-mode-override.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
</head>

<body class="dark:bg-slate-900 min-h-screen flex items-center justify-center p-4 transition-colors duration-200">


    <!-- Dark Mode Toggle -->
    <button onclick="toggleDarkMode()"
        class="fixed top-4 right-4 p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path id="darkModeIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>

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

            <form id="loginForm" class="space-y-5">
                <div>
                    <label for="nip"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">NIP</label>
                    <input type="text" id="nip" name="nip" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-900 dark:text-white placeholder-slate-400"
                        placeholder="Masukkan NIP Anda">
                </div>

                <div>
                    <label for="password"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-900 dark:text-white placeholder-slate-400"
                        placeholder="Masukkan password">
                </div>

                <button type="submit" id="loginBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 shadow-sm">
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
            &copy; <?= date('Y') ?> Sistem Peminjaman BMN
        </p>
    </div>

    <script src="/src/assets/js/main.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const nip = document.getElementById('nip').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');

            // Disable button
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span> Loading...';

            const result = await apiCall('login.php', 'POST', { nip, password });

            if (result.success) {
                showToast('Login berhasil! Mengalihkan...', 'success');
                setTimeout(() => {
                    if (result.data.role === 'admin') {
                        window.location.href = '/src/admin/dashboard.php';
                    } else {
                        window.location.href = '/src/user/dashboard.php';
                    }
                }, 800);
            } else {
                showToast(result.message, 'error');
                loginBtn.disabled = false;
                loginBtn.textContent = 'Access Dashboard';
            }
        });
    </script>
</body>

</html>