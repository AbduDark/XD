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
        return view('reports.index');
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
}
