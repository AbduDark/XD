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
                abort(403, 'ليس لديك الصلاحية المطلوبة');
            }

            return $next($request);
        }

        return $next($request);
    }

    /**
     * Check if user has specific permission for store
     */
    private function hasPermission($user, $store, $permission): bool
    {
        // For now, if user can access store, they have all permissions
        // You can implement more granular permissions here
        return $user->canAccessStore($store->id);
    }
}
