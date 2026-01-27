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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo BPS -->
        <div class="text-center mb-8">
            <div class="bg-white rounded-full w-20 h-20 mx-auto flex items-center justify-center shadow-lg mb-4">
                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Sistem Peminjaman BMN</h1>
            <p class="text-gray-600 mt-2">Badan Pusat Statistik</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Login</h2>

            <form id="loginForm" class="space-y-6">
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                    <input type="text" id="nip" name="nip" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Masukkan NIP Anda">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Masukkan password">
                </div>

                <button type="submit" id="loginBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    Login
                </button>
            </form>

            <!-- Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 font-medium mb-2">Demo Credentials:</p>
                <div class="text-xs text-gray-600 space-y-1">
                    <p><strong>Admin:</strong> NIP: 198001012006041001 | Pass: password123</p>
                    <p><strong>User:</strong> NIP: 199001012015041001 | Pass: password123</p>
                </div>
            </div>
        </div>
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
            loginBtn.textContent = 'Loading...';

            const result = await apiCall('login.php', 'POST', { nip, password });

            if (result.success) {
                showToast('Login berhasil! Mengalihkan...', 'success');
                setTimeout(() => {
                    if (result.data.role === 'admin') {
                        window.location.href = '/src/admin/dashboard.php';
                    } else {
                        window.location.href = '/src/user/dashboard.php';
                    }
                }, 1000);
            } else {
                showToast(result.message, 'error');
                loginBtn.disabled = false;
                loginBtn.textContent = 'Login';
            }
        });
    </script>
</body>

</html>