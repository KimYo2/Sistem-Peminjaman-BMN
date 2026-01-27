<?php
/**
 * API: Logout
 * Endpoint untuk logout dan clear session
 */

header('Content-Type: application/json');
require_once '../config/auth.php';

clearUserSession();

echo json_encode([
    'success' => true,
    'message' => 'Logout berhasil'
]);
