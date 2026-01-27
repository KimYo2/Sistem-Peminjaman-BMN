<?php
/**
 * API: Get Barang
 * Endpoint untuk mendapatkan daftar barang dengan filter
 */

header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/auth.php';

// Require admin
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden - Admin only']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$ketersediaan = $_GET['ketersediaan'] ?? '';
$search = $_GET['search'] ?? '';

try {
    $sql = "SELECT * FROM barang WHERE 1=1";
    $params = [];

    if (!empty($ketersediaan)) {
        $sql .= " AND ketersediaan = ?";
        $params[] = $ketersediaan;
    }

    if (!empty($search)) {
        $sql .= " AND (kode_barang LIKE ? OR brand LIKE ? OR tipe LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $sql .= " ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $barang = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add nomor_bmn field for frontend compatibility
    foreach ($barang as &$item) {
        $item['nomor_bmn'] = $item['kode_barang'] . '-' . $item['nup'];
    }

    echo json_encode([
        'success' => true,
        'data' => $barang
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
}
