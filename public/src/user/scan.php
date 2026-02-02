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
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>

<body class="dark:bg-slate-900 min-h-screen transition-colors duration-200">

    <!-- Header -->
    <header
        class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="dashboard.php"
                        class="flex-shrink-0 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 active:text-slate-700 dark:active:text-slate-300 p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="flex-1 min-w-0">
                        <h1
                            class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white truncate transition-colors">
                            Scan QR Code</h1>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate transition-colors">
                            Pilih metode scan yang Anda inginkan</p>
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
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">

        <!-- Mode Switcher -->
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-md border border-slate-200 dark:border-slate-700 p-4 mb-4 sm:mb-6 transition-colors">
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-3 font-medium transition-colors">Pilih
                Metode Scan:</p>
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                <button id="camera-mode-btn" onclick="switchScannerMode('camera')"
                    class="flex items-center justify-center gap-2 px-3 sm:px-4 py-3 rounded-lg font-semibold transition bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white active:bg-blue-800 touch-manipulation text-sm sm:text-base">
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
                    class="flex items-center justify-center gap-2 px-3 sm:px-4 py-3 rounded-lg font-semibold transition bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 active:bg-slate-400 dark:active:bg-slate-500 touch-manipulation text-sm sm:text-base">
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
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden mb-6 transition-colors">
                <div id="qr-reader" class="w-full"></div>
            </div>

            <!-- Camera Instructions -->
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6 transition-colors">
                <div class="flex">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-1 transition-colors">Cara Scan
                            dengan Kamera:</h4>
                        <ol
                            class="text-sm text-blue-800 dark:text-blue-300 space-y-1 list-decimal list-inside transition-colors">
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
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-8 mb-6 transition-colors">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-slate-400 dark:text-slate-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2 transition-colors">Upload
                        Gambar QR Code</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 transition-colors">Pilih gambar QR Code
                        dari galeri atau file manager Anda</p>

                    <label for="qr-file-input"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold rounded-lg cursor-pointer transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Pilih Gambar
                    </label>
                    <input type="file" id="qr-file-input" accept="image/*" class="hidden">

                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-4 transition-colors">Format: JPG, PNG, atau
                        gambar lainnya yang mengandung QR Code
                    </p>
                </div>
            </div>

            <!-- File Instructions -->
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6 transition-colors">
                <div class="flex">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-3 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-green-900 dark:text-green-200 mb-1 transition-colors">Cara Upload
                            Gambar:</h4>
                        <ol
                            class="text-sm text-green-800 dark:text-green-300 space-y-1 list-decimal list-inside transition-colors">
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

    <script src="/src/assets/js/main.js?v=3"></script>
    <link rel="stylesheet" href="/src/assets/css/light-mode-override.css?v=3">
    <script src="/src/assets/js/qr-scanner.js?v=3"></script>
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