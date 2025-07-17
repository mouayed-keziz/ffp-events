<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'local_middleware' =>  \App\Http\Middleware\SetLocalMiddleware::class,
            'is_authenticated'         => \App\Http\Middleware\Authenticated::class,
            'is_admin'        => \App\Http\Middleware\IsAdmin::class,
            'is_exhibitor'    => \App\Http\Middleware\IsExhibitor::class,
            'is_visitor'      => \App\Http\Middleware\IsVisitor::class,
            'is_guest'        => \App\Http\Middleware\GuestOnly::class,
            'custom.session.lifetime' => \App\Http\Middleware\CustomSessionLifetime::class,
        ]);

        // Apply our custom session lifetime middleware globally
        $middleware->prependToGroup('web', \App\Http\Middleware\CustomSessionLifetime::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
