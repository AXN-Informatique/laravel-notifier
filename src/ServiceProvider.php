<?php

namespace Axn\LaravelNotifier;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'notifier');

        $this->publishes([
            __DIR__ . '/../resources/views/' => base_path('resources/views/vendor/notifier'),
        ]);
    }
}
