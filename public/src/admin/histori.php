<?php
require_once '../config/auth.php';
requireAdmin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Peminjaman - Sistem Peminjaman BMN</title>
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
            <div class="flex items-center gap-4">
                <a href="dashboard.php"
                    class="p-2 -ml-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">Histori
                        Peminjaman</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">Riwayat
                        aktivitas peminjaman</p>
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <button onclick="toggleDarkMode()"
                class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="darkModeIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                    </path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filters -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6 transition-colors">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Filter
                        Status</label>
                    <select id="filterStatus"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 dark:text-white transition-all">
                        <option value="">Semua Status</option>
                        <option value="dipinjam">Sedang Dipinjam</option>
                        <option value="dikembalikan">Sudah Dikembalikan</option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button onclick="loadHistori()"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-medium py-2.5 px-5 rounded-lg transition shadow-sm flex-1 md:flex-none justify-center">
                        Terapkan Filter
                    </button>
                    <a href="/src/api/export_histori.php" target="_blank"
                        class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium py-2.5 px-5 rounded-lg transition inline-flex items-center gap-2 shadow-sm flex-1 md:flex-none justify-center">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Histori List -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead
                        class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-xs font-semibold uppercase tracking-wider transition-colors">
                        <tr>
                            <th class="px-6 py-3 text-left">Nomor BMN</th>
                            <th class="px-6 py-3 text-left">Peminjam</th>
                            <th class="px-6 py-3 text-left">NIP</th>
                            <th class="px-6 py-3 text-left">Waktu Pinjam</th>
                            <th class="px-6 py-3 text-left">Waktu Kembali</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody id="historiTableBody"
                        class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 text-sm transition-colors">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <span
                                    class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></span>
                                <p>Memuat data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script src="/src/assets/js/main.js"></script>
    <script>
        async function loadHistori() {
            const status = document.getElementById('filterStatus').value;

            let url = 'get_histori.php?limit=100';
            if (status) url += `&status=${status}`;

            const result = await apiCall(url);
            const tbody = document.getElementById('historiTableBody');

            if (result.success && result.data.length > 0) {
                tbody.innerHTML = result.data.map(item => `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">${item.nomor_bmn}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">${item.nama_peminjam || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400 font-mono text-xs">${item.nip_peminjam}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">${formatTanggal(item.waktu_pinjam)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">${item.waktu_kembali ? formatTanggal(item.waktu_kembali) : '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md border ${item.status === 'dipinjam' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800'}">
                                ${item.status === 'dipinjam' ? 'Sedang Dipinjam' : 'Dikembalikan'}
                            </span>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm">Tidak ada data ditemukan</p>
                        </td>
                    </tr>
                `;
            }
        }

        // Load on page load
        loadHistori();
    </script>
    <script src="/src/assets/js/main.js?v=<?= time() ?>"></script>
</body>

</html>