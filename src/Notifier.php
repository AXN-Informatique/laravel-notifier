<?php

namespace Axn\LaravelNotifier;

use Illuminate\Contracts\Session\Session as SessionInterface;

class Notifier implements Contract
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
     * @param  string      $view
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showSuccess($view, $message, $title = null)
    {
        return $this->show($view, 'success', $message, $title);
    }

    /**
     * Affiche un message de type information.
     *
     * @param  string      $view
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showInfo($view, $message, $title = null)
    {
        return $this->show($view, 'info', $message, $title);
    }

    /**
     * Affiche un message de type avertissement.
     *
     * @param  string      $view
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showWarning($view, $message, $title = null)
    {
        return $this->show($view, 'warning', $message, $title);
    }

    /**
     * Affiche un message de type erreur.
     *
     * @param  string      $view
     * @param  string      $message
     * @param  string|null $title
     * @return string
     */
    public function showError($view, $message, $title = null)
    {
        return $this->show($view, 'error', $message, $title);
    }

    /**
     * Affiche un message enregistré en session flash.
     *
     * @param  string $view
     * @return string
     */
    public function showFlash($view)
    {
        if (!$this->session->has('notify')) {
            return '';
        }

        $args = ['view' => $view] + $this->session->get('notify');

        return call_user_func_array([$this, 'show'], $args);
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
    protected function show($view, $type, $message, $title)
    {
        return view($view, compact('type', 'message', 'title'));
    }
}
