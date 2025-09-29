<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfWrongRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles)) {
            // Redirect berdasarkan role
            return match($user->role) {
                'admin' => redirect('/admin-dashboard'),
                'seller' => redirect('/seller-dashboard'),
                default => redirect('/'),
            };
        }

        return $next($request);
    }
}
