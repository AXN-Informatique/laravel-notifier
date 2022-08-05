<?php

namespace Axn\Notifier\Concerns;

use Axn\Notifier\Notify;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasNowMessages
{
    /**
     * Enregistre un message de type information pour la requête HTTP courante.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function nowInfo(string $message, ?string $title = null): Notify
    {
        return $this->now(Notify::INFO, $message, $title);
    }

    /**
     * Enregistre un message de type succès pour la requête HTTP courante.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function nowSuccess(string $message, ?string $title = null): Notify
    {
        return $this->now(Notify::SUCCESS, $message, $title);
    }

    /**
     * Enregistre un message de type avertissement pour la requête HTTP courante.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function nowWarning(string $message, ?string $title = null): Notify
    {
        return $this->now(Notify::WARNING, $message, $title);
    }

    /**
     * Enregistre un message de type erreur pour la requête HTTP courante.
     *
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    public function nowError(string $message, ?string $title = null): Notify
    {
        return $this->now(Notify::ERROR, $message, $title);
    }

    /**
     * Retourne les messages pour la requête HTTP courante.
     *
     * @return Collection
     */
    public function nowMessages(): Collection
    {
        return $this->stackMessages("notify_now_$this->stack");
    }

    /**
     * Enregistre un message pour la requête HTTP courante.
     *
     * @param string $type
     * @param string $message
     * @param string|null $title
     * @return Notify
     */
    private function now(string $type, string $message, ?string $title = null): Notify
    {
        static $count = 1;

        $id = "notify_now_$this->stack.".$count++;

        $this->session->now($id, [
            'id' => Str::slug($id),
            'type' => $type,
            'message' => $message,
            'title' => $title,
            'type_order' => $this->typeOrderKey($type),
        ]);

        return $this;
    }
}
