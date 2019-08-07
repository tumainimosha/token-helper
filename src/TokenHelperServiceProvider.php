<?php

namespace Tumainimosha\TokenHelper;

use Illuminate\Support\ServiceProvider;

class TokenHelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/TokenHelper.php', 'token-helper');
        $this->publishThings();
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'TokenHelper');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register facade
        $this->app->singleton('token-helper', function () {
            return new TokenHelper;
        });
    }

    public function publishThings()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/TokenHelper.php' => config_path('TokenHelper.php'),
            ], 'config');
        }
    }
}
