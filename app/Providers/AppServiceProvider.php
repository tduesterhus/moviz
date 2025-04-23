<?php

namespace App\Providers;

use App\APIs\OMDbAPI;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OMDbAPI::class, function ($app) {
            return new OMDbAPI(config('services.omdb.url'), config('services.omdb.key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
