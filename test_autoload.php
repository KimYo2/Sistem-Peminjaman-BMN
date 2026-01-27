<?php
require __DIR__ . '/vendor/autoload.php';

try {
    $client = new Google\Client();
    echo "Google Client Loaded Successfully!\n";
} catch (Error $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
