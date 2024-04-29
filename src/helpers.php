<?php

use Axn\Notifier\Notify;

if (! function_exists('notify')) {
    /**
     * Accès à une instance du Notifier
     */
    function notify(?string $stack = null): Notify
    {
        return app(Notify::class)->stack($stack);
    }
}
