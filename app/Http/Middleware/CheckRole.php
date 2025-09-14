<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if ($user->role !== $role) {
            if ($user->role === 'super_admin') {
                // Super admin can access everything
                return $next($request);
            }
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        return $next($request);
    }
}
