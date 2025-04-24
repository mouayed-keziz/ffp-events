<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CustomSessionLifetime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Set session lifetime based on guard
        $guard = Auth::getDefaultDriver();

        switch ($guard) {
            case 'visitor':
            case 'exhibitor':
                // 30 days in minutes
                Config::set('session.lifetime', 30 * 24 * 60);
                break;

            case 'web':
                // 24 hours in minutes
                Config::set('session.lifetime', 24 * 60);
                break;

            default:
                // Keep default session lifetime
                break;
        }

        return $next($request);
    }
}
