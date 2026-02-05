@extends('layouts.app')

@section('title', 'Scan QR Code - Sistem Peminjaman BMN')

@section('content')
    <main class="max-w-2xl mx-auto" x-data="borrowScanPage()">

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
                            <li>Anda akan diarahkan ke detail barang</li>
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

        <!-- Scan Result -->
        <div id="scan-result" x-html="resultHtml"></div>

    </main>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('borrowScanPage', () => ({
                    mode: 'camera',
                    resultHtml: '',
                    html5QrCode: null,

                    init() {
                        this.$watch('mode', (value) => {
                            this.switchScannerMode(value);
                        });

                        setTimeout(() => {
                            this.startScanner();
                        }, 300);
                    },

                    async startScanner() {
                        if (this.mode !== 'camera') return;

                        const qrReaderElement = document.getElementById('qr-reader');
                        if (!qrReaderElement) return;

                        const cameraError = window.QrScan.getCameraError();
                        if (cameraError) {
                            this.resultHtml = `<div class="bg-red-50 text-red-700 p-4 rounded">${cameraError}</div>`;
                            return;
                        }

                        await this.stopScanner();

                        try {
                            this.html5QrCode = await window.QrScan.startCamera({
                                elementId: 'qr-reader',
                                onDecoded: (decodedText) => this.handleScan(decodedText),
                            });
                        } catch (err) {
                            this.resultHtml = `<div class="bg-red-50 text-red-700 p-4 rounded">
                                Gagal akses kamera: ${err.message || 'Unknown error'}
                            </div>`;
                        }
                    },

                    async stopScanner() {
                        await window.QrScan.stopCamera(this.html5QrCode);
                        this.html5QrCode = null;
                    },

                    async switchScannerMode(newMode) {
                        await this.stopScanner();
                        this.resultHtml = '';
                        if (newMode === 'camera') {
                            setTimeout(() => this.startScanner(), 300);
                        }
                    },

                    handleScan(decodedText) {
                        const parsed = window.QrScan.parseBmn(decodedText);
                        const targetUrl = `{{ url('/barang') }}/${encodeURIComponent(parsed)}`;
                        window.location.href = targetUrl;
                    },

                    async handleFileUpload(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        this.resultHtml = `
                            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 text-center border border-slate-200 dark:border-slate-700">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                <p class="text-slate-600 dark:text-slate-400">Memproses gambar...</p>
                            </div>
                        `;

                        try {
                            const decodedText = await window.QrScan.scanFile(file, 'qr-reader');
                            this.handleScan(decodedText);
                        } catch (err) {
                            console.error('File scan error:', err);
                            this.resultHtml = `
                                <div class="bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 text-center">
                                    <p class="font-bold">Gagal membaca QR Code.</p>
                                    <p class="text-sm">Pastikan gambar jelas dan memuat QR Code yang valid.</p>
                                </div>
                            `;
                        }
                    }
                }));
            });
        </script>
    @endpush
@endsection
