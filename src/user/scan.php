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
    <title>Scan QR Code - Sistem Peminjaman BMN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="dashboard.php"
                    class="flex-shrink-0 text-gray-600 hover:text-gray-900 active:text-gray-700 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">Scan QR Code</h1>
                    <p class="text-xs sm:text-sm text-gray-600 truncate">Pilih metode scan yang Anda inginkan</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">

        <!-- Mode Switcher -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-4 sm:mb-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-3 font-medium">Pilih Metode Scan:</p>
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                <button id="camera-mode-btn" onclick="switchScannerMode('camera')"
                    class="flex items-center justify-center gap-2 px-3 sm:px-4 py-3 rounded-lg font-semibold transition bg-blue-600 text-white active:bg-blue-700 touch-manipulation text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="hidden xs:inline sm:inline">Kamera</span>
                </button>
                <button id="file-mode-btn" onclick="switchScannerMode('file')"
                    class="flex items-center justify-center gap-2 px-3 sm:px-4 py-3 rounded-lg font-semibold transition bg-gray-200 text-gray-700 active:bg-gray-300 touch-manipulation text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="hidden xs:inline sm:inline">Upload</span>
                </button>
            </div>
        </div>

        <!-- Camera Scanner Container -->
        <div id="camera-container">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <div id="qr-reader" class="w-full"></div>
            </div>

            <!-- Camera Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-1">Cara Scan dengan Kamera:</h4>
                        <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                            <li>Izinkan akses kamera saat diminta</li>
                            <li>Arahkan kamera ke QR Code pada barang</li>
                            <li>Tunggu hingga QR Code terbaca otomatis</li>
                            <li>Anda akan diarahkan ke halaman detail barang</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Upload Container -->
        <div id="file-container" class="hidden">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Upload Gambar QR Code</h3>
                    <p class="text-sm text-gray-600 mb-6">Pilih gambar QR Code dari galeri atau file manager Anda</p>

                    <label for="qr-file-input"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg cursor-pointer transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Pilih Gambar
                    </label>
                    <input type="file" id="qr-file-input" accept="image/*" class="hidden">

                    <p class="text-xs text-gray-500 mt-4">Format: JPG, PNG, atau gambar lainnya yang mengandung QR Code
                    </p>
                </div>
            </div>

            <!-- File Instructions -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-green-900 mb-1">Cara Upload Gambar:</h4>
                        <ol class="text-sm text-green-800 space-y-1 list-decimal list-inside">
                            <li>Klik tombol "Pilih Gambar"</li>
                            <li>Pilih foto/screenshot QR Code dari galeri</li>
                            <li>Sistem akan otomatis membaca QR Code</li>
                            <li>Anda akan diarahkan ke halaman detail barang</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scan Result -->
        <div id="scan-result"></div>

    </main>

    <script src="/src/assets/js/main.js?v=<?= time() ?>"></script>
    <script src="/src/assets/js/qr-scanner.js?v=<?= time() ?>"></script>
    <script>
        // Initialize camera scanner on page load (default mode)
        document.addEventListener('DOMContentLoaded', () => {
            initQRScanner(handleQRScan);
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            stopQRScanner();
        });
    </script>
</body>

</html>