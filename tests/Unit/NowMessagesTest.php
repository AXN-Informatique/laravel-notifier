<?php

declare(strict_types=1);

/*
 * Messages enregistrés pour la requête HTTP courante.
 *
 * L'API reprend celle des messages flash, préfixée de `now`. Le point qui lui
 * est propre est le compteur de clés : `Notify` en tient deux, et les
 * confondre ferait s'écraser un message instantané et un message flash portant
 * le même rang.
 */

it('stores a now message with its documented default delay', function (string $method, string $type, int $delay): void {
    notify()->{$method}('message');

    $message = notify()->nowMessages()->first();

    expect($message['type'])->toBe($type)
        ->and($message['delay'])->toBe($delay);
})->with([
    ['nowInfo', 'info', 8000],
    ['nowSuccess', 'success', 3000],
    ['nowWarning', 'warning', 8000],
    ['nowError', 'error', 8000],
]);

it('keeps an explicit delay over the default one', function (): void {
    notify()->nowSuccess('message', null, 500);

    expect(notify()->nowMessages()->first()['delay'])->toBe(500);
});

it('numbers messages so several now messages coexist', function (): void {
    notify()->nowInfo('first');
    notify()->nowError('second');

    expect(notify()->nowMessages()->pluck('message')->all())->toBe(['first', 'second']);
});

it('numbers now messages independently from flash messages', function (): void {
    notify()->info('flashed');
    notify()->nowInfo('immediate');

    // Chacun garde le rang 1 dans sa propre pile. Avec un compteur commun, le
    // message instantané serait rangé sous la clé 2 de la sienne.
    expect(session()->get('notify_flash_default'))->toHaveKeys([1])
        ->and(session()->get('notify_now_default'))->toHaveKeys([1]);
});

it('returns the notifier so now calls can be chained', function (): void {
    notify()->nowInfo('first')->nowError('second');

    expect(notify()->nowMessages())->toHaveCount(2);
});
