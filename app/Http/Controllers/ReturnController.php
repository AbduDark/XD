<?php

namespace App\Http\Controllers;

use App\Models\ReturnItem;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnItem::with('product', 'user');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('reason', 'like', '%' . $request->search . '%')
                  ->orWhereHas('product', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('name_ar', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        return view('returns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            ReturnItem::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'amount' => $request->amount,
            ]);

            // Return products to inventory
            $product = Product::find($request->product_id);
            $product->increment('quantity', $request->quantity);
        });

        return redirect()->route('returns.index')->with('success', 'تم إضافة المرتجع بنجاح');
    }

    public function show(ReturnItem $return)
    {
        $return->load('product', 'user');
        return view('returns.show', compact('return'));
    }

    public function destroy(ReturnItem $return)
    {
        DB::transaction(function () use ($return) {
            // Remove products from inventory
            $product = Product::find($return->product_id);
            $product->decrement('quantity', $return->quantity);

            $return->delete();
        });

        return redirect()->route('returns.index')->with('success', 'تم حذف المرتجع بنجاح');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ReturnItem;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnItem::with('product')->latest();
        
        if ($request->has('search') && $request->search) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name_ar', 'like', '%' . $request->search . '%')
                  ->orWhere('name_en', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $returns = $query->paginate(20);
        
        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        $products = Product::all();
        $invoices = Invoice::latest()->limit(100)->get();
        return view('returns.create', compact('products', 'invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            ReturnItem::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'amount' => $request->amount,
                'user_id' => auth()->id(),
            ]);

            // إرجاع الكمية للمخزون
            $product = Product::find($request->product_id);
            $product->increment('quantity', $request->quantity);

            DB::commit();

            return redirect()->route('returns.index')->with('success', 'تم إنشاء المرتجع بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء المرتجع');
        }
    }

    public function show(ReturnItem $return)
    {
        $return->load('product', 'user');
        return view('returns.show', compact('return'));
    }

    public function destroy(ReturnItem $return)
    {
        try {
            DB::beginTransaction();

            // خصم الكمية من المخزون
            $return->product->decrement('quantity', $return->quantity);
            
            $return->delete();

            DB::commit();

            return redirect()->route('returns.index')->with('success', 'تم حذف المرتجع بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ في حذف المرتجع');
        }
    }
}
