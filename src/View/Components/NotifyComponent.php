<?php

namespace Axn\Notifier\View\Components;

use Axn\Notifier\Notify;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class NotifyComponent extends Component
{
    public Collection $flashMessages;

    public Collection $nowMessages;

    public int $errorsCount;

    private Notify $notify;

    private ?string $stack;

    private string $viewName;

    private bool $sortByType;

    private bool $groupByType;

    private bool $withoutFlashMessages;

    private bool $withoutNowMessages;

    public function __construct(
        Notify $notify,
        ?string $stack = null,
        ?string $viewName = null,
        ?bool $sortByType = true,
        ?bool $groupByType = false,
        ?bool $withoutFlashMessages = false,
        ?bool $withoutNowMessages = false,
    ) {
        $this->notify = $notify;

        $this->stack = $stack ?? Notify::DEFAULT_STACK;

        $config = config('notifier');

        $this->viewName = $viewName ?? $config['default_view'];

        $this->sortByType = $sortByType ?? $config['sort_by_type'];

        $this->groupByType = $groupByType ?? $config['group_by_type'];

        $this->withoutFlashMessages = $withoutFlashMessages;

        $this->withoutNowMessages = $withoutNowMessages;

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

        if (in_array($this->viewName, ['notifier::bootstrap-5', 'notifier::bootstrap-4'])) {
            $this->groupByType = false;
        }
    }

    /**
     * Retourne les messages flash.
     *
     * @return Collection
     */
    private function flashMessages(): Collection
    {
        if ($this->withoutFlashMessages) {
            return collect();
        }

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
     *
     * @return Collection
     */
    private function nowMessages(): Collection
    {
        $this->addErrorsToNowMessage();

        if ($this->withoutNowMessages) {
            return collect();
        }

        return $this->notify->nowMessages($this->stack)
            ->when($this->groupByType, function ($messages) {
                return $messages->groupMessagesByType();
            })
            ->when($this->sortByType, function ($messages) {
                return $messages->sortBy('type_order');
            });
    }

    /**
     * Ajoute les erreurs aux messages instantanés.
     *
     * @return void
     */
    private function addErrorsToNowMessage(): void
    {
        $errors = app('view')->shared('errors');

        $this->errorsCount = $errors->count();

        if ($errors->any()) {
            foreach ($errors->all() as $error) {
                $this->notify->nowError($error);
            }
        }
    }
}
