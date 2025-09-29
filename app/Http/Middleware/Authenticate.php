<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('admin-dashboard') || $request->is('admin-dashboard/*')) {
            return url('/admin-dashboard/login');
        }

        if ($request->is('seller-dashboard') || $request->is('seller-dashboard/*')) {
            return url('/seller-dashboard/login');
        }

        return route('login');
    }
}
