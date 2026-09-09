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
use WeakReference;

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
     * Le sac d'erreurs partagé dont les messages ont déjà été repris.
     *
     * L'identité du sac, et non un simple drapeau : ce service est un singleton,
     * et un serveur qui survit aux requêtes (Octane) le réutilise d'une requête à
     * l'autre. Un drapeau y resterait levé et ferait disparaître toute erreur
     * suivante. La référence est faible, le sac appartenant à la requête.
     */
    private ?WeakReference $handledErrorBag = null;

    public function hasErrorsBeenAdded(object $errorBag): bool
    {
        return $this->handledErrorBag?->get() === $errorBag;
    }

    public function markErrorsAsAdded(object $errorBag): void
    {
        $this->handledErrorBag = WeakReference::create($errorBag);
    }

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
            'message' => $this->escapeQuotes($message),
            'title' => $title !== null && $title !== '' ? $this->escapeQuotes($title) : null,
            'delay' => $delay ?? 0,
            'type_order' => $this->typeOrderKey($type),
        ];
    }

    /**
     * Échappe les guillemets simples et doubles pour l'affichage dans du Javascript.
     *
     * N'échappe pas les autres caractères HTML (<, >, &) contrairement à e().
     */
    private function escapeQuotes(string $string): string
    {
        return strtr($string, [
            "'" => '&apos;',
            '"' => '&quot;',
        ]);
    }
}
