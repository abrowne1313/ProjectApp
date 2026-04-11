<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HodOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 1=Admin, 2=Centre Admin, 3=HOD. 
        if (!in_array(auth()->user()->user_type, [1, 2, 3])) {
            abort(403, 'You are not authorised to access that page.');
        }

        return $next($request);
    }
}