
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class ResolveCurrentStore
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Super admins don't need a specific store resolution
            if ($user->isSuperAdmin()) {
                return $next($request);
            }

            // Check if store is specified in route
            $storeId = $request->route('store');
            if ($storeId) {
                $store = is_numeric($storeId) ? Store::find($storeId) : Store::where('slug', $storeId)->first();
                
                if ($store && $user->canAccessStore($store->id)) {
                    session(['current_store_id' => $store->id]);
                    view()->share('currentStore', $store);
                    app()->instance('currentStore', $store);
                    return $next($request);
                }
            }

            // Get or create user's store
            $currentStore = $user->ownedStores()->where('is_active', true)->first();
            
            if (!$currentStore) {
                // Create a default store for the user if they don't have one
                $currentStore = Store::create([
                    'name' => $user->name . ' Store',
                    'name_ar' => 'متجر ' . $user->name,
                    'slug' => \Str::slug($user->name . '-store-' . $user->id),
                    'owner_id' => $user->id,
                    'is_active' => true,
                    'tax_rate' => 15.0,
                    'currency' => 'SAR'
                ]);
            }
            
            if ($currentStore) {
                session(['current_store_id' => $currentStore->id]);
                view()->share('currentStore', $currentStore);
                app()->instance('currentStore', $currentStore);
            } else {
                abort(403, 'لا يوجد لديك صلاحية للوصول لأي متجر');
            }
        }

        return $next($request);
    }
}
