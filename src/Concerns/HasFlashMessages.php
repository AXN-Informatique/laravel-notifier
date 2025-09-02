<?php

declare(strict_types=1);

namespace Axn\Notifier\Concerns;

use Axn\Notifier\Notify;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasFlashMessages
{
    /**
     * Enregistre un message de type information pour la prochaine requête HTTP.
     */
    public function info(string $message, ?string $title = null, ?int $delay = 8000): Notify
    {
        return $this->flash(Notify::INFO, $message, $title, $delay);
    }

    /**
     * Enregistre un message de type succès pour la prochaine requête HTTP.
     */
    public function success(string $message, ?string $title = null, ?int $delay = 3000): Notify
    {
        return $this->flash(Notify::SUCCESS, $message, $title, $delay);
    }

    /**
     * Enregistre un message de type avertissement pour la prochaine requête HTTP.
     */
    public function warning(string $message, ?string $title = null, ?int $delay = 8000): Notify
    {
        return $this->flash(Notify::WARNING, $message, $title, $delay);
    }

    /**
     * Enregistre un message de type erreur pour la prochaine requête HTTP.
     */
    public function error(string $message, ?string $title = null, ?int $delay = 8000): Notify
    {
        return $this->flash(Notify::ERROR, $message, $title, $delay);
    }

    /**
     * Retourne les messages pour la prochaine requête HTTP.
     */
    public function flashMessages(): Collection
    {
        return $this->stackMessages('notify_flash_'.$this->stack);
    }

    /**
     * Enregistre un message pour la prochaine requête HTTP.
     */
    private function flash(string $type, string $message, ?string $title, ?int $delay): Notify
    {
        static $count = 1;

        $id = 'notify_flash_'.$this->stack.'.'.$count++;

        $this->session->flash($id, [
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
