<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Ensure PHP and Carbon use the application timezone from config
        $timezone = config('app.timezone', 'UTC');
        if ($timezone) {
            date_default_timezone_set($timezone);
        }
    }
}
