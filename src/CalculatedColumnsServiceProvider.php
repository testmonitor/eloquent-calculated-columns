<?php

namespace TestMonitor\CalculatedColumns;

use Illuminate\Support\ServiceProvider;
use TestMonitor\CalculatedColumns\Requests\CalculatedColumnsRequest;

class CalculatedColumnsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/calculated-columns.php' => config_path('calculated-columns.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/calculated-columns.php', 'calculated-columns');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(CalculatedColumnsRequest::class, function ($app) {
            return CalculatedColumnsRequest::fromRequest($app['request']);
        });
    }
}
