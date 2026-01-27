<?php
require_once 'config/auth.php';

// Redirect based on login status
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: /src/admin/dashboard.php');
    } else {
        header('Location: /src/user/dashboard.php');
    }
} else {
    header('Location: /src/login.php');
}
exit;
