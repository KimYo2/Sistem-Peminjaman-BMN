<?php
/**
 * API: Update Barang
 * Endpoint untuk mengubah data barang
 */

require_once '../config/database.php';
require_once '../config/auth.php';

// Require Admin
if (!isAdmin()) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$nomor_bmn = $data['nomor_bmn'] ?? ''; // This is the ID (Composite key)
$brand = $data['brand'] ?? '';
$tipe = $data['tipe'] ?? '';
$kondisi = $data['kondisi'] ?? '';
$ketersediaan = $data['ketersediaan'] ?? '';
$keterangan = $data['keterangan'] ?? '';

if (empty($nomor_bmn) || empty($brand)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Parse Key
$parts = explode('-', $nomor_bmn);
if (count($parts) !== 2) {
    echo json_encode(['success' => false, 'message' => 'Invalid Identifier']);
    exit;
}
$kode_barang = $parts[0];
$nup = intval($parts[1]);

try {
    $stmt = $pdo->prepare("UPDATE barang SET brand = ?, tipe = ?, kondisi_terakhir = ?, ketersediaan = ?, keterangan = ? WHERE kode_barang = ? AND nup = ?");
    $stmt->execute([$brand, $tipe, $kondisi, $ketersediaan, $keterangan, $kode_barang, $nup]);

    echo json_encode(['success' => true, 'message' => 'Data barang berhasil diperbarui']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
