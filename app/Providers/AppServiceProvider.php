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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(['ar', 'en', 'fr']);
        });
    }
}
