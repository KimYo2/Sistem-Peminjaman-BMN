<?php
/**
 * Script Sync ke Google Sheets
 * 
 * Cara pakai:
 * 1. Pastikan credentials.json ada di storage/credentials.json
 * 2. Set SPREADSHEET_ID di environment atau hardcode di bawah
 * 3. Jalankan via terminal: php src/scripts/sync_google_sheets.php
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use Google\Client;
use Google\Service\Sheets;


// Konfigurasi
// Ganti dengan ID Spreadsheet Anda (dari URL Google Sheets)
$spreadsheetId = '1gkp-WTRpfMpq_f8k1cYeJDxwMZWpot0kBuDwuhG-k24';
$credentialsPath = __DIR__ . '/../../storage/credentials.json';

if (!file_exists($credentialsPath)) {
    die("Error: File credentials.json tidak ditemukan di " . $credentialsPath . "\n");
}

echo "Memulai sinkronisasi...\n";

// 1. Setup Client
$client = new Google\Client();
$client->setApplicationName('PinjamQR Sync');
$client->setScopes([Google\Service\Sheets::SPREADSHEETS]);
$client->setAuthConfig($credentialsPath);
$client->setAccessType('offline');

$service = new Google\Service\Sheets($client);

// 2. Ambil Data dari Database
try {
    $sql = "SELECT h.waktu_pinjam, h.waktu_kembali, h.kode_barang, h.nup, b.brand, b.tipe, h.status, h.nip_peminjam, u.nama as nama_peminjam
            FROM histori_peminjaman h
            LEFT JOIN barang b ON h.kode_barang = b.kode_barang AND h.nup = b.nup
            LEFT JOIN users u ON h.nip_peminjam = u.nip
            ORDER BY h.waktu_pinjam DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $histori = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage() . "\n");
}

echo "Ditemukan " . count($histori) . " data histori.\n";

// 3. Format Data untuk Sheets
$values = [
    ['Waktu Pinjam', 'Waktu Kembali', 'Nomor BMN', 'Barang', 'Status', 'NIP', 'Nama Peminjam'] // Header
];

foreach ($histori as $row) {
    $nomor_bmn = $row['kode_barang'] . '-' . str_pad($row['nup'], 3, '0', STR_PAD_LEFT);
    $barang = ($row['brand'] ?? '') . ' ' . ($row['tipe'] ?? '');

    $values[] = [
        $row['waktu_pinjam'],
        $row['waktu_kembali'] ?? '-',
        $nomor_bmn,
        $barang,
        ucfirst($row['status']),
        $row['nip_peminjam'],
        $row['nama_peminjam'] ?? $row['nip_peminjam']
    ];
}

// 4. Kirim ke Google Sheets
$range = 'Sheet1!A1';
$body = new Google\Service\Sheets\ValueRange([
    'values' => $values
]);

$params = [
    'valueInputOption' => 'RAW'
];

try {
    // Clear sheet dulu (opsional, hati-hati jika ada data lain)
    $service->spreadsheets_values->clear($spreadsheetId, 'Sheet1!A:Z', new Google\Service\Sheets\ClearValuesRequest());

    // Tulis data baru
    $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    echo "Sukses! " . $result->getUpdatedCells() . " sel diperbarui.\n";

} catch (Exception $e) {
    echo "Google Sheets Error: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), '403') !== false) {
        echo "Hint: Pastikan email Service Account sudah dijadikan Editor di Spreadsheet ini.\n";
    }
}
