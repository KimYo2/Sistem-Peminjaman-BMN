<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$user = getCurrentUser();

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as total FROM barang");
$totalBarang = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM barang WHERE ketersediaan = 'tersedia'");
$tersedia = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM barang WHERE ketersediaan = 'dipinjam'");
$dipinjam = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM histori_peminjaman WHERE status = 'dipinjam'");
$aktivePeminjaman = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Peminjaman BMN</title>
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

<body class="dark:bg-slate-900 min-h-screen transition-colors duration-200">


    <!-- Header -->
    <header
        class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 sticky top-0 z-30 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 dark:bg-blue-700 rounded-lg p-1.5 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">
                        Dashboard Admin</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">Sistem
                        Peminjaman BMN</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-600 dark:text-slate-300 hidden sm:block transition-colors">Hi,
                    <?= htmlspecialchars($user['nama']) ?></span>

                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()"
                    class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="darkModeIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                <button onclick="logout()"
                    class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    Logout
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Total Barang</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1 transition-colors">
                        <?= $totalBarang ?>
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Tersedia</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1 transition-colors">
                        <?= $tersedia ?>
                    </p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Dipinjam</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1 transition-colors">
                        <?= $dipinjam ?>
                    </p>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Aktif Peminjaman
                    </p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1 transition-colors">
                        <?= $aktivePeminjaman ?>
                    </p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4 transition-colors">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <a href="daftar_barang.php"
                class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-5 hover:border-indigo-300 dark:hover:border-indigo-500 hover:shadow-md transition duration-200 group">
                <div class="flex items-start">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-3 mr-4 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="font-semibold text-slate-800 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition">
                            Daftar Barang</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Kelola inventaris
                            BMN</p>
                    </div>
                </div>
            </a>

            <a href="scan_return.php"
                class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-5 hover:border-emerald-300 dark:hover:border-emerald-500 hover:shadow-md transition duration-200 group">
                <div class="flex items-start">
                    <div
                        class="bg-emerald-50 dark:bg-emerald-900/30 rounded-lg p-3 mr-4 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="font-semibold text-slate-800 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition">
                            Scan
                            Pengembalian</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Proses pengembalian
                            barang</p>
                    </div>
                </div>
            </a>

            <a href="histori.php"
                class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-5 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-md transition duration-200 group">
                <div class="flex items-start">
                    <div
                        class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-3 mr-4 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/50 transition">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="font-semibold text-slate-800 dark:text-white group-hover:text-purple-700 dark:group-hover:text-purple-400 transition">
                            Histori
                            Peminjaman</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Lihat riwayat dan
                            laporan</p>
                    </div>
                </div>
            </a>

        </div>

    </main>

    <script src="/src/assets/js/main.js"></script>
</body>

</html>