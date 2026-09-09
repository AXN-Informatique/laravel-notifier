<?php

declare(strict_types=1);

namespace Axn\Notifier\Tests\Support;

use Axn\Notifier\Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Application servant de vraies requêtes HTTP.
 *
 * La promesse du package tient à un cycle que seule une requête peut
 * reproduire : un message posé ici doit apparaître à la requête suivante, puis
 * disparaître. Le rendu du composant en dehors de ce cycle se teste plus
 * simplement avec `$this->blade()`, sans passer par ce cas de base.
 *
 * Le middleware `web` est nécessaire à plus d'un titre : il démarre la session
 * et c'est lui qui partage les erreurs de validation avec les vues.
 */
abstract class HttpTestCase extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['view']->addNamespace('harness', __DIR__.'/../views');
    }

    protected function defineRoutes($router): void
    {
        $router->middleware('web')->group(function ($router): void {
            $router->get('/page', fn () => view('harness::page'))->name('page');

            $router->get('/flash-then-redirect', function () {
                notify()->success('Flashed message');

                return redirect()->route('page');
            });

            $router->get('/now', function () {
                notify()->nowSuccess('Immediate message');

                return view('harness::page');
            });

            $router->post('/form', function (Request $request) {
                $request->validate(['name' => ['required']]);

                return 'accepted';
            });
        });
    }
}
