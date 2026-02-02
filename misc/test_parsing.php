<?php
// Test parsing nomor BMN
$test_cases = [
    '3100102001-16',
    '3100102001-37',
    '3100102001-10'
];

foreach ($test_cases as $nomor_bmn) {
    echo "Testing: $nomor_bmn\n";

    $parts = explode('-', $nomor_bmn);
    echo "  Parts count: " . count($parts) . "\n";
    echo "  Part[0] (kode_barang): " . $parts[0] . "\n";
    echo "  Part[1] (nup): " . $parts[1] . "\n";
    echo "  NUP as int: " . intval($parts[1]) . "\n\n";
}

// Now test actual database query
require_once 'public/src/config/database.php';

$nomor_bmn = '3100102001-16';
$parts = explode('-', $nomor_bmn);
$kode_barang = $parts[0];
$nup = intval($parts[1]);

echo "=== Testing Database Query ===\n";
echo "Searching for: kode_barang='$kode_barang', nup=$nup\n\n";

$stmt = $pdo->prepare("
    SELECT * FROM histori_peminjaman 
    WHERE kode_barang = ? AND nup = ? AND status = 'dipinjam'
    ORDER BY waktu_pinjam DESC LIMIT 1
");
$stmt->execute([$kode_barang, $nup]);
$peminjaman = $stmt->fetch();

if ($peminjaman) {
    echo "FOUND! Peminjaman active:\n";
    echo "  ID: " . $peminjaman['id'] . "\n";
    echo "  Peminjam: " . $peminjaman['nama_peminjam'] . "\n";
    echo "  NIP: " . $peminjaman['nip_peminjam'] . "\n";
    echo "  Status: " . $peminjaman['status'] . "\n";
} else {
    echo "NOT FOUND! This is the problem.\n";
}
