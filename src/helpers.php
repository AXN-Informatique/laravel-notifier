<?php

use Axn\Notifier\Notify;

if (! function_exists('notify')) {
    /**
     * Accès à une instance du Notifier
     *
     * @param string|null $stack
     * @return Notify
     */
    function notify(?string $stack = null)
    {
        return app(Notify::class)->stack($stack);
    }
}
