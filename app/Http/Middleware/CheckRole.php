<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($user->role !== $role) {
            if ($user->role === 'super_admin') {
                // Super admin can access everything
                return $next($request);
            }
            abort(403, 'ليس لديك صلاحية للوصول لهذه الصفحة');
        }

        return $next($request);
    }
}
