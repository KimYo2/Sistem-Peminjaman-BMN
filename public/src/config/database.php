<?php
/**
 * Database Configuration
 * Koneksi ke MySQL menggunakan PDO
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'pinjam_qr');
define('DB_USER', 'root');
define('DB_PASS', '');

// Set Timezone to Indonesia Western Time (WIB)
date_default_timezone_set('Asia/Jakarta');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die(json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal: ' . $e->getMessage()
    ]));
}
