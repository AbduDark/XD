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
        $query = Invoice::with('items.product')->latest();

        if ($request->has('search') && $request->search) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $invoices = $query->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $products = Product::all();
        return view('invoices.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // إنشاء رقم الفاتورة
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'subtotal' => 0,
                'tax' => 0,
                'discount' => $request->discount ?? 0,
                'total' => 0,
                'user_id' => auth()->id(),
            ]);

            $subtotal = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $itemTotal = $item['quantity'] * $item['price'];

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $itemTotal,
                ]);

                // تحديث الكمية
                $product->decrement('quantity', $item['quantity']);

                $subtotal += $itemTotal;
            }

            $tax = $subtotal * 0.15; // ضريبة القيمة المضافة 15%
            $total = $subtotal + $tax - ($request->discount ?? 0);

            $invoice->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            DB::commit();

            return redirect()->route('invoices.index')->with('success', 'تم إنشاء الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء الفاتورة');
        }
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
        try {
            DB::beginTransaction();

            // إرجاع الكميات
            foreach ($invoice->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $invoice->delete();

            DB::commit();

            return redirect()->route('invoices.index')->with('success', 'تم حذف الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ في حذف الفاتورة');
        }
    }
}
