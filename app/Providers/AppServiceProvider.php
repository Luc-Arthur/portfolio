<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
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
        // Paramètres du site (liens, email, CV) disponibles dans le layout public.
        View::composer(['layouts.front', 'contact'], function ($view) {
            static $settings;
            $view->with('settings', $settings ??= Setting::first());
        });
    }
}
