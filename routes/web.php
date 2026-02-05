<?php

use App\Http\Middleware\EnsureAdmin;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

// Routes for New Features (Laravel 12 Standard)
Route::middleware(['web', Authenticate::class])->group(function () {
    // Return Flow
    Route::get('/return', [App\Http\Controllers\ReturnController::class, 'index'])->name('return.index');
    Route::post('/return/store', [App\Http\Controllers\ReturnController::class, 'store'])->name('return.store');

    // User Histori (New Migration)
    Route::get('/histori', [App\Http\Controllers\User\HistoriController::class, 'index'])->name('user.histori.index');

    // User Dashboard (New Migration)
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');

    // User Scan (Borrowing)
    Route::get('/scan', [App\Http\Controllers\User\ScanController::class, 'index'])->name('user.scan');

    // User Barang Detail & Borrow
    Route::get('/barang/{nomor_bmn}', [App\Http\Controllers\User\BarangController::class, 'show'])->name('user.barang.show');
    Route::post('/barang/borrow', [App\Http\Controllers\User\BarangController::class, 'store'])->name('user.barang.borrow');

    // Admin Only Routes
    Route::middleware([EnsureAdmin::class])->group(function () {
        // Admin Ticket Management
        Route::get('/admin/tiket', [
            App\Http\Controllers\Admin\TiketKerusakanController::class,
            'index'
        ])->name('admin.tiket.index');
        Route::put('/admin/tiket/{id}', [
            App\Http\Controllers\Admin\TiketKerusakanController::class,
            'update'
        ])->name('admin.tiket.update');

        // Admin Dashboard (New Migration)
        Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Admin Barang Management (New Migration)
        Route::resource('/admin/barang', App\Http\Controllers\Admin\BarangController::class, [
            'names' => 'admin.barang',
            'except' => ['show'],
        ]);
        Route::post('/admin/barang/import', [App\Http\Controllers\Admin\BarangController::class, 'import'])->name('admin.barang.import');

        // Admin Histori Management (New Migration)
        Route::get('/admin/histori', [App\Http\Controllers\Admin\HistoriController::class, 'index'])->name('admin.histori.index');
        Route::post('/admin/histori/{id}/approve', [App\Http\Controllers\Admin\HistoriController::class, 'approve'])->name('admin.histori.approve');
        Route::post('/admin/histori/{id}/reject', [App\Http\Controllers\Admin\HistoriController::class, 'reject'])->name('admin.histori.reject');
        Route::get('/admin/histori/export', [App\Http\Controllers\Admin\HistoriController::class, 'export'])->name('admin.histori.export');
    });
});

// Fallback for Legacy Src
// Auth Routes
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Protected Routes Group (Optional: Add middleware later)
// For now, simple grouping or just list them. 
// Ideally we should wrap these with 'auth' middleware manually or in controller constructor.


Route::get('/src/{any?}', function () {
    abort(404);
})->where('any', '.*');
