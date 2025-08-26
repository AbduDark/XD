<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\CashTransfer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // إحصائيات عامة
        $monthSales = Invoice::where('created_at', '>=', $thisMonth)->sum('total');
        $monthInvoices = Invoice::where('created_at', '>=', $thisMonth)->count();
        $pendingRepairs = \App\Models\Repair::where('status', 'pending')->count();
        $totalReturns = ReturnItem::sum('amount');

        // بيانات الرسوم البيانية
        $salesChartData = [];
        $salesChartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $salesChartLabels[] = $date->format('d/m');
            $salesChartData[] = Invoice::whereDate('created_at', $date)->sum('total');
        }

        // بيانات الفئات
        $categories = \App\Models\ProductCategory::withCount('products')->get();
        $categoryLabels = $categories->pluck('name_ar')->toArray();
        $categoryData = $categories->pluck('products_count')->toArray();

        return view('reports.index', compact(
            'monthSales', 'monthInvoices', 'pendingRepairs', 'totalReturns',
            'salesChartData', 'salesChartLabels', 'categoryLabels', 'categoryData'
        ));
    }

    public function sales(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();

        $invoices = Invoice::with('items.product', 'user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $invoices->sum('total');
        $totalDiscount = $invoices->sum('discount');
        $invoiceCount = $invoices->count();

        $dailySales = $invoices->groupBy(function($invoice) {
            return $invoice->created_at->format('Y-m-d');
        })->map(function($dayInvoices) {
            return [
                'total' => $dayInvoices->sum('total'),
                'count' => $dayInvoices->count(),
            ];
        });

        return view('reports.sales', compact('invoices', 'totalSales', 'totalDiscount', 'invoiceCount', 'dailySales', 'dateFrom', 'dateTo'));
    }

    public function inventory()
    {
        $products = Product::with('category')->get();
        
        $totalValue = $products->sum(function($product) {
            return $product->quantity * $product->purchase_price;
        });

        $lowStockProducts = $products->filter(function($product) {
            return $product->quantity <= $product->min_quantity;
        });

        $outOfStockProducts = $products->filter(function($product) {
            return $product->quantity == 0;
        });

        return view('reports.inventory', compact('products', 'totalValue', 'lowStockProducts', 'outOfStockProducts'));
    }

    public function repairs(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();

        $repairs = \App\Models\Repair::with('user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRepairs = $repairs->count();
        $totalRevenue = $repairs->sum('repair_cost');
        $completedRepairs = $repairs->where('status', 'completed')->count();
        $pendingRepairs = $repairs->where('status', 'pending')->count();

        $statusStats = $repairs->groupBy('status')->map(function($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('repair_cost')
            ];
        });

        return view('reports.repairs', compact(
            'repairs', 'totalRepairs', 'totalRevenue', 'completedRepairs', 
            'pendingRepairs', 'statusStats', 'dateFrom', 'dateTo'
        ));
    }
}
