// Shared QR scan helpers (camera + file)
(function () {
    function parseBmn(raw) {
        if (!raw) return '';
        const text = String(raw).trim();
        if (text.includes('*')) {
            const parts = text.split('*');
            if (parts.length >= 4) {
                return `${parts[2].trim()}-${parts[3].trim()}`;
            }
        }
        return text;
    }

    function getCameraError() {
        if (!window.isSecureContext) {
            return 'Akses kamera hanya bisa di HTTPS atau localhost.';
        }
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            return 'Browser tidak mendukung akses kamera.';
        }
        if (typeof Html5Qrcode === 'undefined') {
            return 'Library scanner tidak termuat.';
        }
        return null;
    }

    async function startCamera(options) {
        const error = getCameraError();
        if (error) {
            throw new Error(error);
        }

        const elementId = options.elementId || 'qr-reader';
        const qrbox = options.qrbox || { width: 250, height: 250 };
        const fps = options.fps || 10;
        const onDecoded = options.onDecoded || function () {};

        const scanner = new Html5Qrcode(elementId);
        await scanner.start(
            { facingMode: 'environment' },
            { fps, qrbox },
            onDecoded,
            function () {}
        );

        return scanner;
    }

    async function stopCamera(scanner) {
        if (!scanner) return;
        try {
            if (scanner.isScanning) {
                await scanner.stop();
            }
            scanner.clear();
        } catch (e) {
            // ignore stop errors
        }
    }

    async function scanFile(file, elementId) {
        if (typeof Html5Qrcode === 'undefined') {
            throw new Error('Library scanner tidak termuat.');
        }

        const targetId = elementId || 'qr-reader';
        const scanner = new Html5Qrcode(targetId);
        try {
            return await scanner.scanFile(file, true);
        } finally {
            scanner.clear();
        }
    }

    window.QrScan = {
        parseBmn: parseBmn,
        getCameraError: getCameraError,
        startCamera: startCamera,
        stopCamera: stopCamera,
        scanFile: scanFile,
    };
})();
