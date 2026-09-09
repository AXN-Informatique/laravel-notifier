<?php

declare(strict_types=1);

/*
 * Messages enregistrés pour la requête HTTP suivante.
 *
 * Les délais par défaut font partie du contrat public du package : ils sont
 * documentés, et un utilisateur qui n'en passe pas compte dessus. Leur survie
 * effective à la redirection est vérifiée par le test de cycle de vie, qui
 * seul dispose de vraies requêtes.
 */

it('stores a flash message with its documented default delay', function (string $method, string $type, int $delay): void {
    notify()->{$method}('message');

    $message = notify()->flashMessages()->first();

    expect($message['type'])->toBe($type)
        ->and($message['delay'])->toBe($delay);
})->with([
    ['info', 'info', 8000],
    ['success', 'success', 3000],
    ['warning', 'warning', 8000],
    ['error', 'error', 8000],
]);

it('keeps an explicit delay over the default one', function (): void {
    notify()->success('message', null, 500);

    expect(notify()->flashMessages()->first()['delay'])->toBe(500);
});

it('stores the given title', function (): void {
    notify()->info('message', 'A title');

    expect(notify()->flashMessages()->first()['title'])->toBe('A title');
});

it('numbers messages so several flashes coexist', function (): void {
    notify()->info('first');
    notify()->error('second');

    expect(notify()->flashMessages()->pluck('message')->all())->toBe(['first', 'second']);
});

it('returns the notifier so flash calls can be chained', function (): void {
    notify()->info('first')->error('second');

    expect(notify()->flashMessages())->toHaveCount(2);
});
