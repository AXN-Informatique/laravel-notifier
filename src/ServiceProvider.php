<?php

namespace Axn\LaravelNotifier;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind(
            Contract::class,
            function ($app) {
                return new Notifier($app['session.store']);
            }
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'notifier');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views/' => base_path('resources/views/vendor/notifier'),
            ]);
        }
    }
}
