<?php

use Illuminate\Support\Facades\Route;

// Routes for New Features (Laravel 12 Standard)
Route::middleware(['web'])->group(function () {
    // Return Flow
    Route::get('/return', [App\Http\Controllers\ReturnController::class, 'index'])->name('return.index');
    Route::post('/return/store', [App\Http\Controllers\ReturnController::class, 'store'])->name('return.store');

    // Admin Ticket Management
    Route::get('/admin/tiket', [
        App\Http\Controllers\Admin\TiketKerusakanController::class,
        'index'
    ])->name('admin.tiket.index');
    Route::put('/admin/tiket/{id}', [
        App\Http\Controllers\Admin\TiketKerusakanController::class,
        'update'
    ])->name('admin.tiket.update');
});

// Fallback for Legacy Src
Route::get('/', function () {
    return redirect('/src/');
});

Route::get('/src/{any?}', function () {
    abort(404);
})->where('any', '.*');