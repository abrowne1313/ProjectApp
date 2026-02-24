<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->user_type, [1, 2])) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'You are not authorised to access that page.');
        }

        return $next($request);
    }
}
