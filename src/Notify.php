<?php

namespace Axn\Notifier;

use Axn\Notifier\Concerns\CanGroupMessagesByType;
use Axn\Notifier\Concerns\HasFlashMessages;
use Axn\Notifier\Concerns\HasNowMessages;
use Illuminate\Session\SessionManager as Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;

class Notify
{
    use CanGroupMessagesByType;
    use Conditionable;
    use HasFlashMessages;
    use HasNowMessages;

    public const INFO = 'info';

    public const SUCCESS = 'success';

    public const WARNING = 'warning';

    public const ERROR = 'error';

    public const DEFAULT_STACK = 'default';

    /**
     * Instance du manager de session de Laravel.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * Le nom de la stack à utiliser.
     *
     * @var string
     */
    protected string $stack;

    /**
     * Constructeur.
     *
     * @param  SessionStore $session
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Définit la stack à utiliser.
     *
     * @param string|null $stack
     * @return self
     */
    public function stack(?string $stack): self
    {
        $this->stack = $stack ?? self::DEFAULT_STACK;

        return $this;
    }

    /**
     * Retourne les messages d'une stack donnée.
     *
     * @param string $stackName
     * @return Collection
     */
    private function stackMessages(string $stackName): Collection
    {
        if (! $this->session->has($stackName)) {
            return collect();
        }

        return collect($this->session->get($stackName));
    }

    /**
     * Retourne la clé de l'ordre d'affichage selon le type.
     *
     * @param string $type
     * @return int
     */
    private function typeOrderKey(string $type): int
    {
        static $typeKeys = null;

        if ($typeKeys === null) {
            $typeOrder = config('notifier.sort_type_order');

            foreach ($typeOrder as $key => $type) {
                $typeKeys[$type] = $key;
            }
        }

        return $typeKeys[$type];
    }
}
