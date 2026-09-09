<?php

declare(strict_types=1);

use Axn\Notifier\Notify;

/*
 * Le helper `notify()` est le seul point d'entrée documenté du package.
 *
 * Il résout une instance partagée et lui applique une stack à chaque appel :
 * les deux comportements se vérifient sur l'état que l'instance accumule, et
 * non sur son identité, qui ne dirait rien de ce que l'utilisateur observe.
 */

it('resolves a shared instance whose message counter keeps incrementing', function (): void {
    notify()->nowInfo('first');
    notify()->nowInfo('second');

    // Deux clés distinctes : le compteur a survécu entre les deux appels.
    // Avec une liaison non partagée, le second message repartirait de 1 et
    // écraserait le premier.
    expect(session()->get('notify_now_default'))->toHaveKeys([1, 2]);
});

it('falls back to the default stack when none is given', function (): void {
    notify()->nowInfo('message');

    expect(session()->get('notify_now_default'))->toHaveCount(1);
});

it('applies the requested stack to the shared instance', function (): void {
    notify('admin')->nowInfo('for admins');
    notify()->nowInfo('for everyone');

    expect(session()->get('notify_now_admin'))->toHaveCount(1)
        ->and(session()->get('notify_now_default'))->toHaveCount(1);
});

it('returns the notifier so calls can be chained', function (): void {
    expect(notify())->toBeInstanceOf(Notify::class)
        ->and(notify()->nowInfo('message'))->toBeInstanceOf(Notify::class);
});
