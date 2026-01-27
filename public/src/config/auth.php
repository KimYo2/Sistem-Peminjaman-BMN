<?php
/**
 * Authentication Helper Functions
 */

// Start session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && isset($_SESSION['nip']);
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Get current user data
 */
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'nip' => $_SESSION['nip'],
        'nama' => $_SESSION['nama'],
        'role' => $_SESSION['role']
    ];
}

/**
 * Require login - redirect to login if not logged in
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: /src/login.php');
        exit;
    }
}

/**
 * Require admin - redirect if not admin
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: /src/user/dashboard.php');
        exit;
    }
}

/**
 * Set user session after successful login
 */
function setUserSession($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nip'] = $user['nip'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];
}

/**
 * Clear user session (logout)
 */
function clearUserSession()
{
    session_unset();
    session_destroy();
}
