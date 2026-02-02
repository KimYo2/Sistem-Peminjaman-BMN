<?php
require_once 'public/src/config/database.php';

echo "=== Checking Item 3100102001-16 ===\n\n";

// Check barang table
echo "1. Checking BARANG table:\n";
$stmt = $pdo->prepare("SELECT * FROM barang WHERE kode_barang = ? AND nup = ?");
$stmt->execute(['3100102001', 16]);
$barang = $stmt->fetch();

if ($barang) {
    echo "   Found in barang table:\n";
    echo "   - Ketersediaan: " . $barang['ketersediaan'] . "\n";
    echo "   - Peminjam Terakhir: " . ($barang['peminjam_terakhir'] ?? 'NULL') . "\n";
    echo "   - Waktu Pinjam: " . ($barang['waktu_pinjam'] ?? 'NULL') . "\n\n";
} else {
    echo "   NOT FOUND in barang table!\n\n";
}

// Check histori_peminjaman table
echo "2. Checking HISTORI_PEMINJAMAN table:\n";
$stmt = $pdo->prepare("SELECT * FROM histori_peminjaman WHERE kode_barang = ? AND nup = ? ORDER BY waktu_pinjam DESC");
$stmt->execute(['3100102001', 16]);
$histori = $stmt->fetchAll();

if ($histori) {
    echo "   Found " . count($histori) . " record(s):\n";
    foreach ($histori as $h) {
        echo "   - ID: " . $h['id'] . "\n";
        echo "     Status: " . $h['status'] . "\n";
        echo "     Peminjam: " . $h['nama_peminjam'] . " (NIP: " . $h['nip_peminjam'] . ")\n";
        echo "     Waktu Pinjam: " . $h['waktu_pinjam'] . "\n";
        echo "     Waktu Kembali: " . ($h['waktu_kembali'] ?? 'NULL') . "\n\n";
    }
} else {
    echo "   NO RECORDS found in histori_peminjaman!\n\n";
}

// Check for active borrowing
echo "3. Checking ACTIVE borrowing (status = 'dipinjam'):\n";
$stmt = $pdo->prepare("SELECT * FROM histori_peminjaman WHERE kode_barang = ? AND nup = ? AND status = 'dipinjam' ORDER BY waktu_pinjam DESC LIMIT 1");
$stmt->execute(['3100102001', 16]);
$active = $stmt->fetch();

if ($active) {
    echo "   ACTIVE borrowing found:\n";
    echo "   - ID: " . $active['id'] . "\n";
    echo "   - Peminjam: " . $active['nama_peminjam'] . "\n";
    echo "   - Waktu Pinjam: " . $active['waktu_pinjam'] . "\n";
} else {
    echo "   NO ACTIVE borrowing found!\n";
    echo "   This is why scan return fails!\n";
}

echo "\n=== End of Debug ===\n";
