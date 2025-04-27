<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add this line to suppress the deprecation warning
        error_reporting(E_ALL ^ E_DEPRECATED);

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // Make sure asset URLs are properly generated with the correct scheme
            $this->app['url']->macro('asset', function ($path) {
                return url($path, [], true);
            });

            // Use secure cookies in production to maintain session state
            config(['session.secure' => true]);
        }

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(['ar', 'en', 'fr']);
        });
    }
}
