<?php
/**
 * API: Scan QR Code
 * Endpoint untuk menerima nomor BMN dari hasil scan QR
 * dan mengembalikan detail barang
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
    // Check if input is a combined string or separated
    $nomor_bmn = $data['nomor_bmn'] ?? '';

    $kode_barang = '';
    $nup = '';

    if (!empty($nomor_bmn)) {
        // Try to split by dash if present (e.g. 3100102001-10)
        if (strpos($nomor_bmn, '-') !== false) {
            $parts = explode('-', $nomor_bmn);
            $kode_barang = $parts[0];
            $nup = intval($parts[1]); // Ensure NUP is integer
        } else {
            // Assume it's just the code. 
            $kode_barang = $nomor_bmn;
            // If checking strict NUP is required, we might fail here if NUP is missing.
            // But let's verify if query handles empty NUP.
        }
    }

    if (empty($kode_barang)) {
        echo json_encode(['success' => false, 'message' => 'Kode barang tidak valid']);
        exit;
    }

    $query = "SELECT * FROM barang WHERE kode_barang = ?";
    $params = [$kode_barang];

    if (!empty($nup)) {
        $query .= " AND nup = ?";
        $params[] = $nup;
    } else {
        // If NUP is not provided, do we return any item? 
        // Or do we strictly require it?
        // Let's assume for now we try to find one.
    }

    $stmt = $pdo->prepare($query . " LIMIT 1");
    $stmt->execute($params);
    $barang = $stmt->fetch();

    if (!$barang) {
        echo json_encode([
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ]);
        exit;
    }

    // Add formatted nomor_bmn for frontend compatibility
    $barang['nomor_bmn'] = $barang['kode_barang'] . '-' . $barang['nup'];

    echo json_encode([
        'success' => true,
        'message' => 'Barang ditemukan',
        'data' => $barang
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage() . ' in ' . basename($e->getFile()) . ':' . $e->getLine()]);
}
