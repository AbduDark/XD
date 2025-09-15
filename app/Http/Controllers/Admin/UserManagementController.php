<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    // Middleware is handled in routes/web.php

    public function index(Request $request)
    {
        $query = User::with(['ownedStores', 'stores']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->withCount(['ownedStores', 'stores'])
                      ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'ownedStores' => function ($q) {
                $q->withCount(['products', 'invoices', 'repairs']);
            },
            'stores' => function ($q) {
                $q->withPivot('role', 'is_active', 'created_at');
            }
        ]);

        $userActivity = [
            'total_invoices' => $user->invoices()->count(),
            'total_repairs' => $user->repairs()->count(),
            'total_cash_transfers' => $user->cashTransfers()->count(),
            'recent_activity' => $user->invoices()
                                    ->latest()
                                    ->limit(10)
                                    ->get(),
        ];

        return view('admin.users.show', compact('user', 'userActivity'));
    }

    public function create()
    {
        $stores = Store::where('is_active', true)->get();

        return view('admin.users.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,employee',
            'is_active' => 'boolean',
            'stores' => 'array',
            'stores.*' => 'exists:stores,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Assign stores if provided
        if ($request->filled('stores')) {
            $storeData = [];
            foreach ($request->stores as $storeId) {
                $storeData[$storeId] = [
                    'role' => 'employee',
                    'is_active' => true,
                ];
            }
            $user->stores()->attach($storeData);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        $stores = Store::where('is_active', true)->get();
        $userStores = $user->stores()->pluck('stores.id')->toArray();

        return view('admin.users.edit', compact('user', 'stores', 'userStores'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => 'required|in:super_admin,admin,employee',
            'is_active' => 'boolean',
            'stores' => 'array',
            'stores.*' => 'exists:stores,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Update store assignments
        if ($request->has('stores')) {
            $storeData = [];
            foreach ($request->stores as $storeId) {
                $storeData[$storeId] = [
                    'role' => 'employee',
                    'is_active' => true,
                ];
            }
            $user->stores()->sync($storeData);
        } else {
            $user->stores()->detach();
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'لا يمكن حذف مدير النظام');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function toggleStatus(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'لا يمكن تعديل حالة مدير النظام');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'تفعيل' : 'إلغاء تفعيل';

        return back()->with('success', "تم {$status} المستخدم بنجاح");
    }

    public function impersonate(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'لا يمكن تسجيل الدخول كمدير النظام');
        }

        if (!$user->is_active) {
            return back()->with('error', 'لا يمكن تسجيل الدخول كمستخدم غير نشط');
        }

        session(['impersonating' => auth()->id()]);
        auth()->login($user);

        return redirect()->route('dashboard')
                        ->with('success', "تم تسجيل الدخول كـ {$user->name}");
    }

    public function stopImpersonating()
    {
        if (session()->has('impersonating')) {
            $originalUserId = session()->pull('impersonating');
            $originalUser = User::find($originalUserId);

            if ($originalUser) {
                auth()->login($originalUser);
                return redirect()->route('admin.users.index')
                                ->with('success', 'تم العودة لحسابك الأصلي');
            }
        }

        return redirect()->route('admin.users.index');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->user_ids)
                    ->where('role', '!=', 'super_admin')
                    ->get();

        $count = $users->count();

        switch ($request->action) {
            case 'activate':
                $users->each(function ($user) {
                    $user->update(['is_active' => true]);
                });
                return back()->with('success', "تم تفعيل {$count} مستخدم");

            case 'deactivate':
                $users->each(function ($user) {
                    $user->update(['is_active' => false]);
                });
                return back()->with('success', "تم إلغاء تفعيل {$count} مستخدم");

            case 'delete':
                $users->each(function ($user) {
                    $user->delete();
                });
                return back()->with('success', "تم حذف {$count} مستخدم");
        }

        return back();
    }
}