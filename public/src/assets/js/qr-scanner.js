/**
 * QR Scanner JavaScript
 * Menggunakan library html5-qrcode untuk scan QR
 * Support: Camera dan File Upload
 */

let html5QrCode = null;
let currentMode = 'camera'; // 'camera' or 'file'

/**
 * Initialize QR Scanner (Camera Mode)
 */
async function initQRScanner(onScanSuccess) {
    const qrReaderElement = document.getElementById('qr-reader');

    if (!qrReaderElement) {
        console.error('QR Reader element not found');
        return;
    }

    html5QrCode = new Html5Qrcode("qr-reader");

    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };

    try {
        await html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            (errorMessage) => {
                // Ignore scan errors (too frequent)
            }
        );
        currentMode = 'camera';
    } catch (err) {
        console.error('Error starting QR scanner:', err);

        // Detailed error handling
        let errorMessage = 'Gagal mengakses kamera. Pastikan izin kamera diberikan.';
        let detailedHelp = '';
        let isSecureContextError = false;

        // Check for insecure context (HTTP on non-localhost)
        if (location.protocol !== 'https:' &&
            location.hostname !== 'localhost' &&
            location.hostname !== '127.0.0.1' &&
            !location.hostname.startsWith('192.168.')) { // 192.168 might be allowed by some flags but usually blocked
            // Actually, browsers block mostly everything non-localhost.
        }

        // Specific error messages
        if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
            errorMessage = 'Izin kamera ditolak.';
            detailedHelp = 'Silakan izinkan akses kamera di pengaturan browser Anda.';
        } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
            errorMessage = 'Kamera tidak ditemukan.';
            detailedHelp = 'Pastikan perangkat Anda memiliki kamera yang berfungsi.';
        } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
            errorMessage = 'Kamera sedang digunakan aplikasi lain.';
            detailedHelp = 'Tutup aplikasi lain yang menggunakan kamera.';
        } else if (err.name === 'OverconstrainedError') {
            errorMessage = 'Kamera tidak kompatibel.';
            detailedHelp = 'Kamera depan/belakang yang diminta tidak tersedia.';
        } else if (err.name === 'SecurityError' || err.name === 'SecureContextCodes' || !window.isSecureContext) {
            isSecureContextError = true;
            errorMessage = 'Akses Kamera Dibatasi Browser';
            detailedHelp = `
                Browser memblokir kamera di jaringan lokal (HTTP).<br>
                Solusi:<br>
                1. Gunakan fitur <strong>Upload Gambar</strong> di bawah.<br>
                2. Atau atur browser flags: <code>chrome://flags/#unsafely-treat-insecure-origin-as-secure</code>
            `;
        }

        // Show toast
        showToast(errorMessage, 'error');

        // Inject helper UI into the reader element
        qrReaderElement.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full p-6 text-center bg-gray-50 dark:bg-slate-800 rounded-lg">
                <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">${errorMessage}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                    ${detailedHelp || 'Silakan cek pengaturan izin browser Anda.'}
                </p>
                ${isSecureContextError ? `
                <button onclick="switchScannerMode('file')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Gunakan Upload Gambar
                </button>
                ` : `
                <button onclick="location.reload()" class="bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-800 dark:text-gray-200 font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Coba Lagi
                </button>
                `}
            </div>
        `;
    }
}

/**
 * Initialize File Scanner
 */
function initFileScanner(onScanSuccess) {
    const fileInput = document.getElementById('qr-file-input');

    if (!fileInput) {
        console.error('File input element not found');
        return;
    }

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("qr-reader");
    }

    fileInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        try {
            // Show loading
            const resultDiv = document.getElementById('scan-result');
            if (resultDiv) {
                resultDiv.innerHTML = `
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-4 text-gray-600">Membaca QR Code dari gambar...</p>
                    </div>
                `;
            }

            // Scan file
            const decodedText = await html5QrCode.scanFile(file, true);
            onScanSuccess(decodedText);
        } catch (err) {
            console.error('Error scanning file:', err);
            showToast('Gagal membaca QR Code dari gambar. Pastikan gambar mengandung QR Code yang jelas.', 'error');

            // Clear result
            const resultDiv = document.getElementById('scan-result');
            if (resultDiv) {
                resultDiv.innerHTML = '';
            }
        }
    });

    currentMode = 'file';
}

/**
 * Switch Scanner Mode
 */
async function switchScannerMode(mode) {
    // Stop current scanner if running
    await stopQRScanner();

    // Hide/show elements based on mode
    const cameraContainer = document.getElementById('camera-container');
    const fileContainer = document.getElementById('file-container');
    const cameraModeBtn = document.getElementById('camera-mode-btn');
    const fileModeBtn = document.getElementById('file-mode-btn');

    if (mode === 'camera') {
        cameraContainer.classList.remove('hidden');
        fileContainer.classList.add('hidden');
        cameraModeBtn.classList.add('bg-blue-600', 'text-white');
        cameraModeBtn.classList.remove('bg-gray-200', 'text-gray-700');
        fileModeBtn.classList.remove('bg-blue-600', 'text-white');
        fileModeBtn.classList.add('bg-gray-200', 'text-gray-700');

        // Start camera scanner
        await initQRScanner(handleQRScan);
    } else {
        cameraContainer.classList.add('hidden');
        fileContainer.classList.remove('hidden');
        fileModeBtn.classList.add('bg-blue-600', 'text-white');
        fileModeBtn.classList.remove('bg-gray-200', 'text-gray-700');
        cameraModeBtn.classList.remove('bg-blue-600', 'text-white');
        cameraModeBtn.classList.add('bg-gray-200', 'text-gray-700');

        // Initialize file scanner
        initFileScanner(handleQRScan);
    }
}

/**
 * Stop QR Scanner
 */
async function stopQRScanner() {
    if (html5QrCode && currentMode === 'camera') {
        try {
            await html5QrCode.stop();
            html5QrCode.clear();
        } catch (err) {
            console.error('Error stopping scanner:', err);
        }
    }
}

/**
 * Handle QR Scan Success
 */
function handleQRScan(decodedText, decodedResult) {
    console.log('QR Code detected:', decodedText);

    // Stop scanner
    stopQRScanner();

    // Parse BMN from BPS QR format
    const nomorBMN = parseBPSQRCode(decodedText);

    if (!nomorBMN) {
        showToast('Format QR Code tidak valid', 'error');
        // Reload to retry
        setTimeout(() => location.reload(), 2000);
        return;
    }

    // Process the scanned BMN number
    processScannedBMN(nomorBMN);
}

/**
 * Parse BPS QR Code Format
 * Format: INV-xxx*xxx*xxx*BMN_NUMBER*xxx
 * Example: INV-20210420145333129398000*054010300C*190000000KD*3100102001*37
 * Returns: 3100102001 (BMN number from 4th segment)
 */
function parseBPSQRCode(qrText) {
    console.log('Parsing QR:', qrText);

    // Check if QR contains asterisk delimiter (BPS format)
    if (qrText.includes('*')) {
        // Split by asterisk
        const parts = qrText.split('*');
        console.log('QR Parts:', parts);

        // Priority: Extract specific indices as requested
        // Index 2: Kode Barang (e.g., 3100102001)
        // Index 3: NUP (e.g., 37)
        if (parts.length >= 4) {
            const kodeBarang = parts[2].trim();
            const nup = parts[3].trim();

            // Format: KodeBarang-NUP
            const combined = `${kodeBarang}-${nup}`;
            console.log('Extracted Kode Barang & NUP:', combined);
            return combined;
        }

        // Fallback: Search for segment starting with 31 (BMN code prefix)
        // This is kept as backup if the structure is different but contains the code
        for (let part of parts) {
            // Match 31 followed by digits
            if (part.match(/^31\d+/)) {
                console.log('Found BMN pattern (fallback):', part);
                return part.trim();
            }
        }
    }

    // If no asterisk, assume it's the BMN number/code
    const trimmed = qrText.trim();
    console.log('Direct BMN:', trimmed);
    return trimmed;
}

/**
 * Process scanned BMN number
 */
async function processScannedBMN(nomorBMN) {
    // Show loading
    const resultDiv = document.getElementById('scan-result');
    if (resultDiv) {
        resultDiv.innerHTML = `
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Memuat data barang...</p>
            </div>
        `;
    }

    // Call API to get item details
    const result = await apiCall('scan_qr.php', 'POST', { nomor_bmn: nomorBMN });

    if (result.success) {
        // Redirect to detail page with BMN number
        window.location.href = `/src/user/detail_barang.php?nomor_bmn=${encodeURIComponent(nomorBMN)}`;
    } else {
        showToast(result.message, 'error');

        // Clear result and allow retry
        if (resultDiv) {
            resultDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-800">${result.message}</p>
                    <button onclick="location.reload()" class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Coba Lagi
                    </button>
                </div>
            `;
        }
    }
}

