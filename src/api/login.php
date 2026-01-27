<?php
/**
 * API: Login
 * Endpoint untuk autentikasi user dan admin
 */

header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$nip = $data['nip'] ?? '';
$password = $data['password'] ?? '';

if (empty($nip) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'NIP dan password harus diisi']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE nip = ?");
    $stmt->execute([$nip]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'NIP atau password salah']);
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'NIP atau password salah']);
        exit;
    }

    // Set session
    setUserSession($user);

    echo json_encode([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => [
            'nama' => $user['nama'],
            'nip' => $user['nip'],
            'role' => $user['role']
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
}
