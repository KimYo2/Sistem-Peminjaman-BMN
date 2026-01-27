<?php
/**
 * API: Export Histori Peminjaman (CSV)
 * Endpoint untuk mengunduh histori peminjaman dalam format CSV
 */

require_once '../config/database.php';
require_once '../config/auth.php';

// Require login
if (!isLoggedIn()) {
    http_response_code(401);
    die('Unauthorized');
}

$status = $_GET['status'] ?? '';

try {
    $sql = "SELECT h.waktu_pinjam, h.waktu_kembali, h.kode_barang, h.nup, b.brand, b.tipe, h.status, h.nip_peminjam
            FROM histori_peminjaman h
            LEFT JOIN barang b ON h.kode_barang = b.kode_barang AND h.nup = b.nup
            WHERE 1=1";
    $params = [];

    // Require Admin
    if (!isAdmin()) {
        http_response_code(403);
        die('Forbidden: Hanya admin yang dapat mengakses halaman ini.');
    }

    if (!empty($status)) {
        $sql .= " AND h.status = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY h.waktu_pinjam DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $histori = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="histori_peminjaman_' . date('Y-m-d_H-i-s') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    // Add Byte Order Mark (BOM) for Excel compatibility with UTF-8
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // CSV Header
    fputcsv($output, ['Waktu Pinjam', 'Waktu Kembali', 'Nomor BMN', 'Brand', 'Tipe', 'Status', 'NIP Peminjam']);

    // CSV Data
    foreach ($histori as $row) {
        $nomor_bmn = $row['kode_barang'] . '-' . str_pad($row['nup'], 3, '0', STR_PAD_LEFT); // Ensure NUP is formatted if needed

        fputcsv($output, [
            $row['waktu_pinjam'],
            $row['waktu_kembali'] ?? '-',
            $nomor_bmn,
            $row['brand'] ?? '',
            $row['tipe'] ?? '',
            ucfirst($row['status']),
            $row['nip_peminjam']
        ]);
    }

    fclose($output);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    die('Terjadi kesalahan server: ' . $e->getMessage());
}
