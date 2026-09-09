<?php

declare(strict_types=1);

use Axn\Notifier\Notify;

/*
 * Fusion des messages d'un même type en une notification unique.
 *
 * C'est le mode obligatoire de SweetAlert2, qui ne peut afficher qu'une
 * fenêtre à la fois. Le résultat est du HTML assemblé à partir de trois
 * formats de configuration, et il finit injecté dans une chaîne Javascript :
 * la forme exacte compte donc.
 */

it('returns the types in a fixed order, whatever the input order', function (): void {
    notify()->nowError('an error');
    notify()->nowInfo('an info');
    notify()->nowWarning('a warning');
    notify()->nowSuccess('a success');

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped->pluck('type')->all())->toBe(['info', 'success', 'warning', 'error']);
});

it('leaves out the types that carry no message', function (): void {
    notify()->nowInfo('an info');

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped->keys()->all())->toBe(['info']);
});

it('wraps even a single message in the configured markup', function (): void {
    notify()->nowInfo('alone');

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped['info']['message'])->toBe('<ul class="list-unstyled mb-0"><li>alone</li></ul>');
});

it('joins the messages of one type and inlines their titles', function (): void {
    notify()->nowInfo('first');
    notify()->nowInfo('second', 'Heads up');

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped['info']['message'])->toBe(
        '<ul class="list-unstyled mb-0">'
        .'<li>first</li>'
        .'<li><strong>Heads up&nbsp;:&nbsp;</strong>second</li>'
        .'</ul>'
    );
});

it('assembles the group with the three configured formats', function (): void {
    config()->set('notifier.group_messages_format', '<div>%s</div>');
    config()->set('notifier.group_message_format', '[%s%s]');
    config()->set('notifier.group_title_format', '(%s) ');

    notify()->nowInfo('one', 'Title');

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped['info']['message'])->toBe('<div>[(Title) one]</div>');
});

it('keeps the id of the first message of the group', function (): void {
    notify()->nowInfo('first');
    notify()->nowInfo('second');

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped['info']['id'])->toBe('notify-now-default1');
});

it('keeps the delay of the last message of the group', function (): void {
    notify()->nowInfo('first', null, 1000);
    notify()->nowInfo('second', null, 5000);

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped['info']['delay'])->toBe(5000);
});

it('clears the title of the group, the individual ones moving into the body', function (): void {
    notify()->nowInfo('first', 'Heads up');

    $grouped = Notify::groupMessagesByType(notify()->nowMessages());

    expect($grouped['info']['title'])->toBeNull();
});

it('drops messages whose type is none of the four known ones', function (): void {
    $messages = collect([
        1 => [
            'id' => 'notify-now-default1',
            'type' => 'debug',
            'message' => 'lost',
            'title' => null,
            'delay' => 0,
            'type_order' => 0,
        ],
    ]);

    // Perte silencieuse : rien ne signale au développeur que son message a
    // disparu du groupement.
    expect(Notify::groupMessagesByType($messages))->toBeEmpty();
});
