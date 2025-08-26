<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('users.index');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::get('/products/search/api', [App\Http\Controllers\ProductController::class, 'search'])->name('products.search');
    
    // Invoices
    Route::resource('invoices', App\Http\Controllers\InvoiceController::class);
    Route::get('/invoices/{invoice}/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoices.print');
    
    // Returns
    Route::resource('returns', App\Http\Controllers\ReturnController::class);
    
    // Repairs
    Route::resource('repairs', App\Http\Controllers\RepairController::class);
    
    // Cash Transfers
    Route::resource('cash-transfers', App\Http\Controllers\CashTransferController::class);
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/inventory', [App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
    
    // Users (Super Admin only)
    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
    });
    
    // Backup
    Route::get('/backup', [App\Http\Controllers\BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup', [App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
});

require __DIR__.'/auth.php';
