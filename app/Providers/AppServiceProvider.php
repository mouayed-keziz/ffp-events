<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Constants\Countries;

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

        // Register custom validation rules for country select
        Validator::extend('country_select_valid', function ($attribute, $value, $parameters, $validator) {
            if (!is_array($value)) {
                return false;
            }

            // Check if a country is actually selected
            $selectedCode = $value['selected_country_code'] ?? null;
            if (empty($selectedCode)) {
                return false;
            }

            // Validate that the selected country code exists in our list
            return array_key_exists($selectedCode, Countries::COUNTRIES);
        });

        Validator::extend('country_select_valid_optional', function ($attribute, $value, $parameters, $validator) {
            if (!is_array($value)) {
                return true; // Nullable is OK for optional fields
            }

            // If we have a selection, validate it
            $selectedCode = $value['selected_country_code'] ?? null;
            if (!empty($selectedCode)) {
                return array_key_exists($selectedCode, Countries::COUNTRIES);
            }

            return true; // No selection is OK for optional fields
        });
    }
}
