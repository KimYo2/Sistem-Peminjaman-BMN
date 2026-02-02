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
    <title>Daftar Barang - Sistem Peminjaman BMN</title>
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
                    class="p-2 -ml-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">Daftar
                        Barang</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">Kelola
                        inventaris BMN</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()"
                    class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="darkModeIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                <a href="tambah_barang.php"
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Barang
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filters -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6 transition-colors">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Filter
                        Ketersediaan</label>
                    <select id="filterKetersediaan"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                        <option value="">Semua</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Pencarian</label>
                    <input type="text" id="searchInput" placeholder="Cari nomor BMN, brand, atau tipe..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition placeholder-slate-400">
                </div>
                <div class="flex items-end">
                    <button onclick="loadBarang()"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg transition shadow-sm">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Barang List -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead
                        class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider transition-colors">
                        <tr>
                            <th class="px-6 py-3 text-left">Nomor BMN</th>
                            <th class="px-6 py-3 text-left">Brand</th>
                            <th class="px-6 py-3 text-left">Tipe</th>
                            <th class="px-6 py-3 text-left">Kondisi</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Peminjam</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="barangTableBody"
                        class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 text-sm transition-colors">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <span
                                    class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mb-2"></span>
                                <p>Memuat data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script src="/src/assets/js/main.js?v=<?= time() ?>"></script>
    <script>
        async function loadBarang() {
            const ketersediaan = document.getElementById('filterKetersediaan').value;
            const search = document.getElementById('searchInput').value;

            let url = 'get_barang.php?';
            if (ketersediaan) url += `ketersediaan=${ketersediaan}&`;
            if (search) url += `search=${encodeURIComponent(search)}&`;

            const result = await apiCall(url);
            const tbody = document.getElementById('barangTableBody');

            if (result.success && result.data.length > 0) {
                tbody.innerHTML = result.data.map(item => `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">${item.nomor_bmn}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">${item.brand}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">${item.tipe}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium ${getKondisiBadge(item.kondisi_terakhir)}">
                                <span class="w-1.5 h-1.5 rounded-full ${getKondisiDot(item.kondisi_terakhir)}"></span>
                                ${formatKondisi(item.kondisi_terakhir)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md ${getKetersediaanBadge(item.ketersediaan)}">
                                ${formatStatus(item.ketersediaan)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400 font-mono text-xs">
                            ${item.peminjam_terakhir || '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="edit_barang.php?nomor_bmn=${item.nomor_bmn}" 
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button onclick="deleteBarang('${item.nomor_bmn}')"
                                    class="text-rose-600 hover:text-rose-900 dark:text-rose-400 dark:hover:text-rose-300 p-1 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-sm">Tidak ada data barang</p>
                        </td>
                    </tr>
                `;
            }
        }

        // Helpers
        function formatKondisi(str) {
            return str.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function formatStatus(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Custom badges with stricter colors
        function getKondisiBadge(kondisi) {
            const k = (kondisi || '').toLowerCase();
            if (k === 'baik') return 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800';
            if (k === 'rusak_ringan' || k === 'rusak ringan') return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800';
            return 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800';
        }

        function getKondisiDot(kondisi) {
            const k = (kondisi || '').toLowerCase();
            if (k === 'baik') return 'bg-green-500';
            if (k === 'rusak_ringan' || k === 'rusak ringan') return 'bg-yellow-500';
            return 'bg-red-500';
        }

        function getKetersediaanBadge(status) {
            if (status === 'tersedia') return 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300';
            return 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300';
        }

        async function deleteBarang(nomorBmn) {
            if (!confirm(`Apakah Anda yakin ingin menghapus barang ${nomorBmn}?`)) {
                return;
            }

            const result = await apiCall('../api/delete_barang.php', 'POST', {
                nomor_bmn: nomorBmn
            });

            if (result.success) {
                showToast('Barang berhasil dihapus', 'success');
                loadBarang();
            } else {
                showToast(result.message, 'error');
            }
        }

        // Load on page load
        loadBarang();
    </script>
</body>

</html>