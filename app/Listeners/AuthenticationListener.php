<?php

namespace App\Listeners;

use App\Enums\LogEvent;
use App\Enums\LogName;
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
            activity()
                ->useLog(LogName::Authentication->value)
                ->event(LogEvent::Login->value)
                ->causedBy($event->user instanceof Model ? $event->user : null)
                ->withProperties([
                    'email' => $event->user->email,
                    'name' => $event->user->name,
                ])
                ->log('User logged in');
        }

        if ($event instanceof Logout) {
            dd($event);
            activity()
                ->useLog(LogName::Authentication->value)
                ->event(LogEvent::Logout->value)
                ->causedBy($event->user instanceof Model ? $event->user : null)
                ->withProperties([
                    'email' => $event->user->email,
                    'name' => $event->user->name,
                ])
                ->log('User logged out');
        }
    }
}
