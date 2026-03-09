<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->get('admin_logged_in') !== true) {
            return redirect('/admin')->with('error', 'Please login as admin.');
        }

        return $next($request);
    }
}
