<?php

namespace App\Providers;

use App\Services\OutletConnectionManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(OutletConnectionManager::class, function ($app) {
            return new OutletConnectionManager();
        });
    }

    public function boot()
    {
        //
    }
}
