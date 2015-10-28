<?php

namespace Axn\LaravelNotify;

use Illuminate\Session\SessionManager;

abstract class Notify implements Contract
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
     * @param  SessionManager $session
     * @return void
     */
    public function __construct(SessionManager $session)
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
    public function flashSuccess($message, $title = null)
    {
        $this->flash('success', $message, $title);
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
     * Enregistre en session flash un message de type erreur.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    public function flashError($message, $title = null)
    {
        $this->flash('error', $message, $title);
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
     * Enregistre en session flash un message de type avertissement.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    public function flashWarning($message, $title = null)
    {
        $this->flash('warning', $message, $title);
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
     * Enregistre en session flash un message de type information.
     *
     * @param  string      $message
     * @param  string|null $title
     * @return void
     */
    public function flashInfo($message, $title = null)
    {
        $this->flash('info', $message, $title);
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
