<?php

if (!function_exists('notify')) {
    /**
     * Accès au notifier (alternive à la façade)
     *
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    function notify()
    {
        return app(\Axn\LaravelNotifier\Contract::class);
    }
}

if (!function_exists('notifier')) {
    /**
     * Alias de notify()
     *
     * @see notify()
     */
    function notifier()
    {
        return notifier();
    }
}

if (!function_exists('notify_info')) {
    /**
     * Notificatron flash type "info"
     *
     * @see notifier()
     */
    function notify_info($message, $title = null)
    {
        return notify()->info($message, $title);
    }
}

if (!function_exists('notify_success')) {
    /**
     * Notificatron flash type "success"
     *
     * @see notifier()
     */
    function notify_success($message, $title = null)
    {
        return notify()->success($message, $title);
    }
}

if (!function_exists('notify_warning')) {
    /**
     * Notificatron flash type "warning"
     *
     * @see notifier()
     */
    function notify_warning($message, $title = null)
    {
        return notify()->warning($message, $title);
    }
}

if (!function_exists('notify_error')) {
    /**
     * Notificatron flash type "error"
     *
     * @see notifier()
     */
    function notify_error($message, $title = null)
    {
        return notify()->error($message, $title);
    }
}
