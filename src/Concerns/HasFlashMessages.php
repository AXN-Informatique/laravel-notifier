<?php

namespace Axn\Notifier\Concerns;

use Axn\Notifier\Notify;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasFlashMessages
{
    /**
     * Enregistre un message de type information pour la prochaine requête HTTP.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function info(string $message, ?string $title = null): Notify
    {
        return $this->flash(Notify::INFO, $message, $title);
    }

    /**
     * Enregistre un message de type succès pour la prochaine requête HTTP.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function success(string $message, ?string $title = null): Notify
    {
        return $this->flash(Notify::SUCCESS, $message, $title);
    }

    /**
     * Enregistre un message de type avertissement pour la prochaine requête HTTP.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function warning(string $message, ?string $title = null): Notify
    {
        return $this->flash(Notify::WARNING, $message, $title);
    }

    /**
     * Enregistre un message de type erreur pour la prochaine requête HTTP.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function error(string $message, ?string $title = null): Notify
    {
        return $this->flash(Notify::ERROR, $message, $title);
    }

    /**
     * Retourne les messages pour la prochaine requête HTTP.
     *
     * @return Collection
     */
    public function flashMessages(): Collection
    {
        return $this->stackMessages("notify_flash_$this->stack");
    }

    /**
     * Enregistre un message pour la prochaine requête HTTP.
     *
     * @param string $type
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    private function flash(string $type, string $message, ?string $title = null): Notify
    {
        static $count = 1;

        $id = "notify_flash_$this->stack.".$count++;

        $this->session->flash($id, [
            'id' => Str::slug($id),
            'type' => $type,
            'message' => $message,
            'title' => $title,
            'type_order' => $this->typeOrderKey($type),
        ]);

        return $this;
    }
}
