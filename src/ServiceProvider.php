<?php

namespace Axn\LaravelNotifier;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            Contract::class,
            Notifier::class
        );

        $this->mergeConfigFrom(__DIR__.'/../config/notifier.php', 'notifier');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'notifier');

        if ($this->app->runningInConsole()) {
            $this->configurePublishing();
        }
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    private function configurePublishing()
    {
        // config
        $this->publishes([
            __DIR__.'/../config/notifiers.php' => config_path('notifier.php'),
        ], 'config');

        // views
        $this->publishes([
            __DIR__ . '/../resources/views/' => base_path('resources/views/vendor/notifier'),
        ]);
    }
}
