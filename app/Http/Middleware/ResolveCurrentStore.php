<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class ResolveCurrentStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Super admins don't need a specific store resolution
            if ($user->isSuperAdmin()) {
                return $next($request);
            }

            // Get the current store for the user
            $currentStore = $user->currentStore();
            
            if ($currentStore) {
                // Set the current store in the session and share it globally
                session(['current_store_id' => $currentStore->id]);
                view()->share('currentStore', $currentStore);
                
                // Also make it available globally in the app
                app()->instance('currentStore', $currentStore);
            } else {
                // If user has no store access, redirect or abort
                if (!$user->isSuperAdmin()) {
                    abort(403, 'لا يوجد لديك صلاحية للوصول لأي متجر');
                }
            }
        }

        return $next($request);
    }
}