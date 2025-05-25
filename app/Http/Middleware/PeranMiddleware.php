<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PeranMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $peran
     * @return mixed
     */
    public function handle($request, Closure $next, $peran)
    {
        if (!Auth::check() || Auth::user()->peran !== $peran) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}