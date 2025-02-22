<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->guard('web')->check() ||
            auth()->guard('exhibitor')->check() ||
            auth()->guard('visitor')->check()
        ) {
            // Optionally, redirect authenticated users to a default page.
            return redirect()->intended(route("events"));
        }
        return $next($request);
    }
}
