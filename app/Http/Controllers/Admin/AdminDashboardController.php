<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        $stats = $this->getSystemStats();
        $recentActivities = $this->getRecentActivities();
        $monthlyData = $this->getMonthlyData();
        
        return view('admin.dashboard', compact('stats', 'recentActivities', 'monthlyData'));
    }

    private function getSystemStats()
    {
        return [
            'total_stores' => Store::count(),
            'active_stores' => Store::where('is_active', true)->count(),
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_products' => Product::count(),
            'total_invoices' => Invoice::count(),
            'total_repairs' => Repair::count(),
            'monthly_revenue' => Invoice::whereMonth('created_at', Carbon::now()->month)
                                     ->whereYear('created_at', Carbon::now()->year)
                                     ->sum('total'),
            'pending_repairs' => Repair::where('status', 'pending')->count(),
        ];
    }

    private function getRecentActivities()
    {
        $recentStores = Store::with('owner')
                            ->latest()
                            ->limit(5)
                            ->get()
                            ->map(function ($store) {
                                return [
                                    'type' => 'store_created',
                                    'message' => "متجر جديد: {$store->name}",
                                    'user' => $store->owner->name,
                                    'time' => $store->created_at->diffForHumans(),
                                ];
                            });

        $recentUsers = User::where('role', '!=', 'super_admin')
                          ->latest()
                          ->limit(5)
                          ->get()
                          ->map(function ($user) {
                              return [
                                  'type' => 'user_registered',
                                  'message' => "مستخدم جديد: {$user->name}",
                                  'user' => $user->name,
                                  'time' => $user->created_at->diffForHumans(),
                              ];
                          });

        return $recentStores->concat($recentUsers)
                           ->sortByDesc('time')
                           ->take(10)
                           ->values();
    }

    private function getMonthlyData()
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'stores' => Store::whereYear('created_at', $date->year)
                               ->whereMonth('created_at', $date->month)
                               ->count(),
                'users' => User::whereYear('created_at', $date->year)
                              ->whereMonth('created_at', $date->month)
                              ->count(),
                'revenue' => Invoice::whereYear('created_at', $date->year)
                                  ->whereMonth('created_at', $date->month)
                                  ->sum('total'),
            ]);
        }
        
        return $months;
    }

    public function storesAnalytics()
    {
        $storeStats = Store::withCount(['products', 'invoices', 'repairs'])
                          ->with('owner')
                          ->get()
                          ->map(function ($store) {
                              return [
                                  'id' => $store->id,
                                  'name' => $store->name,
                                  'owner' => $store->owner->name,
                                  'products_count' => $store->products_count,
                                  'invoices_count' => $store->invoices_count,
                                  'repairs_count' => $store->repairs_count,
                                  'revenue' => $store->invoices()->sum('total'),
                                  'status' => $store->is_active ? 'نشط' : 'غير نشط',
                                  'created_at' => $store->created_at->format('Y-m-d'),
                              ];
                          });

        return response()->json($storeStats);
    }

    public function systemHealth()
    {
        $health = [
            'database' => $this->checkDatabaseHealth(),
            'stores' => $this->checkStoresHealth(),
            'users' => $this->checkUsersHealth(),
            'performance' => $this->checkPerformanceMetrics(),
        ];

        return response()->json($health);
    }

    private function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'قاعدة البيانات تعمل بشكل طبيعي'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'خطأ في الاتصال بقاعدة البيانات'];
        }
    }

    private function checkStoresHealth()
    {
        $totalStores = Store::count();
        $activeStores = Store::where('is_active', true)->count();
        $inactiveStores = $totalStores - $activeStores;

        return [
            'total_stores' => $totalStores,
            'active_stores' => $activeStores,
            'inactive_stores' => $inactiveStores,
            'health_percentage' => $totalStores > 0 ? round(($activeStores / $totalStores) * 100, 2) : 0,
        ];
    }

    private function checkUsersHealth()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $recentlyActive = User::where('updated_at', '>=', Carbon::now()->subDays(7))->count();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'recently_active' => $recentlyActive,
            'activity_percentage' => $totalUsers > 0 ? round(($recentlyActive / $totalUsers) * 100, 2) : 0,
        ];
    }

    private function checkPerformanceMetrics()
    {
        $avgInvoiceProcessingTime = DB::table('invoices')
                                     ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_time')
                                     ->first();

        return [
            'avg_invoice_processing' => $avgInvoiceProcessingTime->avg_time ?? 0,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
        ];
    }
}
