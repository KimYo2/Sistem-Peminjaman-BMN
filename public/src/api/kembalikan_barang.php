<?php
/**
 * API: Kembalikan Barang
 * Endpoint untuk mengembalikan barang yang dipinjam
 * - User biasa: hanya bisa mengembalikan barang yang dipinjam sendiri
 * - Admin: bisa mengembalikan barang siapa saja
 */

header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/auth.php';

try {
    // Require login
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $nomor_bmn_raw = $data['nomor_bmn'] ?? '';

    if (empty($nomor_bmn_raw)) {
        echo json_encode(['success' => false, 'message' => 'Nomor BMN tidak valid']);
        exit;
    }

    // Parse BPS QR Code format
    // Format: INV-xxx*xxx*xxx*KODE_BARANG*NUP
    // Example: INV-20210420145333129398000*0540103000190000000KD*3100102001*16
    $nomor_bmn = $nomor_bmn_raw;

    // Check if QR contains asterisk delimiter (BPS format)
    if (strpos($nomor_bmn_raw, '*') !== false) {
        $parts = explode('*', $nomor_bmn_raw);

        // Extract from specific indices:
        // Index 2: Kode Barang (e.g., 3100102001)
        // Index 3: NUP (e.g., 16)
        if (count($parts) >= 4) {
            $kode_barang_extracted = trim($parts[2]);
            $nup_extracted = trim($parts[3]);

            // Combine: KodeBarang-NUP
            $nomor_bmn = $kode_barang_extracted . '-' . $nup_extracted;

            error_log("BPS QR parsed: {$nomor_bmn_raw} -> {$nomor_bmn}");
        }
    }

    // Parse kode_barang and nup from combined format
    $parts = explode('-', $nomor_bmn);
    if (count($parts) !== 2) {
        echo json_encode(['success' => false, 'message' => 'Format nomor BMN tidak valid']);
        exit;
    }

    $kode_barang = $parts[0];
    $nup = intval($parts[1]);
    $user = getCurrentUser();

    // Check if item is currently borrowed
    $stmt = $pdo->prepare("
        SELECT * FROM histori_peminjaman 
        WHERE kode_barang = ? AND nup = ? AND status = 'dipinjam'
        ORDER BY waktu_pinjam DESC LIMIT 1
    ");
    $stmt->execute([$kode_barang, $nup]);
    $peminjaman = $stmt->fetch();

    // DEBUG: Log the query parameters and result
    error_log("DEBUG kembalikan_barang.php:");
    error_log("  Received nomor_bmn: " . $nomor_bmn);
    error_log("  Parsed kode_barang: " . $kode_barang);
    error_log("  Parsed nup: " . $nup);
    error_log("  Query result: " . ($peminjaman ? "FOUND (ID: {$peminjaman['id']})" : "NOT FOUND"));

    if (!$peminjaman) {
        echo json_encode([
            'success' => false,
            'message' => 'Barang ini tidak sedang dipinjam',
            'debug' => [
                'nomor_bmn' => $nomor_bmn,
                'kode_barang' => $kode_barang,
                'nup' => $nup
            ]
        ]);
        exit;
    }

    // If not admin, verify user is the borrower
    if (!isAdmin() && $peminjaman['nip_peminjam'] !== $user['nip']) {
        echo json_encode([
            'success' => false,
            'message' => 'Anda tidak memiliki peminjaman aktif untuk barang ini'
        ]);
        exit;
    }

    // Begin transaction
    $pdo->beginTransaction();

    $waktu_kembali = date('Y-m-d H:i:s');

    // Update histori_peminjaman
    $stmt = $pdo->prepare("
        UPDATE histori_peminjaman 
        SET status = 'dikembalikan', waktu_kembali = ? 
        WHERE id = ?
    ");
    $stmt->execute([$waktu_kembali, $peminjaman['id']]);

    // Update barang availability
    $stmt = $pdo->prepare("
        UPDATE barang 
        SET ketersediaan = 'tersedia',
            waktu_kembali = ?
        WHERE kode_barang = ? AND nup = ?
    ");
    $stmt->execute([$waktu_kembali, $kode_barang, $nup]);

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Barang berhasil dikembalikan',
        'data' => [
            'nomor_bmn' => $nomor_bmn,
            'waktu_kembali' => $waktu_kembali
        ]
    ]);

} catch (Exception $e) {
    // Rollback on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server Error: ' . $e->getMessage()
    ]);
}
