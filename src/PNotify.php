<?php

namespace Axn\LaravelNotify;

class PNotify extends Notify
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

        //return '<script>jQuery(function() {new PNotify('.json_encode($options).');})</script>';

        return
            '<script type="text/javascript">' . "\n" .
            "\t" . 'window.onload = function(){' . "\n" .
            "\t\t" . 'jQuery(function(){' . "\n" .
            "\t\t\t" . 'new PNotify(' . json_encode($options) . ');' . "\n" .
            "\t\t" . '});' . "\n" .
            "\t" . '};' .  "\n" .
            '</script>' . "\n";
    }
}
