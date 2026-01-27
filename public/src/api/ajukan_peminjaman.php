<?php
/**
 * API: Ajukan Peminjaman
 * Endpoint untuk memproses pengajuan peminjaman barang
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$nomor_bmn_input = $data['nomor_bmn'] ?? '';

if (empty($nomor_bmn_input)) {
    echo json_encode(['success' => false, 'message' => 'Nomor BMN tidak boleh kosong']);
    exit;
}

// Parse kode_barang and nup
$parts = explode('-', $nomor_bmn_input);
$kode_barang = $parts[0];
$nup = isset($parts[1]) ? intval($parts[1]) : null;

if (empty($kode_barang) || $nup === null) {
    echo json_encode(['success' => false, 'message' => 'Format Nomor BMN tidak valid']);
    exit;
}

try {
    // Cek ketersediaan barang
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE kode_barang = ? AND nup = ?");
    $stmt->execute([$kode_barang, $nup]);
    $barang = $stmt->fetch();

    if (!$barang) {
        echo json_encode(['success' => false, 'message' => 'Barang tidak ditemukan']);
        exit;
    }

    if ($barang['ketersediaan'] !== 'tersedia') {
        echo json_encode([
            'success' => false,
            'message' => 'Barang sedang dipinjam oleh ' . $barang['peminjam_terakhir']
        ]);
        exit;
    }

    $user = getCurrentUser();
    $waktu_pinjam = date('Y-m-d H:i:s');

    // Begin transaction
    $pdo->beginTransaction();

    // Update tabel barang
    $stmt = $pdo->prepare("
        UPDATE barang 
        SET ketersediaan = 'dipinjam',
        peminjam_terakhir = ?,
        waktu_pinjam = ?,
        waktu_kembali = NULL
        WHERE kode_barang = ? AND nup = ?
    ");
    $stmt->execute([$user['nama'], $waktu_pinjam, $kode_barang, $nup]);

    // Insert ke histori peminjaman
    $stmt = $pdo->prepare("
        INSERT INTO histori_peminjaman 
        (kode_barang, nup, nip_peminjam, nama_peminjam, waktu_pinjam, status)
        VALUES (?, ?, ?, ?, ?, 'dipinjam')
    ");
    $stmt->execute([$kode_barang, $nup, $user['nip'], $user['nama'], $waktu_pinjam]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Peminjaman berhasil diajukan',
        'data' => [
            'nomor_bmn' => $nomor_bmn_input,
            'peminjam' => $user['nama'],
            'waktu_pinjam' => $waktu_pinjam
        ]
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()]);
}
