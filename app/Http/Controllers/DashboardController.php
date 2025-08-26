<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Invoice;
use App\Models\Repair;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات أساسية
        $totalProducts = Product::count();

        $todaySales = Invoice::whereDate('created_at', today())
            ->sum('total');

        $pendingRepairs = Repair::whereIn('status', ['pending', 'in_progress'])
            ->count();

        $lowStock = Product::where('quantity', '<=', 10)->count();

        return view('dashboard', compact(
            'totalProducts',
            'todaySales',
            'pendingRepairs',
            'lowStock'
        ));
    }

    public function recentInvoices()
    {
        $invoices = Invoice::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($invoice) {
                return [
                    'id' => $invoice->id,
                    'customer_name' => $invoice->customer_name,
                    'total' => (float) $invoice->total,
                    'created_at' => $invoice->created_at->toISOString(),
                ];
            });

        return response()->json($invoices);
    }

    public function recentRepairs()
    {
        $repairs = Repair::latest()
            ->take(5)
            ->get()
            ->map(function($repair) {
                return [
                    'id' => $repair->id,
                    'device_type' => $repair->device_type,
                    'customer_name' => $repair->customer_name,
                    'status' => $repair->status,
                    'created_at' => $repair->created_at->toISOString(),
                ];
            });

        return response()->json($repairs);
    }

    public function salesChart()
    {
        // بيانات آخر 7 أيام
        $days = collect();
        $sales = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->locale('ar')->translatedFormat('l');

            $dailySales = Invoice::whereDate('created_at', $date->format('Y-m-d'))
                ->sum('total');

            $days->push($dayName);
            $sales->push((float) $dailySales);
        }

        return response()->json([
            'labels' => $days->toArray(),
            'sales' => $sales->toArray(),
        ]);
    }

    public function stats()
    {
        // إحصائيات محدثة للتحديث المباشر
        $totalProducts = Product::count();
        $todaySales = Invoice::whereDate('created_at', today())->sum('total');
        $pendingRepairs = Repair::whereIn('status', ['pending', 'in_progress'])->count();
        $lowStock = Product::where('quantity', '<=', 10)->count();

        return response()->json([
            'totalProducts' => $totalProducts,
            'todaySales' => (float) $todaySales,
            'pendingRepairs' => $pendingRepairs,
            'lowStock' => $lowStock,
            'lastUpdated' => now()->toISOString(),
        ]);
    }

    public function weeklyStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyData = collect();

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $sales = Invoice::whereDate('created_at', $date->format('Y-m-d'))->sum('total');
            $repairs = Repair::whereDate('created_at', $date->format('Y-m-d'))->count();

            $weeklyData->push([
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->locale('ar')->translatedFormat('l'),
                'sales' => (float) $sales,
                'repairs' => $repairs,
            ]);
        }

        return response()->json($weeklyData);
    }

    public function monthlyStats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // مبيعات الشهر الحالي
        $currentMonthSales = Invoice::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total');

        // مبيعات الشهر الماضي
        $lastMonthSales = Invoice::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total');

        // نسبة النمو
        $growthRate = $lastMonthSales > 0 ?
            (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // أفضل المنتجات مبيعاً هذا الشهر
        $topProducts = Product::withCount(['invoiceItems' => function($query) use ($currentMonth, $currentYear) {
            $query->whereHas('invoice', function($q) use ($currentMonth, $currentYear) {
                $q->whereMonth('created_at', $currentMonth)
                  ->whereYear('created_at', $currentYear);
            });
        }])
        ->orderBy('invoice_items_count', 'desc')
        ->take(5)
        ->get()
        ->map(function($product) {
            return [
                'name' => $product->name,
                'sales_count' => $product->invoice_items_count,
                'price' => (float) $product->price,
            ];
        });

        return response()->json([
            'current_month_sales' => (float) $currentMonthSales,
            'last_month_sales' => (float) $lastMonthSales,
            'growth_rate' => round($growthRate, 2),
            'top_products' => $topProducts,
        ]);
    }

    public function productCategories()
    {
        // إحصائيات حسب فئات المنتجات
        $categories = Product::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->map(function($item) {
                return [
                    'category' => $item->category ?: 'غير محدد',
                    'count' => $item->count,
                ];
            });

        return response()->json($categories);
    }

    public function repairStats()
    {
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        $repairStats = collect();

        foreach ($statuses as $status) {
            $count = Repair::where('status', $status)->count();
            $repairStats->push([
                'status' => $status,
                'count' => $count,
                'label' => $this->getStatusLabel($status),
            ]);
        }

        return response()->json($repairStats);
    }

    public function inventoryAlerts()
    {
        // تنبيهات المخزون
        $lowStock = Product::where('quantity', '<=', 10)
            ->where('quantity', '>', 0)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->quantity,
                    'price' => (float) $product->price,
                    'alert_level' => $product->quantity <= 5 ? 'critical' : 'warning',
                ];
            });

        $outOfStock = Product::where('quantity', 0)->count();

        return response()->json([
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'total_alerts' => $lowStock->count() + $outOfStock,
        ]);
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'معلق',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            'delivered' => 'تم التسليم'
        ];

        return $labels[$status] ?? $status;
    }
}
