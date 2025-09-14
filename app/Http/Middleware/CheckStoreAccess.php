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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super admins can access any store
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
        }

        return $next($request);
    }
}