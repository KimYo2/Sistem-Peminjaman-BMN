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
    <title>Scan Pengembalian - Sistem Peminjaman BMN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center">
                <a href="dashboard.php" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Scan Pengembalian</h1>
                    <p class="text-sm text-gray-600">Scan QR untuk proses pengembalian barang</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- QR Scanner -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div id="qr-reader" class="w-full"></div>
        </div>

        <!-- Instructions -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-green-900 mb-1">Cara Menggunakan:</h4>
                    <ol class="text-sm text-green-800 space-y-1 list-decimal list-inside">
                        <li>Izinkan akses kamera saat diminta</li>
                        <li>Arahkan kamera ke QR Code barang yang dikembalikan</li>
                        <li>Sistem akan otomatis memproses pengembalian</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Scan Result -->
        <div id="scan-result"></div>

    </main>

    <script src="/src/assets/js/main.js"></script>
    <script src="/src/assets/js/qr-scanner.js"></script>
    <script>
        // Override handleQRScan for admin return flow
        async function handleQRScanReturn(decodedText, decodedResult) {
            console.log('QR Code detected:', decodedText);

            // Stop scanner
            stopQRScanner();

            // Parse BMN
            const nomorBMN = parseBPSQRCode(decodedText);
            if (!nomorBMN) {
                showToast('Format QR Code tidak valid', 'error');
                setTimeout(() => location.reload(), 2000);
                return;
            }

            // Show loading
            const resultDiv = document.getElementById('scan-result');
            resultDiv.innerHTML = `
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Memproses pengembalian...</p>
                    <p class="text-sm font-semibold mt-2 text-blue-600">${nomorBMN}</p>
                </div>
            `;

            // Call API to return item
            const result = await apiCall('kembalikan_barang.php', 'POST', { nomor_bmn: nomorBMN });

            if (result.success) {
                resultDiv.innerHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-12 h-12 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-green-900">Pengembalian Berhasil!</h3>
                                <p class="text-sm text-green-700">Barang ${decodedText} telah dikembalikan</p>
                            </div>
                        </div>
                        <button onclick="location.reload()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Scan Lagi
                        </button>
                    </div>
                `;
                showToast('Pengembalian berhasil!', 'success');
            } else {
                resultDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-12 h-12 text-red-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-red-900">Gagal!</h3>
                                <p class="text-sm text-red-700">${result.message}</p>
                            </div>
                        </div>
                        <button onclick="location.reload()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Coba Lagi
                        </button>
                    </div>
                `;
                showToast(result.message, 'error');
            }
        }

        // Initialize scanner on page load
        document.addEventListener('DOMContentLoaded', () => {
            initQRScanner(handleQRScanReturn);
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            stopQRScanner();
        });
    </script>
</body>

</html>