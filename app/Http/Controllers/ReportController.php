
<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\CashTransfer;
use App\Models\InvoiceItem;
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

        // إحصائيات اليوم
        $todaySales = Invoice::whereDate('created_at', $today)->sum('total');
        $todayProfit = $this->calculateDayProfit($today);

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
            'todaySales', 'todayProfit',
            'salesChartData', 'salesChartLabels', 'categoryLabels', 'categoryData'
        ));
    }

    public function dailyReport(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::yesterday();
        
        // مبيعات اليوم
        $dailySales = Invoice::whereDate('created_at', $date)->sum('total');
        $dailyInvoices = Invoice::whereDate('created_at', $date)->count();
        
        // حساب الربح
        $dailyProfit = $this->calculateDayProfit($date);
        
        // تفاصيل الفواتير
        $invoices = Invoice::with('items.product', 'user')
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        // أفضل المنتجات مبيعاً
        $topProducts = InvoiceItem::whereHas('invoice', function($query) use ($date) {
            $query->whereDate('created_at', $date);
        })
        ->with('product')
        ->selectRaw('product_id, SUM(quantity) as total_sold, SUM(total) as total_revenue')
        ->groupBy('product_id')
        ->orderBy('total_sold', 'desc')
        ->take(10)
        ->get();

        return view('reports.daily', compact(
            'date', 'dailySales', 'dailyInvoices', 'dailyProfit', 
            'invoices', 'topProducts'
        ));
    }

    public function inventoryValue()
    {
        $products = Product::with('category')->get();
        
        // القيمة بسعر البيع
        $totalSellingValue = $products->sum(function($product) {
            return $product->quantity * $product->selling_price;
        });

        // القيمة بسعر الشراء
        $totalPurchaseValue = $products->sum(function($product) {
            return $product->quantity * $product->purchase_price;
        });

        // الربح المتوقع
        $expectedProfit = $totalSellingValue - $totalPurchaseValue;

        $lowStockProducts = $products->filter(function($product) {
            return $product->quantity <= $product->min_quantity;
        });

        $outOfStockProducts = $products->filter(function($product) {
            return $product->quantity == 0;
        });

        // تجميع حسب الفئات
        $categoryStats = $products->groupBy('category.name_ar')->map(function($categoryProducts) {
            return [
                'products_count' => $categoryProducts->count(),
                'total_quantity' => $categoryProducts->sum('quantity'),
                'selling_value' => $categoryProducts->sum(function($p) { return $p->quantity * $p->selling_price; }),
                'purchase_value' => $categoryProducts->sum(function($p) { return $p->quantity * $p->purchase_price; }),
            ];
        });

        return view('reports.inventory-value', compact(
            'products', 'totalSellingValue', 'totalPurchaseValue', 'expectedProfit',
            'lowStockProducts', 'outOfStockProducts', 'categoryStats'
        ));
    }

    public function dailyClosing(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        // المبيعات
        $sales = Invoice::whereDate('created_at', $date)->sum('total');
        $invoicesCount = Invoice::whereDate('created_at', $date)->count();
        
        // الأرباح
        $profit = $this->calculateDayProfit($date);
        
        // المرتجعات
        $returns = ReturnItem::whereDate('created_at', $date)->sum('amount');
        
        // التحويلات النقدية
        $cashIn = CashTransfer::whereDate('created_at', $date)
            ->where('type', 'in')->sum('amount');
        $cashOut = CashTransfer::whereDate('created_at', $date)
            ->where('type', 'out')->sum('amount');
        
        // صافي النقد
        $netCash = $sales - $returns + $cashIn - $cashOut;
        
        // المنتجات المباعة
        $soldProducts = InvoiceItem::whereHas('invoice', function($query) use ($date) {
            $query->whereDate('created_at', $date);
        })
        ->with('product')
        ->selectRaw('product_id, SUM(quantity) as total_sold')
        ->groupBy('product_id')
        ->get();

        return view('reports.daily-closing', compact(
            'date', 'sales', 'invoicesCount', 'profit', 'returns',
            'cashIn', 'cashOut', 'netCash', 'soldProducts'
        ));
    }

    private function calculateDayProfit($date)
    {
        $invoiceItems = InvoiceItem::whereHas('invoice', function($query) use ($date) {
            $query->whereDate('created_at', $date);
        })->with('product')->get();

        $totalProfit = 0;
        foreach($invoiceItems as $item) {
            if($item->product) {
                $profitPerUnit = $item->price - $item->product->purchase_price;
                $totalProfit += $profitPerUnit * $item->quantity;
            }
        }

        return $totalProfit;
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
