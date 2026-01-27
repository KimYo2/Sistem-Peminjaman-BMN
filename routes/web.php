<?php

use Illuminate\Support\Facades\Route;

// Redirect root to src folder (PHP native app)
Route::get('/', function () {
    return redirect('/src/');
});

// Catch-all route for src folder - let PHP native handle it
Route::get('/src/{any?}', function () {
    // This will be handled by public/src/ files
    abort(404);
})->where('any', '.*');
