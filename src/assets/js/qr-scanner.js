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
        showToast('Gagal mengakses kamera. Pastikan izin kamera diberikan.', 'error');
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
        setTimeout(() => location.reload(), 2000);
        return;
    }

    // Process with debug info
    processScannedBMN(nomorBMN, decodedText);
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
async function processScannedBMN(nomorBMN, rawText = '') {
    // Show loading with debug info
    const resultDiv = document.getElementById('scan-result');
    if (resultDiv) {
        resultDiv.innerHTML = `
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-900">Memproses Data...</h3>
                <div class="mt-4 text-left bg-gray-50 p-4 rounded text-sm font-mono overflow-auto">
                    <p class="mb-2"><strong>Parsed BMN:</strong> <span class="text-blue-600 font-bold">${nomorBMN}</span></p>
                    <p class="text-xs text-gray-500 border-t pt-2"><strong>Raw QR:</strong> ${rawText.substring(0, 50)}...</p>
                </div>
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

