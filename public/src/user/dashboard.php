<?php
require_once '../config/auth.php';
requireLogin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Peminjaman BMN</title>
    <link rel="stylesheet" href="/src/assets/css/light-mode-override.css?v=<?= time() ?>">
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
                <div class="bg-blue-600 rounded-lg p-1.5">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">
                        Dashboard Pegawai</h1>
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

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <!-- Scan QR Card -->
            <a href="scan.php"
                class="block bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg shadow-sm hover:shadow-md transition p-6 text-white group relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-full opacity-10 pointer-events-none">
                    <svg class="w-full h-full transform translate-x-10 scale-150" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                </div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-2xl font-bold mb-2">Scan QR Code</h3>
                        <p class="text-blue-100 text-sm">Pindai kode QR pada stiker barang untuk memulai peminjaman.
                        </p>
                    </div>
                    <div
                        class="bg-white/20 rounded-lg p-3 backdrop-blur-sm group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Peminjaman Aktif Card -->
            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex flex-col h-full transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white transition-colors">Peminjaman Aktif</h3>
                    <span
                        class="bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs px-2 py-1 rounded-md font-medium transition-colors">Sedang
                        Dipinjam</span>
                </div>
                <div id="aktivePeminjaman" class="space-y-3 flex-1 overflow-y-auto max-h-60 pr-1 custom-scrollbar">
                    <div class="text-center py-8 text-slate-400 dark:text-slate-500">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto mb-2"></div>
                        <p class="text-xs">Memuat data...</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Histori Peminjaman -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 transition-colors">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2 transition-colors">
                <span>Histori Peminjaman Saya</span>
            </h3>

            <div id="historiList" class="space-y-3">
                <div class="text-center py-12 text-slate-400 dark:text-slate-500">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-3"></div>
                    Memuat histori...
                </div>
            </div>
        </div>

    </main>

    <script src="/src/assets/js/main.js?v=<?= time() ?>"></script>
    <script>
        // Load peminjaman aktif
        async function loadAktivePeminjaman() {
            const result = await apiCall('get_histori.php?status=dipinjam&limit=10');
            const containerEl = document.getElementById('aktivePeminjaman');

            if (result.success && result.data.length > 0) {
                containerEl.innerHTML = result.data.map(item => `
                    <div class="bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 rounded-lg p-3 hover:border-indigo-200 dark:hover:border-indigo-500 transition group">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm text-slate-900 dark:text-white truncate">${item.nomor_bmn}</h4>
                                <div class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 truncate">
                                    ${item.brand ? `<span class="font-medium">${item.brand}</span>` : ''} 
                                    ${item.tipe ? `- ${item.tipe}` : ''}
                                </div>
                                <div class="flex items-center gap-1 mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    ${formatTanggal(item.waktu_pinjam)}
                                </div>
                            </div>
                            <button onclick="kembalikanDariDashboard('${item.nomor_bmn}')" 
                                class="w-full sm:w-auto flex-shrink-0 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-md transition shadow-sm">
                                Kembalikan
                            </button>
                        </div>
                    </div>
                `).join('');
            } else {
                containerEl.innerHTML = `
                    <div class="text-center py-6 text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700/30 rounded-lg border border-dashed border-slate-200 dark:border-slate-600">
                        <p class="text-xs">Tidak ada barang yang sedang dipinjam</p>
                    </div>
                `;
            }
        }

        // Kembalikan dari dashboard
        async function kembalikanDariDashboard(nomorBMN) {
            if (!confirm('Apakah Anda yakin ingin mengembalikan barang ini?')) {
                return;
            }

            const result = await apiCall('kembalikan_barang.php', 'POST', {
                nomor_bmn: nomorBMN
            });

            if (result.success) {
                showToast('Barang berhasil dikembalikan!', 'success');
                // Reload both active and history
                loadAktivePeminjaman();
                loadHistori();
            } else {
                showToast(result.message, 'error');
            }
        }

        // Load histori
        async function loadHistori() {
            const result = await apiCall('get_histori.php?limit=20');
            const listEl = document.getElementById('historiList');

            if (result.success && result.data.length > 0) {
                listEl.innerHTML = result.data.map(item => `
                    <div class="border border-slate-100 dark:border-slate-700 rounded-lg p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition flex flex-col sm:flex-row justify-between items-start gap-4 group">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-semibold text-sm text-slate-900 dark:text-white">${item.nomor_bmn}</h4>
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wide ${item.status === 'dipinjam' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300'}">
                                    ${item.status === 'dipinjam' ? 'Dipinjam' : 'Selesai'}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-1">${item.brand || 'Barang'} - ${item.tipe || 'Umum'}</p>
                            
                            <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400 mt-2">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Pinjam: ${formatTanggal(item.waktu_pinjam)}
                                </span>
                                ${item.waktu_kembali ? `
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Kembali: ${formatTanggal(item.waktu_kembali)}
                                </span>` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                listEl.innerHTML = `
                    <div class="text-center py-12 text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700/30 rounded-lg border border-dashed border-slate-200 dark:border-slate-600">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm">Belum ada riwayat peminjaman</p>
                    </div>
                `;
            }
        }

        // Load data on page load
        loadAktivePeminjaman();
        loadHistori();
    </script>
</body>

</html>