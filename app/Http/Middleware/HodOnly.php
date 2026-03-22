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
    //Check if logged in
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (!auth()->check() || !in_array(auth()->user()->user_type, [1, 2, 3])) {
abort(403, 'You are not authorised to access that page.');
    }
    return $next($request);
}

}
