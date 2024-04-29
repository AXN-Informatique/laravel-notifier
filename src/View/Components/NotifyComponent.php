<?php

namespace Axn\Notifier\View\Components;

use Axn\Notifier\Notify;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class NotifyComponent extends Component
{
    public Collection $flashMessages;

    public Collection $nowMessages;

    public int $flashErrorsCount;

    public int $nowErrorsCount;

    private Notify $notify;

    private string $stack;

    private string $viewName;

    private bool $sortByType;

    private bool $groupByType;

    private bool $withoutFlashMessages;

    private bool $withoutNowMessages;

    private bool $withoutViewSharedErrors;

    public function __construct(
        ?string $stack = null,
        ?string $viewName = null,
        bool $sortByType = true,
        bool $groupByType = false,
        bool $withoutFlashMessages = false,
        bool $withoutNowMessages = false,
        bool $withoutViewSharedErrors = false,
    ) {
        $this->stack = $stack ?? Notify::DEFAULT_STACK;

        $this->notify = notify($this->stack);

        $config = config('notifier');

        $this->viewName = $viewName ?? $config['default_view'];

        $this->sortByType = $sortByType ?? $config['sort_by_type'];

        $this->groupByType = $groupByType ?? $config['group_by_type'];

        $this->withoutFlashMessages = $withoutFlashMessages;

        $this->withoutNowMessages = $withoutNowMessages;

        $this->withoutViewSharedErrors = $withoutViewSharedErrors;

        $this->setParticularViewParams();

        $this->flashMessages = $this->flashMessages();

        $this->nowMessages = $this->nowMessages();
    }

    public function render()
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
        if (\in_array($this->viewName, ['notifier::bootstrap-5', 'notifier::bootstrap-4'])) {
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

        $this->flashErrorsCount = $this->notify->flashMessages($this->stack)
            ->filter(function ($value) {
                return $value['type'] === Notify::ERROR;
            })
            ->count();

        return $this->notify->flashMessages($this->stack)
            ->when($this->groupByType, function ($messages) {
                return $messages->groupMessagesByType();
            })
            ->when($this->sortByType, function ($messages) {
                return $messages->sortBy('type_order');
            });
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

        $this->nowErrorsCount = $this->notify->nowMessages($this->stack)
            ->filter(function ($value) {
                return $value['type'] === Notify::ERROR;
            })
            ->count();

        return $this->notify->nowMessages($this->stack)
            ->when($this->groupByType, function ($messages) {
                return $messages->groupMessagesByType();
            })
            ->when($this->sortByType, function ($messages) {
                return $messages->sortBy('type_order');
            });
    }

    /**
     * Ajoute les erreurs partagées par les vues.
     *
     * Elles ne doivent êtres ajoutées qu'à la stack par défaut
     * et qu'une seule fois.
     */
    private function addErrorsSharedFromViews(): void
    {
        static $errorsAlreadyAdded = false;

        if ($this->withoutViewSharedErrors) {
            return;
        }

        if ($this->stack !== Notify::DEFAULT_STACK) {
            return;
        }

        if ($errorsAlreadyAdded === true) {
            return;
        }

        $errors = app('view')->shared('errors');

        if (\is_null($errors)) {
            return;
        }

        foreach ($errors->all() as $error) {
            $this->notify->nowError($error);
        }

        $errorsAlreadyAdded = true;
    }
}
