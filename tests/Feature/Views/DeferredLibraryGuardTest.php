<?php

declare(strict_types=1);

use Axn\Notifier\Tests\TestCase;

/*
 * Garde de chargement différé des vues à script.
 *
 * Une application peut charger la bibliothèque d'affichage (Swal, bootstrap,
 * jQuery, PNotify) par un module, exécuté après la lecture de la page, alors
 * que le script de la vue s'exécute immédiatement et lèverait une erreur. La
 * vue attend donc `DOMContentLoaded`, mais seulement si la bibliothèque est
 * absente et la page encore en lecture : une bibliothèque déjà chargée sert
 * tout de suite, comme avant.
 */

uses(TestCase::class);

it('waits for its library only while the page is still loading', function (string $view, string $library): void {
    notify()->nowInfo('message');

    expect((string) $this->blade('<x-notify view-name="'.$view.'" />'))
        ->toContain("typeof {$library} === 'undefined' && document.readyState === 'loading'")
        ->toContain("document.addEventListener('DOMContentLoaded', show)");
})->with([
    'sweetalert2' => ['notifier::sweetalert2', 'Swal'],
    'bootstrap 5 toast' => ['notifier::bootstrap-5-toast', 'bootstrap'],
    'bootstrap 4 toast' => ['notifier::bootstrap-4-toast', '$'],
    'pnotify 5' => ['notifier::pnotify-5', 'PNotify'],
    'pnotify 3' => ['notifier::pnotify-3', 'jQuery'],
]);
