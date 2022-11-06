<?php

namespace App\Providers;

use App\Services\PentekTimingProvider;
use App\Services\RaceResultsProvider;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RaceResultsProvider::class, function ($app, $params) {
            if ($params['provider'] == 'pentek') {
                return new PentekTimingProvider();
            } else {
                throw new InvalidArgumentException('Race results provider ' . $params['provider'] . ' not available');
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
