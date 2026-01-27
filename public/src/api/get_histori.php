<?php
/**
 * API: Get Histori Peminjaman
 * Endpoint untuk mendapatkan histori peminjaman
 */

header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/auth.php';

// Require login
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$status = $_GET['status'] ?? '';
$limit = $_GET['limit'] ?? 50;

try {
    $sql = "SELECT h.*, b.brand, b.tipe 
            FROM histori_peminjaman h
            LEFT JOIN barang b ON h.kode_barang = b.kode_barang AND h.nup = b.nup
            WHERE 1=1";
    $params = [];

    // Jika bukan admin, hanya tampilkan histori user sendiri
    if (!isAdmin()) {
        $user = getCurrentUser();
        $sql .= " AND h.nip_peminjam = ?";
        $params[] = $user['nip'];
    }

    if (!empty($status)) {
        $sql .= " AND h.status = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY h.waktu_pinjam DESC LIMIT ?";
    $params[] = (int) $limit;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $histori = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add nomor_bmn field for frontend compatibility
    foreach ($histori as &$item) {
        $item['nomor_bmn'] = $item['kode_barang'] . '-' . $item['nup'];
    }

    echo json_encode([
        'success' => true,
        'data' => $histori
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
}
