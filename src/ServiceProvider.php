<?php

namespace Axn\Notifier;

use Axn\Notifier\View\Components\NotifyComponent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/notifier.php', 'notifier');

        $this->app->bind(Notify::class, function ($app) {
            return new Notify($app['session']);
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'notifier');

        Blade::component('notify', NotifyComponent::class);

        Collection::macro('groupMessagesByType', function () {
            return Notify::groupMessagesByType($this);
        });

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
            __DIR__.'/../config/notifier.stub' => config_path('notifier.php'),
        ], 'notifier-config');

        // views
        $this->publishes([
            __DIR__.'/../resources/views/' => base_path('resources/views/vendor/notifier'),
        ], 'notifier-views');
    }
}
