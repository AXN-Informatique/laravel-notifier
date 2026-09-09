<?php

declare(strict_types=1);

/*
 * Forme des données que `Notify` range en session pour chaque message.
 *
 * Ce tableau est ce que les vues reçoivent : chacune de ses cinq clés porte une
 * décision qui se voit à l'affichage, de l'identifiant HTML des toasts au rang
 * de tri par type.
 */

it('slugifies the message id so a view can use it as an html id', function (): void {
    notify()->nowInfo('message');

    // Le point du rang disparaît à la slugification : les toasts Bootstrap
    // reprennent cet identifiant dans un getElementById.
    expect(notify()->nowMessages()->first()['id'])->toBe('notify-now-default1');
});

it('ranks messages according to the configured type order', function (): void {
    config()->set('notifier.sort_type_order', ['info', 'success', 'warning', 'error']);

    notify()->nowInfo('first shown');
    notify()->nowError('last shown');

    expect(notify()->nowMessages()->pluck('type_order')->all())->toBe([0, 3]);
});

it('ranks a type missing from the configuration as high as an error', function (): void {
    config()->set('notifier.sort_type_order', ['error', 'warning']);

    notify()->nowInfo('unranked');
    notify()->nowError('ranked first');

    // Piège de configuration : retirer un type de la liste ne le relègue pas en
    // fin d'affichage, il remonte au rang des erreurs.
    expect(notify()->nowMessages()->pluck('type_order')->all())->toBe([0, 0]);
});

it('normalises a missing title to null', function (): void {
    notify()->nowInfo('message');

    expect(notify()->nowMessages()->first()['title'])->toBeNull();
});

it('normalises an empty title to null', function (): void {
    notify()->nowInfo('message', '');

    expect(notify()->nowMessages()->first()['title'])->toBeNull();
});

it('keeps the title "0", which a loose emptiness check would drop', function (): void {
    notify()->nowInfo('message', '0');

    expect(notify()->nowMessages()->first()['title'])->toBe('0');
});

it('escapes quotes so a message survives inside a javascript string', function (): void {
    notify()->nowInfo("it's \"done\"", "the user's title");

    $message = notify()->nowMessages()->first();

    expect($message['message'])->toBe('it&apos;s &quot;done&quot;')
        ->and($message['title'])->toBe('the user&apos;s title');
});

it('leaves html markup untouched, unlike e()', function (): void {
    notify()->nowInfo('<strong>bold</strong> & more');

    // Les messages sont rendus avec {!! !!} : le package accepte le HTML
    // volontaire et n'échappe que ce qui casserait le Javascript.
    expect(notify()->nowMessages()->first()['message'])->toBe('<strong>bold</strong> & more');
});

it('turns an explicitly null delay into zero', function (): void {
    notify()->nowInfo('message', null, null);

    expect(notify()->nowMessages()->first()['delay'])->toBe(0);
});
