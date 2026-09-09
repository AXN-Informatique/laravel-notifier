<?php

declare(strict_types=1);

use Axn\Notifier\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Cas de test
|--------------------------------------------------------------------------
|
| La suite `Unit` s'appuie sur le cas de base Testbench : il amorce
| l'application, enregistre le ServiceProvider du package et force la session
| sur le driver `array`. Même les tests dits unitaires en ont besoin : `Notify`
| reçoit le gestionnaire de session par injection et lit sa configuration dans
| le conteneur.
|
| La suite `Feature` n'est volontairement pas liée ici : les tests de cycle de
| vie réclament une application qui sert de vraies requêtes, avec ses routes et
| son middleware. Chaque fichier y déclare donc le cas dont il a besoin par
| `uses()`, Pest interdisant de lier deux fois le même dossier.
|
*/

pest()->extend(TestCase::class)->in('Unit');
