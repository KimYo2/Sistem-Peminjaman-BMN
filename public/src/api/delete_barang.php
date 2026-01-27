<?php
/**
 * API: Delete Barang
 * Endpoint untuk menghapus barang
 */

require_once '../config/database.php';
require_once '../config/auth.php';

// Require Admin
if (!isAdmin()) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);

$nomor_bmn = $data['nomor_bmn'] ?? '';

if (empty($nomor_bmn)) {
    echo json_encode(['success' => false, 'message' => 'Invalid Identifier']);
    exit;
}

// Parse Key
$parts = explode('-', $nomor_bmn);
if (count($parts) !== 2) {
    echo json_encode(['success' => false, 'message' => 'Invalid Identifier Format']);
    exit;
}
$kode_barang = $parts[0];
$nup = intval($parts[1]);

try {
    // Check availability
    $check = $pdo->prepare("SELECT ketersediaan FROM barang WHERE kode_barang = ? AND nup = ?");
    $check->execute([$kode_barang, $nup]);
    $status = $check->fetchColumn();

    if ($status === 'dipinjam') {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menghapus barang yang sedang dipinjam']);
        exit;
    }

    // Delete
    $stmt = $pdo->prepare("DELETE FROM barang WHERE kode_barang = ? AND nup = ?");
    $stmt->execute([$kode_barang, $nup]);

    echo json_encode(['success' => true, 'message' => 'Barang berhasil dihapus']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
