<?php

declare(strict_types=1);

namespace Axn\Notifier\View\Components;

use Axn\Notifier\Notify;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class NotifyComponent extends Component
{
    public Collection $flashMessages;

    public Collection $nowMessages;

    public int $flashErrorsCount = 0;

    public int $nowErrorsCount = 0;

    private readonly Notify $notify;

    private readonly string $stack;

    private readonly string $viewName;

    private readonly bool $sortByType;

    private bool $groupByType;

    public function __construct(
        ?string $stack = null,
        ?string $viewName = null,
        ?bool $sortByType = null,
        ?bool $groupByType = null,
        private readonly bool $withoutFlashMessages = false,
        private readonly bool $withoutNowMessages = false,
        private readonly bool $withoutViewSharedErrors = false,
    ) {
        $this->stack = $stack ?? Notify::DEFAULT_STACK;

        $this->notify = notify($this->stack);

        $config = config('notifier');

        $this->viewName = $viewName ?? $config['default_view'];

        $this->sortByType = $sortByType ?? $config['sort_by_type'];

        $this->groupByType = $groupByType ?? $config['group_by_type'];

        $this->setParticularViewParams();

        $this->flashMessages = $this->flashMessages();

        $this->nowMessages = $this->nowMessages();
    }

    public function render(): View
    {
        return view($this->viewName);
    }

    private function setParticularViewParams(): void
    {
        // les notifications sont forcément groupée
        // car il ne peut y avoir qu'une seule instance
        if ($this->viewName === 'notifier::sweetalert2') {
            $this->groupByType = true;
        }

        // les vue 'notifier::bootstrap-5' et 'notifier::bootstrap-4'
        // ne peuvent êtres groupées par type car cela casse leur affichage
        if (\in_array($this->viewName, ['notifier::bootstrap-5', 'notifier::bootstrap-4'], true)) {
            $this->groupByType = false;
        }
    }

    /**
     * Retourne les messages flash.
     */
    private function flashMessages(): Collection
    {
        if ($this->withoutFlashMessages) {
            return collect();
        }

        [$messages, $this->flashErrorsCount] = $this->processMessages($this->notify->flashMessages());

        return $messages;
    }

    /**
     * Retourne les messages instantanés.
     */
    private function nowMessages(): Collection
    {
        $this->addErrorsSharedFromViews();

        if ($this->withoutNowMessages) {
            return collect();
        }

        [$messages, $this->nowErrorsCount] = $this->processMessages($this->notify->nowMessages());

        return $messages;
    }

    /**
     * Compte les erreurs et applique le groupement/tri sur une collection de messages.
     *
     * @return array{Collection, int}
     */
    private function processMessages(Collection $messages): array
    {
        $errorsCount = $messages
            ->filter(fn ($value): bool => $value['type'] === Notify::ERROR)
            ->count();

        $processed = $messages
            ->when($this->groupByType, fn (Collection $messages): Collection => Notify::groupMessagesByType($messages))
            ->when($this->sortByType, fn ($messages) => $messages->sortBy('type_order'));

        return [$processed, $errorsCount];
    }

    /**
     * Ajoute les erreurs partagées par les vues.
     *
     * Elles ne doivent êtres ajoutées qu'à la stack par défaut
     * et qu'une seule fois par sac d'erreurs, deux composants d'une même page
     * lisant le même sac.
     */
    private function addErrorsSharedFromViews(): void
    {
        if ($this->withoutViewSharedErrors) {
            return;
        }

        if ($this->stack !== Notify::DEFAULT_STACK) {
            return;
        }

        $errors = app('view')->shared('errors');

        if (\is_null($errors)) {
            return;
        }

        if ($this->notify->hasErrorsBeenAdded($errors)) {
            return;
        }

        foreach ($errors->all() as $error) {
            $this->notify->nowError($error);
        }

        $this->notify->markErrorsAsAdded($errors);
    }
}
