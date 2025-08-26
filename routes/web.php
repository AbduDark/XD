<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\CashTransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ProfileController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// API routes for dashboard
Route::prefix('api')->group(function () {
    Route::get('/dashboard/recent-invoices', [DashboardController::class, 'recentInvoices']);
    Route::get('/dashboard/recent-repairs', [DashboardController::class, 'recentRepairs']);
    Route::get('/dashboard/sales-chart', [DashboardController::class, 'salesChart']);
    Route::get('/products', [ProductController::class, 'apiIndex']);
});

    // Product routes
    Route::resource('products', ProductController::class);
    Route::get('/products/search/api', [ProductController::class, 'search'])->name('products.search');

    // Invoice routes
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // Return routes
    Route::resource('returns', ReturnController::class);

    // Repair routes
    Route::resource('repairs', RepairController::class);

    // Cash Transfer routes
    Route::resource('cash-transfers', CashTransferController::class);

    // Report routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/repairs', [ReportController::class, 'repairs'])->name('reports.repairs');
    Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');

    // Backup routes
    Route::get('/backup', [BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup/download', [BackupController::class, 'download'])->name('backup.download');

    // User management routes (Super Admin only)
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});
