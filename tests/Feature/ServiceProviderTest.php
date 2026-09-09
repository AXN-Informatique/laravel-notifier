<?php

declare(strict_types=1);

use Axn\Notifier\ServiceProvider;
use Axn\Notifier\Tests\TestCase;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/*
 * Ce que le package installe dans l'application hôte.
 *
 * Quatre engagements : un notifieur partagé, un namespace de vues, un
 * composant Blade, et deux jeux de fichiers publiables.
 */

uses(TestCase::class);

it('exposes the package views under the notifier namespace', function (string $view): void {
    expect(view()->exists($view))->toBeTrue();
})->with([
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

it('registers the notify blade component', function (): void {
    notify()->nowInfo('registered');

    expect((string) $this->blade('<x-notify />'))->toContain('registered');
});

it('merges the package configuration into the application', function (): void {
    expect(config('notifier'))->toHaveKeys([
        'default_view',
        'sort_by_type',
        'sort_type_order',
        'group_by_type',
        'group_messages_format',
        'group_title_format',
        'group_message_format',
    ]);
});

it('lets the application override a single configuration key', function (): void {
    config()->set('notifier.group_by_type', true);

    expect(config('notifier.group_by_type'))->toBeTrue()
        ->and(config('notifier.sort_by_type'))->toBeTrue();
});

it('publishes the configuration stub rather than the full config file', function (): void {
    $paths = BaseServiceProvider::pathsToPublish(ServiceProvider::class, 'notifier-config');

    // Le stub est un tableau vide commenté : l'application n'y déclare que ce
    // qu'elle surcharge. Publier `config/notifier.php` y recopierait au
    // contraire toute la configuration du package, qui cesserait alors de
    // suivre ses mises à jour.
    expect(array_keys($paths))->toHaveCount(1)
        ->and(array_key_first($paths))->toEndWith('config/notifier.stub')
        ->and(reset($paths))->toBe(config_path('notifier.php'));
});

it('publishes the package views into the vendor views directory', function (): void {
    $paths = BaseServiceProvider::pathsToPublish(ServiceProvider::class, 'notifier-views');

    expect(array_keys($paths))->toHaveCount(1)
        ->and(array_key_first($paths))->toEndWith('resources/views/')
        ->and(reset($paths))->toBe(resource_path('views/vendor/notifier'));
});
