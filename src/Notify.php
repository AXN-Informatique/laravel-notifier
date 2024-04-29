<?php

declare(strict_types=1);

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
     */
    protected Session $session;

    /**
     * Le nom de la stack à utiliser.
     */
    protected ?string $stack = null;

    /**
     * Constructeur.
     *
     * @param  SessionStore  $session
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Définit la stack à utiliser.
     */
    public function stack(?string $stack = null): self
    {
        $this->stack = $stack ?? self::DEFAULT_STACK;

        return $this;
    }

    /**
     * Retourne les messages d'une stack donnée.
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
     */
    private function typeOrderKey(string $type): int
    {
        static $typeKeys = null;

        if ($typeKeys === null) {
            $typeOrders = config('notifier.sort_type_order');

            foreach ($typeOrders as $key => $typeOrder) {
                $typeKeys[$typeOrder] = $key;
            }
        }

        return $typeKeys[$type];
    }

    /**
     * Cette méthode transforme certain caractères.
     *
     * ELle n'a rien à voir par exemple avec la méthoe e($string) de Laravel.
     *
     * Ceci est nécessaire notamment lorsque les chaines sont affichées dans du Javascript.
     */
    private function escapeString(string $string): string
    {
        return str_replace(["'", '"'], ['&apos;', '&quot;'], $string);
    }
}
