<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('user', 'items.product');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            $items = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("الكمية المطلوبة من {$product->name_ar} غير متوفرة");
                }

                $totalPrice = $product->selling_price * $item['quantity'];
                $subtotal += $totalPrice;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'total_price' => $totalPrice,
                ];
            }

            $discount = $request->discount ?? 0;
            $total = $subtotal - $discount;

            $invoice = Invoice::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'paid',
            ]);

            foreach ($items as $item) {
                $invoice->items()->create($item);
                
                // Update product quantity
                $product = Product::find($item['product_id']);
                $product->decrement('quantity', $item['quantity']);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items.product', 'user');
        return view('invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('items.product', 'user');
        return view('invoices.print', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            // Return products to inventory
            foreach ($invoice->items as $item) {
                $product = Product::find($item->product_id);
                $product->increment('quantity', $item->quantity);
            }

            $invoice->delete();
        });

        return redirect()->route('invoices.index')->with('success', 'تم حذف الفاتورة بنجاح');
    }
}
