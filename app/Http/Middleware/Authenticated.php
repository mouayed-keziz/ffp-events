<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated in any guard
        if (
            auth()->guard('web')->check() ||
            auth()->guard('exhibitor')->check() ||
            auth()->guard('visitor')->check()
        ) {
            return $next($request);
        }
        return redirect()->guest(route('login'));
    }
}
