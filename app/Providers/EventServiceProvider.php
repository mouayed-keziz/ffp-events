<?php

namespace App\Providers;

use App\Listeners\AuthenticationListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            AuthenticationListener::class,
        ],
        Logout::class => [
            AuthenticationListener::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
