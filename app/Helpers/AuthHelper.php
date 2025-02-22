<?php

if (!function_exists('currentUser')) {
    /**
     * Get the current authenticated user and their guard.
     *
     * @return array|null
     */
    function currentUser()
    {
        $guards = ['web',  'exhibitor', 'visitor'];

        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                return  auth()->guard($guard)->user();
            }
        }

        return null;
    }
}

if (!function_exists('checkUser')) {
    /**
     * Check if a user exists in any of the specified guards.
     *
     * @param mixed $userId
     * @return bool
     */
    function checkUser(): bool
    {
        $guards = ['web',  'exhibitor', 'visitor'];

        foreach ($guards as $guard) {
            $user = auth()->guard($guard)->user();
            if ($user) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('checkExhibitor')) {
    /**
     * Check if a user from the exhibitor guard is authenticated.
     *
     * @return bool
     */
    function checkExhibitor(): bool
    {
        return auth()->guard('exhibitor')->check();
    }
}

if (!function_exists('checkVisitor')) {
    /**
     * Check if a user from the visitor guard is authenticated.
     *
     * @return bool
     */
    function checkVisitor(): bool
    {
        return auth()->guard('visitor')->check();
    }
}
