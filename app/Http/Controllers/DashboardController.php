<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
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

        $pendingRepairs = Repair::where('status', 'pending')
            ->orWhere('status', 'in_progress')
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
                    'total' => $invoice->total,
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
        $days = collect();
        $sales = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->locale('ar')->dayName;

            $daySales = Invoice::whereDate('created_at', $date->toDateString())
                ->sum('total');

            $days->push($dayName);
            $sales->push($daySales);
        }

        return response()->json([
            'labels' => $days,
            'sales' => $sales
        ]);
    }
}