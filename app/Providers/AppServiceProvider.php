<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Admin directive
        Blade::if('admin', function () {
            return auth()->user() && auth()->user()->user_role == 'admin';
        });

        Blade::if('client', function () {
            return auth()->user() && auth()->user()->user_role == 'client';
        });
    }
}