<?php

declare(strict_types=1);

namespace Axn\Notifier;

use Axn\Notifier\Concerns\CanGroupMessagesByType;
use Axn\Notifier\Concerns\HasFlashMessages;
use Axn\Notifier\Concerns\HasNowMessages;
use Illuminate\Session\SessionManager as Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;

class Notify
{
    use CanGroupMessagesByType;
    use Conditionable;
    use HasFlashMessages;
    use HasNowMessages;

    public const string INFO = 'info';

    public const string SUCCESS = 'success';

    public const string WARNING = 'warning';

    public const string ERROR = 'error';

    public const string DEFAULT_STACK = 'default';

    /**
     * Le nom de la stack à utiliser.
     */
    protected ?string $stack = null;

    /**
     * Compteur pour les clés de session flash.
     */
    private int $flashCount = 0;

    /**
     * Compteur pour les clés de session now.
     */
    private int $nowCount = 0;

    /**
     * Cache des clés d'ordre par type.
     */
    private ?array $typeKeys = null;

    /**
     * Indique si les erreurs partagées par les vues ont déjà été ajoutées.
     */
    public bool $errorsAlreadyAdded = false;

    public function __construct(
        protected Session $session,
    ) {}

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
        if ($this->typeKeys === null) {
            $typeOrders = config('notifier.sort_type_order');

            foreach ($typeOrders as $key => $typeOrder) {
                $this->typeKeys[$typeOrder] = $key;
            }
        }

        return $this->typeKeys[$type] ?? 0;
    }

    /**
     * Construit le tableau de données d'un message de notification.
     */
    private function buildMessageData(string $id, string $type, string $message, ?string $title, ?int $delay): array
    {
        return [
            'id' => Str::slug($id),
            'type' => $type,
            'message' => $this->escapeString($message),
            'title' => $title !== null && $title !== '' && $title !== '0' ? $this->escapeString($title) : null,
            'delay' => $delay ?? 0,
            'type_order' => $this->typeOrderKey($type),
        ];
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
