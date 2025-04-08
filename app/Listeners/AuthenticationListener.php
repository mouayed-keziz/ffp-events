<?php

namespace App\Listeners;

use App\Activity\AuthenticationActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Database\Eloquent\Model;

class AuthenticationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if ($event instanceof Login) {
            AuthenticationActivity::logLogin($event->user);
        }

        if ($event instanceof Logout) {
            // Fix for logout event where user might be null
            // For the "visitor" guard, the user might be null in the logout event
            // We don't log anything in that case as the AuthenticationActivity handles the null check
            AuthenticationActivity::logLogout($event->user);
        }
    }
}
