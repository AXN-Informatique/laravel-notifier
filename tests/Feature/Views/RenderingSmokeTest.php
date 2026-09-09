<?php

declare(strict_types=1);

use Axn\Notifier\Tests\TestCase;

/*
 * Invariants communs aux onze vues prédéfinies.
 *
 * Neuf d'entre elles incluent `partials/a-generic-component`, qui parcourt les
 * messages flash puis les messages instantanés et passe à chaque partial les
 * mêmes paramètres ; les deux vues toast répètent ces boucles à l'intérieur de
 * leur propre conteneur. Ce fichier vérifie ce que toutes garantissent malgré
 * cette divergence : le message sort, le titre sort, l'ordre des deux files est
 * respecté, rien n'est émis en l'absence de message, et les entités posées par
 * le notifieur arrivent intactes.
 */

uses(TestCase::class);

dataset('views', [
    'notifier::bootstrap-4',
    'notifier::bootstrap-4-alert',
    'notifier::bootstrap-4-alert-advanced',
    'notifier::bootstrap-4-toast',
    'notifier::bootstrap-5',
    'notifier::bootstrap-5-alert',
    'notifier::bootstrap-5-alert-advanced',
    'notifier::bootstrap-5-toast',
    'notifier::pnotify-3',
    'notifier::pnotify-5',
    'notifier::sweetalert2',
]);

dataset('types', [
    'info',
    'success',
    'warning',
    'error',
]);

function renderView(string $view): string
{
    return (string) test()->blade('<x-notify view-name="'.$view.'" />');
}

it('renders a message of any type in every view', function (string $view, string $type): void {
    notify()->{'now'.ucfirst($type)}('the message body');

    // Les partials branchent sur le type, souvent dans un `@switch` sans cas
    // par défaut : un type non traité produirait une variable indéfinie.
    expect(renderView($view))->toContain('the message body');
})->with('views')->with('types');

it('renders the title in every view', function (string $view): void {
    notify()->nowInfo('the message body', 'Heads up');

    // Chaque vue place le titre à sa façon, mais aucune n'a le droit de le
    // perdre en route.
    expect(renderView($view))->toContain('Heads up');
})->with('views');

it('renders the flashed messages before the now ones in every view', function (string $view): void {
    notify()->info('flashed');
    notify()->nowInfo('immediate');

    $rendered = renderView($view);

    // La présence des deux messages est vérifiée d'abord : `strpos` retourne
    // `false` pour un message absent, et `false` est inférieur à n'importe
    // quelle position.
    expect($rendered)->toContain('flashed')
        ->toContain('immediate')
        ->and(strpos($rendered, 'flashed'))->toBeLessThan(strpos($rendered, 'immediate'));
})->with('views');

it('emits no notification markup when there is no message', function (string $view): void {
    // Sans message, une vue n'a le droit d'émettre que ses conteneurs. Un
    // `<script>` ou les symboles SVG des vues avancées, s'ils étaient posés en
    // dehors de la boucle, se retrouveraient sur toutes les pages de
    // l'application, y compris celles qui ne notifient rien.
    expect(renderView($view))->not->toMatch('/<\/?(?!div\b)[a-z]/i');
})->with('views');

it('leaves the quote entities untouched in every view', function (string $view): void {
    notify()->nowInfo('it\'s "done"');

    // Le notifieur a déjà converti les guillemets en entités. Une vue qui
    // afficherait le message avec `{{ }}` au lieu de `{!! !!}` échapperait
    // l'esperluette et l'utilisateur lirait `it&apos;s`.
    expect(renderView($view))->toContain('it&apos;s &quot;done&quot;');
})->with('views');
