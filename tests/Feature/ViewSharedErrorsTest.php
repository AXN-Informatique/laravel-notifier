<?php

declare(strict_types=1);

use Axn\Notifier\Tests\Support\HttpTestCase;

/*
 * Reprise des erreurs de validation partagées par le middleware `web`.
 *
 * Le composant les convertit en notifications d'erreur, pour éviter à
 * l'application de les afficher elle-même. Deux garde-fous encadrent cette
 * reprise : elle ne vaut que pour la stack par défaut, et elle n'a lieu qu'une
 * fois, faute de quoi une page portant deux composants doublerait chaque
 * erreur.
 */

uses(HttpTestCase::class);

it('turns validation errors into notifications', function (): void {
    $this->from('/page')->post('/form', [])->assertRedirect('/page');

    $this->get('/page')->assertSee('The name field is required.', escape: false);
});

it('turns validation errors into notifications on a process that already rendered a page', function (): void {
    // Sous PHP-FPM le process meurt à chaque requête et ce premier appel ne
    // change rien. Sous un serveur qui survit aux requêtes, Octane comme le
    // serveur des tests navigateur, il pose l'état que la requête suivante
    // rencontre : le garde-fou anti-doublon ne doit pas s'y étendre.
    $this->get('/page')->assertOk();

    $this->from('/page')->post('/form', [])->assertRedirect('/page');

    $this->get('/page')->assertSee('The name field is required.', escape: false);
});

it('adds the shared errors only once when a page holds two components', function (): void {
    $this->withViewErrors(['name' => 'The name field is required.']);

    $this->blade('<x-notify /><x-notify />');

    // Le second composant lit la même pile : sans le garde-fou, l'erreur y
    // serait ajoutée une deuxième fois et s'afficherait en double.
    expect(notify()->nowMessages())->toHaveCount(1);
});

it('ignores the shared errors on a stack other than the default one', function (): void {
    $this->withViewErrors(['name' => 'The name field is required.']);

    $this->blade('<x-notify stack="admin" />');

    expect(notify('admin')->nowMessages())->toBeEmpty();
});

it('skips the shared errors when the attribute says so', function (): void {
    $this->withViewErrors(['name' => 'The name field is required.']);

    $rendered = (string) $this->blade('<x-notify without-view-shared-errors />');

    expect($rendered)->not->toContain('The name field is required.')
        ->and(notify()->nowMessages())->toBeEmpty();
});

it('renders nothing when no errors are shared', function (): void {
    expect(notify()->nowMessages())->toBeEmpty()
        ->and((string) $this->blade('<x-notify />'))->not->toContain('Swal.fire');
});
