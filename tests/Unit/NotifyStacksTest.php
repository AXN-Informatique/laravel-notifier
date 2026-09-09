<?php

declare(strict_types=1);

/*
 * Les stacks sont des piles de messages indépendantes.
 *
 * Elles existent pour qu'une page puisse afficher deux zones de notifications
 * sans que l'une vide l'autre : l'isolation est donc le comportement à
 * protéger, et elle repose entièrement sur le nom de la clé de session.
 */

it('keeps the messages of two stacks apart', function (): void {
    notify()->nowInfo('for everyone');
    notify('admin')->nowInfo('for admins');

    expect(notify('admin')->nowMessages()->pluck('message')->all())->toBe(['for admins'])
        ->and(notify()->nowMessages()->pluck('message')->all())->toBe(['for everyone']);
});

it('falls back to the default stack when stack() receives no name', function (): void {
    notify('admin')->stack()->nowInfo('back to default');

    expect(session()->get('notify_now_default'))->toHaveCount(1)
        ->and(session()->has('notify_now_admin'))->toBeFalse();
});

it('returns an empty collection for a stack that holds nothing', function (): void {
    expect(notify('unused')->nowMessages())->toBeEmpty()
        ->and(notify('unused')->flashMessages())->toBeEmpty();
});

it('keeps the flash and now piles of one stack apart', function (): void {
    notify()->info('next request');
    notify()->nowInfo('this request');

    expect(notify()->flashMessages()->pluck('message')->all())->toBe(['next request'])
        ->and(notify()->nowMessages()->pluck('message')->all())->toBe(['this request']);
});
