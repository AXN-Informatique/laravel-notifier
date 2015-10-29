<?php

namespace Axn\LaravelNotifier;

class Bootstrap3 extends Notifier
{
    /**
     * Retourne le code HTML/JS pour l'afficage de la notification avec PNotify.
     *
     * @param  string      $type
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    protected function show($type, $message, $title = null)
    {
        if ($type === 'error') {
            $type = 'danger';
        }

        return view('notifier::bootstrap3', [
            'type'    => $type,
            'message' => $message,
            'title'   => $title
        ]);
    }
}
