<?php

namespace Axn\LaravelNotifier;

use Illuminate\Session\SessionInterface;

abstract class Notifier implements Contract
{
    /**
     * Instance du manager de session de Laravel.
     *
     * @var SessionManager
     */
    protected $session;

    /**
     * Constructeur.
     *
     * @param  SessionInterface $session
     * @return void
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Enregistre en session flash un message de type succès.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    public function success($message, $title = null)
    {
        $this->flash('success', $message, $title);
    }

    /**
     * Enregistre en session flash un message de type information.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    public function info($message, $title = null)
    {
        $this->flash('info', $message, $title);
    }

    /**
     * Enregistre en session flash un message de type avertissement.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    public function warning($message, $title = null)
    {
        $this->flash('warning', $message, $title);
    }

    /**
     * Enregistre en session flash un message de type erreur.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    public function error($message, $title = null)
    {
        $this->flash('error', $message, $title);
    }

    /**
     * Affiche un message de type succès.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showSuccess($message, $title = null)
    {
        return $this->show('success', $message, $title);
    }

    /**
     * Affiche un message de type information.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showInfo($message, $title = null)
    {
        return $this->show('info', $message, $title);
    }

    /**
     * Affiche un message de type avertissement.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showWarning($message, $title = null)
    {
        return $this->show('warning', $message, $title);
    }

    /**
     * Affiche un message de type erreur.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showError($message, $title = null)
    {
        return $this->show('error', $message, $title);
    }

    /**
     * Affiche un message enregistré en session flash.
     *
     * @return string
     */
    public function showFlash()
    {
        if (!$this->session->has('notify')) {
            return '';
        }

        return call_user_func_array([$this, 'show'], $this->session->get('notify'));
    }

    /**
     * Enregistre un message en session flash.
     *
     * @param  string      $type
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    protected function flash($type, $message, $title = null)
    {
        $this->session->flash('notify', compact('type', 'message', 'title'));
    }

    /**
     * Affiche un message.
     *
     * @param  string      $type
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    abstract protected function show($type, $message, $title = null);
}
