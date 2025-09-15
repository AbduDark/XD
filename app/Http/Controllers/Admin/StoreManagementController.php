<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreManagementController extends Controller
{
    // Middleware is handled in routes/web.php

    public function index(Request $request)
    {
        $query = Store::with(['owner', 'products', 'invoices']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('name_ar', 'like', '%' . $request->search . '%')
                  ->orWhereHas('owner', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $stores = $query->withCount(['products', 'invoices', 'repairs'])
                       ->paginate(15);

        return view('admin.stores.index', compact('stores'));
    }

    public function show(Store $store)
    {
        $store->load([
            'owner',
            'products' => function ($q) { $q->latest()->limit(10); },
            'invoices' => function ($q) { $q->latest()->limit(10); },
            'repairs' => function ($q) { $q->latest()->limit(10); },
            'users'
        ]);

        $statistics = [
            'total_products' => $store->products()->count(),
            'total_invoices' => $store->invoices()->count(),
            'total_repairs' => $store->repairs()->count(),
            'total_revenue' => $store->invoices()->sum('total'),
            'pending_repairs' => $store->repairs()->where('status', 'pending')->count(),
            'low_stock_products' => $store->products()->whereRaw('quantity <= min_quantity')->count(),
        ];

        return view('admin.stores.show', compact('store', 'statistics'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 'super_admin')
                    ->whereDoesntHave('ownedStores')
                    ->get();
        
        return view('admin.stores.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'address_ar' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|size:3',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($data['name']);
        
        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Store::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        Store::create($data);

        return redirect()->route('admin.stores.index')
                        ->with('success', 'تم إنشاء المتجر بنجاح');
    }

    public function edit(Store $store)
    {
        $users = User::where('role', '!=', 'super_admin')->get();
        
        return view('admin.stores.edit', compact('store', 'users'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'address_ar' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|size:3',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Update slug if name changed
        if ($data['name'] !== $store->name) {
            $data['slug'] = Str::slug($data['name']);
            
            // Ensure slug is unique
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Store::where('slug', $data['slug'])->where('id', '!=', $store->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $store->update($data);

        return redirect()->route('admin.stores.index')
                        ->with('success', 'تم تحديث المتجر بنجاح');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        
        return redirect()->route('admin.stores.index')
                        ->with('success', 'تم حذف المتجر بنجاح');
    }

    public function toggleStatus(Store $store)
    {
        $store->update(['is_active' => !$store->is_active]);
        
        $status = $store->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        
        return back()->with('success', "تم {$status} المتجر بنجاح");
    }

    public function assignUser(Request $request, Store $store)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:manager,employee',
        ]);

        $store->users()->syncWithoutDetaching([
            $request->user_id => [
                'role' => $request->role,
                'is_active' => true,
            ]
        ]);

        return back()->with('success', 'تم إضافة المستخدم للمتجر بنجاح');
    }

    public function removeUser(Store $store, User $user)
    {
        $store->users()->detach($user->id);
        
        return back()->with('success', 'تم إزالة المستخدم من المتجر بنجاح');
    }
}
