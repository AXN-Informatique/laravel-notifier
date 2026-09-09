<?php

declare(strict_types=1);

use Axn\Notifier\Tests\TestCase;

/*
 * Vue `notifier::sweetalert2`.
 *
 * C'est la vue employée dans la très grande majorité des applications, et la
 * seule dont le rendu déborde de la notification : le message est injecté dans
 * une chaîne Javascript, donc un défaut d'échappement casse le script de toute
 * la page plutôt que la seule notification.
 *
 * Elle impose par ailleurs le groupement, puisque SweetAlert2 n'affiche qu'une
 * fenêtre à la fois. Le groupement déplace le titre dans le corps du message,
 * ce qui rend le paramètre `title` de la mixin inatteignable.
 */

uses(TestCase::class);

function renderSweetAlert2(): string
{
    return (string) test()->blade('<x-notify view-name="notifier::sweetalert2" />');
}

it('maps each type to its icon and colour classes', function (string $type, string $colour): void {
    notify()->{'now'.ucfirst($type)}('message');

    // SweetAlert2 nomme ses icônes comme le package nomme ses types, mais
    // Bootstrap nomme « danger » ce que le package appelle « error ».
    expect(renderSweetAlert2())
        ->toContain("icon: '".$type."'")
        ->toContain("timerProgressBar: 'bg-".$colour."'")
        ->toContain("confirmButton: 'btn btn-".$colour."'")
        ->toContain("htmlContainer: 'text-".$colour."'");
})->with([
    ['info', 'info'],
    ['success', 'success'],
    ['warning', 'warning'],
    ['error', 'danger'],
]);

it('uses the message delay as the timer', function (): void {
    notify()->nowInfo('message', null, 1234);

    expect(renderSweetAlert2())->toContain('timer: 1234');
});

it('stretches the timer of an error by the number of errors', function (): void {
    notify()->nowError('first', null, 1000);
    notify()->nowError('second', null, 1000);

    // Les deux erreurs sont groupées dans une seule fenêtre : elle doit rester
    // affichée le temps de lire les deux messages, pas celui d'un seul.
    expect(renderSweetAlert2())->toContain('timer: 2000');
});

it('counts the errors of each queue separately', function (): void {
    notify()->error('flashed first', null, 1000);
    notify()->error('flashed second', null, 1000);
    notify()->nowError('immediate', null, 3000);

    // Deux erreurs flash à 1000 donnent 2000, l'erreur instantanée seule à
    // 3000 donne 3000. Si le partial générique intervertissait les deux
    // compteurs, on lirait 1000 et 6000.
    expect(renderSweetAlert2())
        ->toContain('timer: 2000')
        ->toContain('timer: 3000');
});

it('folds the title into the html body', function (): void {
    notify()->nowInfo('message', 'Heads up');

    // Le groupement forcé vide le titre du message et le replace en tête du
    // corps : le paramètre `title` de la mixin ne sert donc jamais.
    expect(renderSweetAlert2())
        ->toContain('html: \'<ul class="list-unstyled mb-0"><li><strong>Heads up&nbsp;:&nbsp;</strong>message</li></ul>\'')
        ->not->toContain("title: '");
});

it('emits one notification per type', function (): void {
    notify()->nowInfo('an info');
    notify()->nowError('an error');

    $rendered = renderSweetAlert2();

    // Le groupement rassemble les messages d'un même type, pas les types entre
    // eux : deux types donnent deux fenêtres, affichées l'une après l'autre.
    expect(substr_count($rendered, 'Swal.mixin'))->toBe(2)
        ->and(substr_count($rendered, '.fire('))->toBe(2);
});

it('keeps the message quotes escaped inside the javascript string', function (): void {
    notify()->nowInfo('it\'s "done"');

    // Le message est posé entre apostrophes dans le script : une apostrophe
    // non convertie fermerait la chaîne et casserait toute la page.
    expect(renderSweetAlert2())
        ->toContain('html: \'<ul class="list-unstyled mb-0"><li>it&apos;s &quot;done&quot;</li></ul>\'')
        ->not->toContain("it's");
});
