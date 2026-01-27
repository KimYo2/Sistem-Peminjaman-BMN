<?php
require_once 'public/src/config/database.php';
try {
    $pdo->exec("ALTER TABLE barang ADD COLUMN keterangan TEXT NULL AFTER kondisi_terakhir");
    echo "Column 'keterangan' added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column 'keterangan' already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
