
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Repair;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvancedReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function storePerformance(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();

        $stores = Store::with(['owner', 'users'])
            ->withCount(['products', 'invoices', 'repairs'])
            ->get()
            ->map(function ($store) use ($dateFrom, $dateTo) {
                $revenue = $store->invoices()
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->sum('total');
                
                $profitData = $this->calculateStoreProfit($store->id, $dateFrom, $dateTo);
                
                return [
                    'id' => $store->id,
                    'name' => $store->name,
                    'owner' => $store->owner->name,
                    'products_count' => $store->products_count,
                    'invoices_count' => $store->invoices_count,
                    'repairs_count' => $store->repairs_count,
                    'revenue' => $revenue,
                    'profit' => $profitData['profit'],
                    'profit_margin' => $profitData['margin'],
                    'active_users' => $store->users()->wherePivot('is_active', true)->count(),
                    'growth_rate' => $this->calculateGrowthRate($store->id, $dateFrom, $dateTo),
                    'status' => $store->is_active ? 'نشط' : 'غير نشط',
                    'last_activity' => $store->invoices()->latest()->first()?->created_at,
                ];
            });

        return view('admin.reports.store-performance', compact('stores', 'dateFrom', 'dateTo'));
    }

    public function systemAnalytics()
    {
        $analytics = [
            'overview' => $this->getSystemOverview(),
            'performance' => $this->getPerformanceMetrics(),
            'user_activity' => $this->getUserActivityMetrics(),
            'sales_trends' => $this->getSalesTrends(),
            'top_performers' => $this->getTopPerformers(),
        ];

        return view('admin.reports.system-analytics', compact('analytics'));
    }

    public function financialSummary(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfYear();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now()->endOfYear();

        $summary = [
            'total_revenue' => $this->getTotalRevenue($dateFrom, $dateTo),
            'total_profit' => $this->getTotalProfit($dateFrom, $dateTo),
            'store_breakdown' => $this->getStoreRevenueBreakdown($dateFrom, $dateTo),
            'monthly_trends' => $this->getMonthlyFinancialTrends($dateFrom, $dateTo),
            'expense_categories' => $this->getExpenseCategories($dateFrom, $dateTo),
            'roi_metrics' => $this->getROIMetrics($dateFrom, $dateTo),
        ];

        return view('admin.reports.financial-summary', compact('summary', 'dateFrom', 'dateTo'));
    }

    public function userEngagement()
    {
        $engagement = [
            'active_users' => $this->getActiveUsersMetrics(),
            'login_patterns' => $this->getLoginPatterns(),
            'feature_usage' => $this->getFeatureUsage(),
            'user_retention' => $this->getUserRetention(),
            'geographic_distribution' => $this->getGeographicDistribution(),
        ];

        return view('admin.reports.user-engagement', compact('engagement'));
    }

    public function inventoryAnalysis()
    {
        $analysis = [
            'stock_levels' => $this->getStockLevels(),
            'turnover_rates' => $this->getInventoryTurnover(),
            'slow_moving' => $this->getSlowMovingItems(),
            'fast_moving' => $this->getFastMovingItems(),
            'stock_value' => $this->getStockValueAnalysis(),
            'reorder_alerts' => $this->getReorderAlerts(),
        ];

        return view('admin.reports.inventory-analysis', compact('analysis'));
    }

    public function exportReport(Request $request)
    {
        $reportType = $request->report_type;
        $format = $request->format; // pdf, excel, csv
        
        switch ($reportType) {
            case 'store_performance':
                return $this->exportStorePerformance($format, $request);
            case 'financial_summary':
                return $this->exportFinancialSummary($format, $request);
            case 'system_analytics':
                return $this->exportSystemAnalytics($format, $request);
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
    }

    // Helper methods
    private function calculateStoreProfit($storeId, $dateFrom, $dateTo)
    {
        $invoices = Invoice::where('store_id', $storeId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('items.product')
            ->get();

        $totalRevenue = $invoices->sum('total');
        $totalCost = 0;

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                if ($item->product) {
                    $totalCost += $item->quantity * $item->product->purchase_price;
                }
            }
        }

        $profit = $totalRevenue - $totalCost;
        $margin = $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0;

        return [
            'profit' => $profit,
            'margin' => $margin
        ];
    }

    private function calculateGrowthRate($storeId, $dateFrom, $dateTo)
    {
        $currentPeriod = Invoice::where('store_id', $storeId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('total');

        $previousPeriod = Invoice::where('store_id', $storeId)
            ->whereBetween('created_at', [
                $dateFrom->copy()->subDays($dateTo->diffInDays($dateFrom)),
                $dateFrom
            ])
            ->sum('total');

        if ($previousPeriod == 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }

        return (($currentPeriod - $previousPeriod) / $previousPeriod) * 100;
    }

    private function getSystemOverview()
    {
        return [
            'total_stores' => Store::count(),
            'active_stores' => Store::where('is_active', true)->count(),
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_products' => Product::count(),
            'total_invoices' => Invoice::count(),
            'total_revenue' => Invoice::sum('total'),
            'avg_order_value' => Invoice::avg('total'),
        ];
    }

    private function getPerformanceMetrics()
    {
        return [
            'avg_response_time' => 0.23, // seconds
            'system_uptime' => 99.9, // percentage
            'error_rate' => 0.01, // percentage
            'active_sessions' => User::where('updated_at', '>=', Carbon::now()->subHours(1))->count(),
        ];
    }

    private function getUserActivityMetrics()
    {
        $now = Carbon::now();
        
        return [
            'daily_active' => User::where('updated_at', '>=', $now->copy()->startOfDay())->count(),
            'weekly_active' => User::where('updated_at', '>=', $now->copy()->startOfWeek())->count(),
            'monthly_active' => User::where('updated_at', '>=', $now->copy()->startOfMonth())->count(),
            'new_registrations' => User::whereDate('created_at', $now->toDateString())->count(),
        ];
    }

    private function getSalesTrends()
    {
        $trends = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $trends[] = [
                'date' => $date->format('Y-m-d'),
                'sales' => Invoice::whereDate('created_at', $date)->sum('total'),
                'orders' => Invoice::whereDate('created_at', $date)->count(),
            ];
        }

        return $trends;
    }

    private function getTopPerformers()
    {
        return [
            'stores' => Store::withSum(['invoices' => function($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()]);
            }], 'total')
            ->orderBy('invoices_sum_total', 'desc')
            ->limit(5)
            ->get(),
            
            'users' => User::withCount(['invoices' => function($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()]);
            }])
            ->orderBy('invoices_count', 'desc')
            ->limit(5)
            ->get(),
        ];
    }

    private function getTotalRevenue($dateFrom, $dateTo)
    {
        return Invoice::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total');
    }

    private function getTotalProfit($dateFrom, $dateTo)
    {
        // This would need to be calculated based on product costs
        // For now, returning estimated profit margin of 30%
        return $this->getTotalRevenue($dateFrom, $dateTo) * 0.3;
    }

    private function getStoreRevenueBreakdown($dateFrom, $dateTo)
    {
        return Store::with('invoices')
            ->get()
            ->map(function ($store) use ($dateFrom, $dateTo) {
                $revenue = $store->invoices()
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->sum('total');
                
                return [
                    'store_name' => $store->name,
                    'revenue' => $revenue,
                    'percentage' => 0, // Will be calculated in the view
                ];
            })
            ->sortByDesc('revenue');
    }

    private function getMonthlyFinancialTrends($dateFrom, $dateTo)
    {
        $trends = [];
        $current = $dateFrom->copy()->startOfMonth();
        
        while ($current <= $dateTo) {
            $revenue = Invoice::whereYear('created_at', $current->year)
                ->whereMonth('created_at', $current->month)
                ->sum('total');
            
            $trends[] = [
                'month' => $current->format('Y-m'),
                'revenue' => $revenue,
                'profit' => $revenue * 0.3, // Estimated
            ];
            
            $current->addMonth();
        }

        return $trends;
    }

    private function getExpenseCategories($dateFrom, $dateTo)
    {
        // This would need a proper expense tracking system
        // For now, returning sample data
        return [
            ['category' => 'التشغيل', 'amount' => 50000],
            ['category' => 'المرتبات', 'amount' => 80000],
            ['category' => 'التسويق', 'amount' => 20000],
            ['category' => 'الصيانة', 'amount' => 15000],
        ];
    }

    private function getROIMetrics($dateFrom, $dateTo)
    {
        $revenue = $this->getTotalRevenue($dateFrom, $dateTo);
        $investment = 500000; // This should come from actual investment data
        
        return [
            'roi_percentage' => $investment > 0 ? (($revenue - $investment) / $investment) * 100 : 0,
            'payback_period' => 12, // months
            'break_even_point' => 300000,
        ];
    }

    private function getActiveUsersMetrics()
    {
        $now = Carbon::now();
        
        return [
            'today' => User::where('updated_at', '>=', $now->copy()->startOfDay())->count(),
            'this_week' => User::where('updated_at', '>=', $now->copy()->startOfWeek())->count(),
            'this_month' => User::where('updated_at', '>=', $now->copy()->startOfMonth())->count(),
        ];
    }

    private function getLoginPatterns()
    {
        // This would need proper session tracking
        // For now, returning sample data based on user activity
        $patterns = [];
        
        for ($hour = 0; $hour < 24; $hour++) {
            $patterns[] = [
                'hour' => $hour,
                'logins' => rand(5, 50), // Sample data
            ];
        }

        return $patterns;
    }

    private function getFeatureUsage()
    {
        return [
            'products' => Product::count(),
            'invoices' => Invoice::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->count(),
            'repairs' => Repair::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->count(),
            'reports_generated' => 150, // This would need tracking
        ];
    }

    private function getUserRetention()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('updated_at', '>=', Carbon::now()->subDays(30))->count();
        
        return [
            'retention_rate' => $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0,
            'churn_rate' => $totalUsers > 0 ? (($totalUsers - $activeUsers) / $totalUsers) * 100 : 0,
        ];
    }

    private function getGeographicDistribution()
    {
        // This would need proper location tracking
        return [
            'الرياض' => 45,
            'جدة' => 30,
            'الدمام' => 15,
            'مكة' => 10,
        ];
    }

    private function getStockLevels()
    {
        return [
            'total_items' => Product::sum('quantity'),
            'low_stock' => Product::whereRaw('quantity <= min_quantity')->count(),
            'out_of_stock' => Product::where('quantity', 0)->count(),
            'overstock' => Product::whereRaw('quantity > min_quantity * 5')->count(),
        ];
    }

    private function getInventoryTurnover()
    {
        // This would need proper calculation based on sales and average inventory
        return Product::with('invoiceItems')
            ->get()
            ->map(function ($product) {
                $totalSold = $product->invoiceItems()->sum('quantity');
                $turnoverRate = $product->quantity > 0 ? $totalSold / $product->quantity : 0;
                
                return [
                    'product' => $product->name,
                    'turnover_rate' => $turnoverRate,
                    'status' => $turnoverRate > 4 ? 'سريع' : ($turnoverRate > 2 ? 'متوسط' : 'بطيء'),
                ];
            })
            ->sortByDesc('turnover_rate');
    }

    private function getSlowMovingItems()
    {
        return Product::with('invoiceItems')
            ->get()
            ->filter(function ($product) {
                $recentSales = $product->invoiceItems()
                    ->whereHas('invoice', function ($query) {
                        $query->where('created_at', '>=', Carbon::now()->subDays(90));
                    })
                    ->sum('quantity');
                
                return $recentSales < 5; // Less than 5 sold in 90 days
            })
            ->take(10);
    }

    private function getFastMovingItems()
    {
        return Product::with('invoiceItems')
            ->get()
            ->filter(function ($product) {
                $recentSales = $product->invoiceItems()
                    ->whereHas('invoice', function ($query) {
                        $query->where('created_at', '>=', Carbon::now()->subDays(30));
                    })
                    ->sum('quantity');
                
                return $recentSales > 20; // More than 20 sold in 30 days
            })
            ->take(10);
    }

    private function getStockValueAnalysis()
    {
        return [
            'total_cost_value' => Product::selectRaw('SUM(quantity * purchase_price) as total')->first()->total ?? 0,
            'total_selling_value' => Product::selectRaw('SUM(quantity * selling_price) as total')->first()->total ?? 0,
            'potential_profit' => Product::selectRaw('SUM(quantity * (selling_price - purchase_price)) as profit')->first()->profit ?? 0,
        ];
    }

    private function getReorderAlerts()
    {
        return Product::whereRaw('quantity <= min_quantity')
            ->with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'product' => $product->name,
                    'current_stock' => $product->quantity,
                    'min_quantity' => $product->min_quantity,
                    'category' => $product->category->name ?? 'غير محدد',
                    'urgency' => $product->quantity == 0 ? 'urgent' : 'warning',
                ];
            });
    }
}
