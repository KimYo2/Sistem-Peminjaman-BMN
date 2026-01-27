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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">Dashboard Pegawai</h1>
                    <p class="text-xs sm:text-sm text-gray-600 truncate">Selamat datang,
                        <?= htmlspecialchars($user['nama']) ?>
                    </p>
                </div>
                <button onclick="logout()"
                    class="w-full sm:w-auto flex-shrink-0 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition text-sm sm:text-base font-medium">
                    Logout
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">

            <!-- Scan QR Card -->
            <a href="scan.php"
                class="block bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl sm:rounded-2xl shadow-lg hover:shadow-xl transition p-6 sm:p-8 text-white active:scale-95">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl sm:text-2xl font-bold mb-1 sm:mb-2">Scan QR Code</h3>
                        <p class="text-sm sm:text-base text-blue-100">Pindai QR barang untuk meminjam</p>
                    </div>
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-blue-200 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                </div>
            </a>

            <!-- Peminjaman Aktif Card -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Peminjaman Aktif</h3>
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div id="aktivePeminjaman" class="space-y-2 sm:space-y-3">
                    <div class="text-center py-4 text-gray-500">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                        <p class="text-sm">Loading...</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Histori Peminjaman -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Histori Peminjaman Saya</h3>

            <div id="historiList" class="space-y-4">
                <div class="text-center py-8 text-gray-500">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    Loading histori...
                </div>
            </div>
        </div>

    </main>

    <script src="/src/assets/js/main.js"></script>
    <script>
        // Load peminjaman aktif
        async function loadAktivePeminjaman() {
            const result = await apiCall('get_histori.php?status=dipinjam&limit=10');
            const containerEl = document.getElementById('aktivePeminjaman');

            if (result.success && result.data.length > 0) {
                containerEl.innerHTML = result.data.map(item => `
                    <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm sm:text-base text-gray-900 truncate">${item.nomor_bmn}</h4>
                                ${item.brand && item.tipe ? `<p class="text-xs sm:text-sm text-gray-700 mt-1 truncate">${item.brand} - ${item.tipe}</p>` : ''}
                                <p class="text-xs text-gray-500 mt-1">Dipinjam: ${formatTanggal(item.waktu_pinjam)}</p>
                            </div>
                            <button onclick="kembalikanDariDashboard('${item.nomor_bmn}')" 
                                class="w-full sm:w-auto flex-shrink-0 px-4 py-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold rounded-lg transition touch-manipulation">
                                Kembalikan
                            </button>
                        </div>
                    </div>
                `).join('');
            } else {
                containerEl.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-sm">Tidak ada peminjaman aktif</p>
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
                    <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 sm:gap-4">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm sm:text-base text-gray-900 break-words">${item.nomor_bmn}</h4>
                                ${item.brand && item.tipe ? `<p class="text-xs sm:text-sm text-gray-700 mt-1 break-words">${item.brand} - ${item.tipe}</p>` : ''}
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">Dipinjam: ${formatTanggal(item.waktu_pinjam)}</p>
                                ${item.waktu_kembali ? `<p class="text-xs sm:text-sm text-gray-600">Dikembalikan: ${formatTanggal(item.waktu_kembali)}</p>` : ''}
                            </div>
                            <span class="self-start px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap ${item.status === 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                ${item.status === 'dipinjam' ? 'Sedang Dipinjam' : 'Dikembalikan'}
                            </span>
                        </div>
                    </div>
                `).join('');
            } else {
                listEl.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Belum ada histori peminjaman</p>
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