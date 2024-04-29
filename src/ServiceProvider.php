<?php

declare(strict_types=1);

namespace Axn\Notifier;

use Axn\Notifier\View\Components\NotifyComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    private string $basePath = '';

    public function register(): void
    {
        $this->basePath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

        $this->mergeConfigFrom($this->basePath.'config/notifier.php', 'notifier');

        $this->app->bind(Notify::class, fn ($app): Notify => new Notify($app['session']));
    }

    public function boot(): void
    {
        $this->loadViewsFrom($this->basePath.'resources/views/', 'notifier');

        Blade::component('notify', NotifyComponent::class);

        if ($this->app->runningInConsole()) {
            $this->configurePublishing();
        }
    }

    /**
     * Configure the publishable resources offered by the package.
     */
    private function configurePublishing(): void
    {
        // config
        $this->publishes([
            $this->basePath.'config/notifier.stub' => $this->app->configPath('notifier.php'),
        ], 'notifier-config');

        // views
        $this->publishes([
            $this->basePath.'resources/views/' => $this->app->resourcePath('views/vendor/notifier'),
        ], 'notifier-views');
    }
}
