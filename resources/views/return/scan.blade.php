@extends('layouts.app')

@section('title', 'Scan Pengembalian - Sistem Peminjaman BMN')
@section('header_title', 'Scan Pengembalian')
@section('header_subtitle', 'Scan QR untuk proses pengembalian barang')

@section('content')
    <main class="max-w-2xl mx-auto" x-data="returnPage()">

        <!-- Mode Switcher -->
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-md border border-slate-200 dark:border-slate-700 p-4 mb-6 transition-colors">
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-3 font-medium transition-colors">Pilih Metode Scan:</p>
            <div class="grid grid-cols-2 gap-3">
                <button @click="mode = 'camera'"
                    :class="mode === 'camera' ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200'"
                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Kamera</span>
                </button>
                <button @click="mode = 'file'"
                    :class="mode === 'file' ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200'"
                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Upload</span>
                </button>
            </div>
        </div>

        <!-- Camera Scanner Container -->
        <div x-show="mode === 'camera'" class="mb-6">
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden mb-6 transition-colors">
                <div id="qr-reader" class="w-full"></div>
            </div>
            <!-- Tips -->
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 transition-colors">
                <div class="flex">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-1">Cara Scan:</h4>
                        <ol class="text-sm text-blue-800 dark:text-blue-300 space-y-1 list-decimal list-inside">
                            <li>Izinkan akses kamera saat diminta</li>
                            <li>Arahkan kamera ke QR Code barang</li>
                            <li>Konfirmasi kondisi barang sebelum pengembalian</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Upload Container -->
        <div x-show="mode === 'file'" x-cloak class="mb-6">
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-8 mb-6 transition-colors">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Upload Gambar QR</h3>
                    <label
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg cursor-pointer transition">
                        Pilih Gambar
                        <input type="file" @change="handleFileUpload" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>
        </div>
        </div>

        <!-- Confirmation & Damage Form -->
        <div x-show="scannedCode && !resultHtml" x-cloak class="space-y-6">

            <!-- Barang Terdeteksi Box -->
            <div
                class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-xl p-5 text-center">
                <p class="text-sm text-indigo-600 dark:text-indigo-300 mb-1">Barang Terdeteksi:</p>
                <p class="text-2xl font-bold text-indigo-900 dark:text-white font-mono" x-text="scannedCode"></p>
                <p class="text-xs text-indigo-500 dark:text-indigo-400 mt-2" x-show="isRawBPS">Formatted from BPS QR</p>
            </div>

            <!-- Damage Form -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-md border border-slate-200 dark:border-slate-700 p-6 transition-colors">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Laporan Kondisi Barang</h3>

                <div class="flex items-center mb-6">
                    <input id="damaged-checkbox" type="checkbox" x-model="isDamaged"
                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="damaged-checkbox" class="ml-2 text-sm font-medium text-slate-900 dark:text-slate-300">
                        Barang mengalami kerusakan
                    </label>
                </div>

                <div x-show="isDamaged" x-transition class="space-y-4 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-900 dark:text-white">Jenis Kerusakan</label>
                        <select x-model="damageType"
                            class="bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="ringan">Ringan (Lecet/Gores)</option>
                            <option value="berat">Berat (Pecah/Mati/Rusak Fungsi)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-900 dark:text-white">Deskripsi
                            Kerusakan</label>
                        <textarea x-model="damageDescription" rows="3"
                            class="block p-2.5 w-full text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-slate-400 dark:text-white"
                            placeholder="Jelaskan detail kerusakan yang terjadi..."></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <button @click="processReturn()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Konfirmasi Pengembalian
                    </button>
                    <button @click="resetScan()"
                        class="w-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Batal / Scan Ulang
                    </button>
                </div>
            </div>
        </div>

        <!-- Scan Result -->
        <div id="scan-result" x-html="resultHtml"></div>

    </main>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('returnPage', () => ({
                    mode: 'camera',
                    scannedCode: null,
                    isDamaged: false,
                    isRawBPS: false,
                    damageType: 'ringan',
                    damageDescription: '',
                    resultHtml: '',
                    html5QrCode: null,

                    init() {
                        // Check for URL param 'code' from detail_barang.php
                        const urlParams = new URLSearchParams(window.location.search);
                        const code = urlParams.get('code');

                        if (code) {
                            console.log('Auto-scanning from URL:', code);
                            this.handleScan(code);
                        } else {
                            // Only start scanner if no code provided
                            setTimeout(() => {
                                this.startScanner();
                            }, 500);
                        }

                        // Watch mode to switch scanner
                        this.$watch('mode', (value) => {
                            this.switchScannerMode(value);
                        });
                    },

                    async startScanner() {
                        if (this.mode !== 'camera') return;

                        const qrReaderElement = document.getElementById('qr-reader');
                        if (!qrReaderElement) return;

                        if (!window.isSecureContext) {
                            this.resultHtml = `<div class="bg-red-50 text-red-700 p-4 rounded">
                                Akses kamera hanya bisa di HTTPS atau localhost.
                            </div>`;
                            return;
                        }

                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            this.resultHtml = `<div class="bg-red-50 text-red-700 p-4 rounded">
                                Browser tidak mendukung akses kamera.
                            </div>`;
                            return;
                        }

                        if (typeof Html5Qrcode === 'undefined') {
                            this.resultHtml = `<div class="bg-red-50 text-red-700 p-4 rounded">
                                Library scanner tidak termuat.
                            </div>`;
                            return;
                        }

                        // Cleanup existing instance if any
                        if (this.html5QrCode) {
                            try {
                                await this.html5QrCode.stop();
                                this.html5QrCode.clear();
                            } catch (e) {
                                console.log('Cleanup error', e);
                            }
                        }

                        this.html5QrCode = new Html5Qrcode("qr-reader");
                        const config = {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        };

                        try {
                            await this.html5QrCode.start({
                                facingMode: "environment"
                            },
                                config,
                                (decodedText) => this.handleScan(decodedText),
                                (errorMessage) => { /* ignore */ }
                            );
                        } catch (err) {
                            console.error("Error starting scanner", err);
                            this.resultHtml = `<div class="bg-red-50 text-red-700 p-4 rounded">Gagal akses kamera: ${err.message || 'Unknown error'}</div>`;
                        }
                    },

                    async stopScanner() {
                        if (this.html5QrCode) {
                            try {
                                if (this.html5QrCode.isScanning) {
                                    await this.html5QrCode.stop();
                                }
                                this.html5QrCode.clear();
                            } catch (e) {
                                // ignore stop errors
                            }
                        }
                    },

                    async switchScannerMode(newMode) {
                        await this.stopScanner();
                        if (newMode === 'camera') {
                            setTimeout(() => this.startScanner(), 300);
                        }
                    },

                    async handleScan(decodedText) {
                        console.log('Raw Scan:', decodedText);
                        await this.stopScanner();

                        // Parse BPS QR Code if detected (Format: INV-...*...*CODE*NUP)
                        if (decodedText.includes('*')) {
                            const parts = decodedText.split('*');
                            // Usually parts[2] is Code, parts[3] is NUP
                            if (parts.length >= 4) {
                                this.scannedCode = parts[2].trim() + '-' + parts[3].trim();
                                this.isRawBPS = true;
                            } else {
                                this.scannedCode = decodedText;
                                this.isRawBPS = false;
                            }
                        } else {
                            this.scannedCode = decodedText;
                            this.isRawBPS = false;
                        }

                        // Clear resultHtml (to show form)
                        this.resultHtml = '';
                    },

                    async processReturn() {
                        if (!this.scannedCode) return;

                        // Show loading state
                        this.resultHtml = `
                                            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 text-center border border-slate-200 dark:border-slate-700">
                                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                                <p class="text-slate-600 dark:text-slate-400">Memproses pengembalian...</p>
                                            </div>
                                        `;

                        try {
                            const payload = {
                                nomor_bmn: this.scannedCode,
                                is_damaged: this.isDamaged,
                                jenis_kerusakan: this.damageType,
                                deskripsi: this.damageDescription
                            };

                            console.log('Sending payload:', payload);

                            const response = await fetch("{{ route('return.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(payload)
                            });

                            const result = await response.json();

                            if (result.success) {
                                this.resultHtml = `
                                                                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                                                                        <div class="flex items-center mb-4 gap-4">
                                                                                            <div class="bg-green-100 dark:bg-green-800 rounded-full p-2">
                                                                                                <svg class="w-8 h-8 text-green-600 dark:text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                                                </svg>
                                                                                            </div>
                                                                                            <div class="text-left">
                                                                                                <h3 class="text-lg font-bold text-green-900 dark:text-green-200">Berhasil!</h3>
                                                                                                <p class="text-green-800 dark:text-green-300">${result.message}</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <button @click="resetScan()" 
                                                                                            class="w-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold py-3 px-4 rounded-lg transition">
                                                                                            Scan Barang Lain
                                                                                        </button>
                                                                                    </div>
                                                                                `;
                            } else {
                                throw new Error(result.message);
                            }
                        } catch (error) {
                            this.resultHtml = `
                                                                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                                                                    <div class="flex items-center mb-4 gap-4">
                                                                                        <div class="bg-red-100 dark:bg-red-800 rounded-full p-2">
                                                                                            <svg class="w-8 h-8 text-red-600 dark:text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                                            </svg>
                                                                                        </div>
                                                                                        <div class="text-left">
                                                                                            <h3 class="text-lg font-bold text-red-900 dark:text-red-200">Gagal</h3>
                                                                                            <p class="text-red-800 dark:text-red-300">${error.message || 'Terjadi kesalahan sistem'}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <button @click="resetScan()" 
                                                                                        class="w-full bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700 text-red-800 dark:text-red-200 font-semibold py-3 px-4 rounded-lg transition">
                                                                                        Coba Lagi
                                                                                    </button>
                                                                                </div>
                                                                            `;
                        }
                    },

                    resetScan() {
                        this.scannedCode = null;
                        this.resultHtml = null;
                        this.isDamaged = false;
                        this.damageType = 'ringan';
                        this.damageDescription = '';
                        this.mode = 'camera';
                        this.startScanner();
                    },

                    handleFileUpload(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        // Create a temporary instance for file scanning
                        const scanner = new Html5Qrcode("qr-reader");

                        // Show loading state
                        this.resultHtml = `
                                                                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 text-center border border-slate-200 dark:border-slate-700">
                                                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                                                        <p class="text-slate-600 dark:text-slate-400">Memproses gambar...</p>
                                                                    </div>
                                                                `;

                        scanner.scanFile(file, true)
                            .then(decodedText => {
                                scanner.clear();
                                this.handleScan(decodedText);
                            })
                            .catch(err => {
                                console.error('File scan error:', err);
                                scanner.clear();
                                this.resultHtml = `
                                                                            <div class="bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 text-center">
                                                                                <p class="font-bold">Gagal membaca QR Code.</p>
                                                                                <p class="text-sm">Pastikan gambar jelas dan memuat QR Code yang valid.</p>
                                                                            </div>
                                                                        `;
                            });
                    }
                }))
            })
        </script>
    @endpush
@endsection
