<?php

declare(strict_types=1);

namespace Axn\Notifier\Concerns;

use Axn\Notifier\Notify;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasNowMessages
{
    /**
     * Enregistre un message de type information pour la requête HTTP courante.
     */
    public function nowInfo(string $message, ?string $title = null, ?int $delay = 8000): Notify
    {
        return $this->now(Notify::INFO, $message, $title, $delay);
    }

    /**
     * Enregistre un message de type succès pour la requête HTTP courante.
     */
    public function nowSuccess(string $message, ?string $title = null, ?int $delay = 3000): Notify
    {
        return $this->now(Notify::SUCCESS, $message, $title, $delay);
    }

    /**
     * Enregistre un message de type avertissement pour la requête HTTP courante.
     */
    public function nowWarning(string $message, ?string $title = null, ?int $delay = 8000): Notify
    {
        return $this->now(Notify::WARNING, $message, $title, $delay);
    }

    /**
     * Enregistre un message de type erreur pour la requête HTTP courante.
     */
    public function nowError(string $message, ?string $title = null, ?int $delay = 8000): Notify
    {
        return $this->now(Notify::ERROR, $message, $title, $delay);
    }

    /**
     * Retourne les messages pour la requête HTTP courante.
     */
    public function nowMessages(): Collection
    {
        return $this->stackMessages('notify_now_'.$this->stack);
    }

    /**
     * Enregistre un message pour la requête HTTP courante.
     */
    private function now(string $type, string $message, ?string $title, ?int $delay): Notify
    {
        static $count = 1;

        $id = 'notify_now_'.$this->stack.'.'.$count++;

        $this->session->now($id, [
            'id' => Str::slug($id),
            'type' => $type,
            'message' => $this->escapeString($message),
            'title' => $title !== null && $title !== '' && $title !== '0' ? $this->escapeString($title) : null,
            'delay' => $delay ?? 0,
            'type_order' => $this->typeOrderKey($type),
        ]);

        return $this;
    }
}
