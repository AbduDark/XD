<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'store.access']);
    }
    public function apiIndex()
    {
        $products = Product::select('id', 'name', 'price', 'quantity')
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'quantity' => $product->quantity,
                ];
            });

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('barcode', 'LIKE', "%{$query}%")
            ->where('quantity', '>', 0)
            ->take(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'quantity' => $product->quantity,
                    'barcode' => $product->barcode,
                ];
            });

        return response()->json($products);
    }
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && $request->search) {
            $query->where('name_ar', 'like', '%' . $request->search . '%')
                  ->orWhere('name_en', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(20);
        $categories = ProductCategory::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'barcode' => 'nullable|string|unique:products',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'nullable|integer|min:0',
        ]);

        // The store_id will be automatically assigned via the model's booted method
        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'nullable|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        try {
            // Check if product has any invoice items
            if ($product->invoiceItems()->count() > 0) {
                return redirect()->route('products.index')
                    ->with('error', 'لا يمكن حذف هذا المنتج لأنه مرتبط بفواتير موجودة');
            }

            $product->delete();
            return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'حدث خطأ أثناء حذف المنتج');
        }
    }
}
