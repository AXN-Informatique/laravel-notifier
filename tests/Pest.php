<?php

declare(strict_types=1);

use Axn\Notifier\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Cas de test
|--------------------------------------------------------------------------
|
| Les deux suites s'appuient sur le même cas de base Testbench : il amorce
| l'application, enregistre le ServiceProvider du package et force la session
| sur le driver `array`. Même les tests dits unitaires en ont besoin : `Notify`
| reçoit le gestionnaire de session par injection et lit sa configuration dans
| le conteneur.
|
*/

pest()->extend(TestCase::class)->in('Unit', 'Feature');
