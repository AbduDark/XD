<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Repair;
use App\Models\CashTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class AdvancedReportsController extends Controller
{
    // Middleware is handled in routes/web.php

    public function index()
    {
        $systemMetrics = $this->getSystemMetrics();
        $storePerformance = $this->getStorePerformance();
        $financialSummary = $this->getFinancialSummary();

        return view('admin.reports.advanced', compact(
            'systemMetrics',
            'storePerformance',
            'financialSummary'
        ));
    }

    public function getSystemMetrics()
    {
        $totalStores = Store::count();
        $activeStores = Store::where('is_active', true)->count();
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();

        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        $currentMonthRevenue = Invoice::whereMonth('created_at', $currentMonth->month)
                                    ->whereYear('created_at', $currentMonth->year)
                                    ->sum('total');

        $previousMonthRevenue = Invoice::whereMonth('created_at', $previousMonth->month)
                                     ->whereYear('created_at', $previousMonth->year)
                                     ->sum('total');

        $revenueGrowth = $previousMonthRevenue > 0
            ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100
            : 0;

        return [
            'total_stores' => $totalStores,
            'active_stores' => $activeStores,
            'store_activity_rate' => $totalStores > 0 ? round(($activeStores / $totalStores) * 100, 2) : 0,
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'user_activity_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0,
            'current_month_revenue' => $currentMonthRevenue,
            'previous_month_revenue' => $previousMonthRevenue,
            'revenue_growth' => round($revenueGrowth, 2),
            'total_products' => Product::count(),
            'pending_repairs' => Repair::where('status', 'pending')->count(),
        ];
    }

    public function getStorePerformance()
    {
        return Store::withCount(['products', 'invoices', 'repairs'])
                   ->with('owner')
                   ->get()
                   ->map(function ($store) {
                       $revenue = $store->invoices()->sum('total');
                       $avgOrderValue = $store->invoices_count > 0 ? $revenue / $store->invoices_count : 0;

                       return [
                           'id' => $store->id,
                           'name' => $store->name,
                           'owner' => $store->owner->name,
                           'products_count' => $store->products_count,
                           'invoices_count' => $store->invoices_count,
                           'repairs_count' => $store->repairs_count,
                           'total_revenue' => $revenue,
                           'avg_order_value' => round($avgOrderValue, 2),
                           'status' => $store->is_active ? 'نشط' : 'غير نشط',
                           'created_at' => $store->created_at->format('Y-m-d'),
                       ];
                   });
    }

    public function getFinancialSummary()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $dailyRevenue = Invoice::whereDate('created_at', $today)->sum('total');
        $monthlyRevenue = Invoice::where('created_at', '>=', $thisMonth)->sum('total');
        $yearlyRevenue = Invoice::where('created_at', '>=', $thisYear)->sum('total');

        $dailyOrders = Invoice::whereDate('created_at', $today)->count();
        $monthlyOrders = Invoice::where('created_at', '>=', $thisMonth)->count();
        $yearlyOrders = Invoice::where('created_at', '>=', $thisYear)->count();

        $totalCashIn = CashTransfer::where('type', 'in')->sum('amount');
        $totalCashOut = CashTransfer::where('type', 'out')->sum('amount');
        $netCashFlow = $totalCashIn - $totalCashOut;

        return [
            'daily_revenue' => $dailyRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'yearly_revenue' => $yearlyRevenue,
            'daily_orders' => $dailyOrders,
            'monthly_orders' => $monthlyOrders,
            'yearly_orders' => $yearlyOrders,
            'total_cash_in' => $totalCashIn,
            'total_cash_out' => $totalCashOut,
            'net_cash_flow' => $netCashFlow,
            'avg_daily_revenue' => $dailyOrders > 0 ? $dailyRevenue / $dailyOrders : 0,
            'avg_monthly_revenue' => $monthlyOrders > 0 ? $monthlyRevenue / $monthlyOrders : 0,
        ];
    }

    public function exportReport(Request $request)
    {
        $type = $request->get('type', 'store_performance');

        switch ($type) {
            case 'store_performance':
                return $this->exportStorePerformance();
            case 'financial_summary':
                return $this->exportFinancialSummary();
            case 'system_analytics':
                return $this->exportSystemAnalytics();
            default:
                return redirect()->back()->with('error', 'نوع التقرير غير صحيح');
        }
    }

    private function exportStorePerformance()
    {
        $data = $this->getStorePerformance();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="store_performance_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID', 'Store Name', 'Owner', 'Products Count',
                'Invoices Count', 'Repairs Count', 'Total Revenue',
                'Average Order Value', 'Status', 'Created At'
            ]);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row['id'],
                    $row['name'],
                    $row['owner'],
                    $row['products_count'],
                    $row['invoices_count'],
                    $row['repairs_count'],
                    $row['total_revenue'],
                    $row['avg_order_value'],
                    $row['status'],
                    $row['created_at']
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportFinancialSummary()
    {
        $data = $this->getFinancialSummary();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="financial_summary_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, ['Metric', 'Value']);

            foreach ($data as $key => $value) {
                fputcsv($file, [ucfirst(str_replace('_', ' ', $key)), $value]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportSystemAnalytics()
    {
        $data = $this->getSystemMetrics();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="system_analytics_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, ['Metric', 'Value']);

            foreach ($data as $key => $value) {
                fputcsv($file, [ucfirst(str_replace('_', ' ', $key)), $value]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
