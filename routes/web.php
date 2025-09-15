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
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\Admin\UserManagementController;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'super_admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
Route::get('/local', function () {
    if (auth()->check()) {
        return 'welcome';
    } else {
        return 'please login first';
    }
});


Route::middleware(['auth', 'store.resolve'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // API Routes for dashboard and search
    Route::prefix('api')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
        Route::get('/dashboard/recent-invoices', [DashboardController::class, 'getRecentInvoices']);
        Route::get('/dashboard/recent-repairs', [DashboardController::class, 'getRecentRepairs']);
        Route::get('/dashboard/sales-chart', [DashboardController::class, 'getSalesChart']);
        Route::get('/products/search', [ProductController::class, 'search']);
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
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/repairs', [ReportController::class, 'repairs'])->name('repairs');
        Route::get('/daily', [ReportController::class, 'dailyReport'])->name('daily');
        Route::get('/inventory-value', [ReportController::class, 'inventoryValue'])->name('inventory-value');
        Route::get('/daily-closing', [ReportController::class, 'dailyClosing'])->name('daily-closing');
    });

    // Backup routes
    Route::get('/backup', [BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup/download', [BackupController::class, 'download'])->name('backup.download');

    // Admin routes (Super Admin only)
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stores-analytics', [AdminDashboardController::class, 'storesAnalytics'])->name('dashboard.stores-analytics');
        Route::get('/dashboard/system-health', [AdminDashboardController::class, 'systemHealth'])->name('dashboard.system-health');

        // Store Settings
        Route::get('/stores/{store}/settings', [App\Http\Controllers\Admin\StoreSettingsController::class, 'index'])->name('stores.settings');
        Route::post('/stores/{store}/basic-settings', [App\Http\Controllers\Admin\StoreSettingsController::class, 'updateBasicSettings'])->name('stores.basic-settings');
        Route::post('/stores/{store}/business-settings', [App\Http\Controllers\Admin\StoreSettingsController::class, 'updateBusinessSettings'])->name('stores.business-settings');
        Route::post('/stores/{store}/notification-settings', [App\Http\Controllers\Admin\StoreSettingsController::class, 'updateNotificationSettings'])->name('stores.notification-settings');
        Route::post('/stores/{store}/security-settings', [App\Http\Controllers\Admin\StoreSettingsController::class, 'updateSecuritySettings'])->name('stores.security-settings');
        Route::post('/stores/{store}/logo', [App\Http\Controllers\Admin\StoreSettingsController::class, 'updateLogo'])->name('stores.logo');
        Route::get('/stores/{store}/permissions', [App\Http\Controllers\Admin\StoreSettingsController::class, 'getPermissions'])->name('stores.permissions');
        Route::post('/stores/{store}/permissions', [App\Http\Controllers\Admin\StoreSettingsController::class, 'updatePermissions'])->name('stores.update-permissions');
        Route::post('/stores/{store}/reset-settings', [App\Http\Controllers\Admin\StoreSettingsController::class, 'resetSettings'])->name('stores.reset-settings');

        // Advanced Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdvancedReportsController::class, 'index'])->name('index');
            Route::get('/store-performance', [App\Http\Controllers\Admin\AdvancedReportsController::class, 'storePerformance'])->name('store-performance');
            Route::get('/system-analytics', [App\Http\Controllers\Admin\AdvancedReportsController::class, 'systemAnalytics'])->name('system-analytics');
            Route::get('/financial-summary', [App\Http\Controllers\Admin\AdvancedReportsController::class, 'financialSummary'])->name('financial-summary');
            Route::get('/user-engagement', [App\Http\Controllers\Admin\AdvancedReportsController::class, 'userEngagement'])->name('user-engagement');
            Route::get('/inventory-analysis', [App\Http\Controllers\Admin\AdvancedReportsController::class, 'inventoryAnalysis'])->name('inventory-analysis');
            Route::get('/export', [App\Http\Controllers\Admin\AdvancedReportsController::class, 'exportReport'])->name('export');
        });

        // Store Management
        Route::resource('stores', StoreManagementController::class);
        Route::patch('/stores/{store}/toggle-status', [StoreManagementController::class, 'toggleStatus'])->name('stores.toggle-status');
        Route::post('/stores/{store}/assign-user', [StoreManagementController::class, 'assignUser'])->name('stores.assign-user');
        Route::delete('/stores/{store}/users/{user}', [StoreManagementController::class, 'removeUser'])->name('stores.remove-user');

        // User Management
        Route::resource('users', UserManagementController::class);
        Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/impersonate', [UserManagementController::class, 'impersonate'])->name('users.impersonate');
        Route::post('/users/stop-impersonating', [UserManagementController::class, 'stopImpersonating'])->name('users.stop-impersonating');
        Route::post('/users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulk-action');
    });

    // Legacy user management routes (Super Admin only) - redirects to new admin routes
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/users', function() {
            return redirect()->route('admin.users.index');
        })->name('users.index');
        Route::resource('users', UserController::class)->except(['index']);
    });
});

require __DIR__.'/auth.php';

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});
