<?php

namespace Axn\LaravelNotifier;

class PNotify extends Notifier
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
        $options = ['type' => $type, 'text' => $message];

        if (!empty($title)) {
            $options['title'] = $title;
        }

        return view('notifier::pnotify', [
            'options' => $options
        ]);
    }
}
