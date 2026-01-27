<?php
/**
 * API: Add Barang
 * Endpoint untuk menambahkan barang baru
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

$nomor_bmn = $data['nomor_bmn'] ?? '';
$brand = $data['brand'] ?? '';
$tipe = $data['tipe'] ?? '';
$kondisi = $data['kondisi'] ?? 'baik';

if (empty($nomor_bmn) || empty($brand)) {
    echo json_encode(['success' => false, 'message' => 'Kode BMN dan Brand wajib diisi']);
    exit;
}

// Parse Nomor BMN to Kode & NUP
// Format expected: KODE-NUP (e.g. 340123-001)
$parts = explode('-', $nomor_bmn);
if (count($parts) !== 2) {
    echo json_encode(['success' => false, 'message' => 'Format Nomor BMN salah. Gunakan format: KODE-NUP (contoh: 3101-001)']);
    exit;
}

$kode_barang = $parts[0];
$nup = intval($parts[1]);

if ($nup <= 0) {
    echo json_encode(['success' => false, 'message' => 'NUP tidak valid']);
    exit;
}

try {
    // Check duplication
    $check = $pdo->prepare("SELECT COUNT(*) FROM barang WHERE kode_barang = ? AND nup = ?");
    $check->execute([$kode_barang, $nup]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Barang dengan Nomor BMN tersebut sudah terdaftar']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO barang (kode_barang, nup, brand, tipe, kondisi_terakhir, ketersediaan) VALUES (?, ?, ?, ?, ?, 'tersedia')");
    $stmt->execute([$kode_barang, $nup, $brand, $tipe, $kondisi]);

    echo json_encode(['success' => true, 'message' => 'Barang berhasil ditambahkan']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
