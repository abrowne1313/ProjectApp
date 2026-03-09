<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HodOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
// app/Http/Middleware/HodOnly.php
public function handle($request, Closure $next)
{
    if (!auth()->check() || !in_array(auth()->user()->user_type, [1, 2, 3])) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'You are not authorised to access that page.');
        }
    return $next($request);
}

}
