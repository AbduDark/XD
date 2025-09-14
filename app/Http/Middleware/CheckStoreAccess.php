
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class CheckStoreAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super admins can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if route contains store parameter
        $storeSlug = $request->route('store');
        if ($storeSlug) {
            $store = Store::where('slug', $storeSlug)->first();
            
            if (!$store) {
                abort(404, 'المتجر غير موجود');
            }

            // Check if user can access this specific store
            if (!$user->canAccessStore($store->id)) {
                abort(403, 'ليس لديك صلاحية للوصول لهذا المتجر');
            }

            // Check specific permission if provided
            if ($permission && !$this->hasPermission($user, $store, $permission)) {
                abort(403, 'ليس لديك صلاحية لهذا الإجراء');
            }

            // Set the store context
            session(['current_store_id' => $store->id]);
            view()->share('currentStore', $store);
            app()->instance('currentStore', $store);
        } else {
            // For routes without store parameter, ensure user has at least one store
            $currentStore = $user->currentStore();
            if (!$currentStore) {
                abort(403, 'ليس لديك صلاحية للوصول لأي متجر');
            }

            // Check permission for current store
            if ($permission && !$this->hasPermission($user, $currentStore, $permission)) {
                abort(403, 'ليس لديك صلاحية لهذا الإجراء');
            }
        }

        return $next($request);
    }

    /**
     * Check if user has specific permission for a store
     */
    private function hasPermission($user, $store, $permission)
    {
        // Store owner has all permissions
        if ($store->owner_id === $user->id) {
            return true;
        }

        // Check store-specific permissions
        $settings = $store->settings ?? [];
        $permissions = $settings['permissions'] ?? [];

        // Parse permission string (e.g., 'products.create', 'reports.view')
        $parts = explode('.', $permission);
        if (count($parts) !== 2) {
            return false;
        }

        [$module, $action] = $parts;

        return $permissions[$module][$action] ?? false;
    }
}
