<?php

declare(strict_types=1);

use Axn\Notifier\Tests\TestCase;

/*
 * Attributs du composant `<x-notify />`.
 *
 * Chacun a un équivalent en configuration : l'attribut décide pour une
 * instance, la configuration pour toute l'application. Deux vues font
 * exception et imposent leur propre mode de groupement, quoi qu'on leur
 * demande.
 */

uses(TestCase::class);

it('renders only the messages of the requested stack', function (): void {
    notify()->nowInfo('for everyone');
    notify('admin')->nowInfo('for admins');

    $rendered = (string) $this->blade('<x-notify stack="admin" />');

    expect($rendered)->toContain('for admins')
        ->not->toContain('for everyone');
});

it('renders the view named by the attribute', function (): void {
    notify()->nowInfo('message');

    expect((string) $this->blade('<x-notify view-name="notifier::pnotify-5" />'))
        ->toContain('PNotify.alert')
        ->not->toContain('Swal.mixin');
});

it('falls back to the view configured by the application', function (): void {
    config()->set('notifier.default_view', 'notifier::bootstrap-5-alert');

    notify()->nowInfo('message');

    expect((string) $this->blade('<x-notify />'))->toContain('alert alert-info');
});

it('sorts messages by type when asked to', function (): void {
    notify()->nowInfo('an info');
    notify()->nowError('an error');

    $rendered = (string) $this->blade('<x-notify view-name="notifier::bootstrap-5-alert" :sort-by-type="true" />');

    // `sort_type_order` place les erreurs en tête : elles doivent précéder
    // l'information, alors qu'elles ont été déclarées après.
    expect(strpos($rendered, 'an error'))->toBeLessThan(strpos($rendered, 'an info'));
});

it('keeps the declaration order when sorting is turned off', function (): void {
    notify()->nowInfo('an info');
    notify()->nowError('an error');

    $rendered = (string) $this->blade('<x-notify view-name="notifier::bootstrap-5-alert" :sort-by-type="false" />');

    expect(strpos($rendered, 'an info'))->toBeLessThan(strpos($rendered, 'an error'));
});

it('merges messages of one type into a single notification when grouping', function (): void {
    notify()->nowInfo('first');
    notify()->nowInfo('second');

    $rendered = (string) $this->blade('<x-notify view-name="notifier::bootstrap-5-alert" :group-by-type="true" />');

    expect(substr_count($rendered, 'alert alert-info'))->toBe(1)
        ->and($rendered)->toContain('<li>first</li>')
        ->and($rendered)->toContain('<li>second</li>');
});

it('renders one notification per message when grouping is turned off', function (): void {
    notify()->nowInfo('first');
    notify()->nowInfo('second');

    $rendered = (string) $this->blade('<x-notify view-name="notifier::bootstrap-5-alert" :group-by-type="false" />');

    expect(substr_count($rendered, 'alert alert-info'))->toBe(2);
});

it('leaves out the flashed messages when asked to', function (): void {
    notify()->success('flashed');
    notify()->nowSuccess('immediate');

    $rendered = (string) $this->blade('<x-notify :without-flash-messages="true" />');

    expect($rendered)->toContain('immediate')
        ->not->toContain('flashed');
});

it('leaves out the now messages when asked to', function (): void {
    notify()->success('flashed');
    notify()->nowSuccess('immediate');

    $rendered = (string) $this->blade('<x-notify :without-now-messages="true" />');

    expect($rendered)->toContain('flashed')
        ->not->toContain('immediate');
});

it('always groups the sweetalert2 view, even when the configuration says otherwise', function (): void {
    config()->set('notifier.group_by_type', false);

    notify()->nowInfo('first');
    notify()->nowInfo('second');

    // SweetAlert2 n'affiche qu'une fenêtre à la fois : deux appels à `fire`
    // feraient disparaître le premier message aussitôt affiché.
    expect(substr_count((string) $this->blade('<x-notify view-name="notifier::sweetalert2" />'), 'Swal.mixin'))->toBe(1);
});

it('never groups the bootstrap views, even when asked to', function (string $view): void {
    notify()->nowInfo('first');
    notify()->nowInfo('second');

    $rendered = (string) $this->blade('<x-notify view-name="'.$view.'" :group-by-type="true" />');

    // Ces deux vues cassent leur mise en page si on leur passe une liste
    // groupée : le forçage du composant prime donc sur l'attribut.
    expect($rendered)->toContain('first')
        ->and($rendered)->toContain('second')
        ->and($rendered)->not->toContain('<li>');
})->with([
    'notifier::bootstrap-4',
    'notifier::bootstrap-5',
]);
