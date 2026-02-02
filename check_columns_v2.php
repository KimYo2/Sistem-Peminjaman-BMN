<?php
require_once 'config/database.php';
try {
    $stmt = $pdo->query("DESCRIBE histori_peminjaman");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Columns in histori_peminjaman:\n";
    foreach ($columns as $col) {
        echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>