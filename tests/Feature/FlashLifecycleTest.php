<?php

declare(strict_types=1);

use Axn\Notifier\Tests\Support\HttpTestCase;

/*
 * Cycle de vie des deux familles de messages, en conditions réelles.
 *
 * C'est la promesse centrale du package : un message posé avant une
 * redirection survit à celle-ci, s'affiche une fois, et disparaît. Aucun test
 * hors requête HTTP ne peut le prouver, la session n'étant vieillie que par le
 * middleware.
 */

uses(HttpTestCase::class);

it('renders a flashed message on the request that follows the redirect', function (): void {
    $this->get('/flash-then-redirect')->assertRedirect(route('page'));

    $this->get('/page')->assertOk()->assertSee('Flashed message');
});

it('drops a flashed message after it has been rendered once', function (): void {
    $this->get('/flash-then-redirect');
    $this->get('/page')->assertSee('Flashed message');

    $this->get('/page')->assertDontSee('Flashed message');
});

it('renders a now message within the request that posted it', function (): void {
    $this->get('/now')->assertOk()->assertSee('Immediate message');
});

it('does not carry a now message over to the next request', function (): void {
    $this->get('/now')->assertSee('Immediate message');

    $this->get('/page')->assertDontSee('Immediate message');
});
