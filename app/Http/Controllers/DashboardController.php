<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Repair;
use App\Models\CashTransfer;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'today_sales' => Invoice::whereDate('created_at', $today)->sum('total'),
            'today_sales_count' => Invoice::whereDate('created_at', $today)->count(),
            'month_sales' => Invoice::where('created_at', '>=', $thisMonth)->sum('total'),
            'month_sales_count' => Invoice::where('created_at', '>=', $thisMonth)->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('quantity <= min_quantity')->count(),
            'pending_repairs' => Repair::where('status', 'pending')->count(),
            'today_cash_transfers' => CashTransfer::whereDate('created_at', $today)->sum('amount'),
            'today_returns' => ReturnItem::whereDate('created_at', $today)->sum('amount'),
        ];

        // Chart data
        $salesChart = $this->getSalesChartData();
        $lowStockProducts = Product::whereRaw('quantity <= min_quantity')->with('category')->get();

        return view('dashboard', compact('stats', 'salesChart', 'lowStockProducts'));
    }

    private function getSalesChartData()
    {
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Invoice::whereDate('created_at', $date)->sum('total');
            $salesData[] = [
                'date' => $date->format('M d'),
                'sales' => $sales
            ];
        }
        return $salesData;
    }
}