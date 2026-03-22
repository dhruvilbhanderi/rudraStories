<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('author')->guest()) {
            return redirect('/author/login');
        }
        return $next($request);
    }
}
