<?php

declare(strict_types=1);

namespace Axn\Notifier\Tests;

use Axn\Notifier\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        // La session est le seul support de stockage du package : les messages
        // flash comme les messages instantanés y transitent. Le driver `array`
        // la garde en mémoire, donc vidée d'un test à l'autre sans toucher au
        // système de fichiers.
        $app['config']->set('session.driver', 'array');
    }
}
