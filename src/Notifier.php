<?php

namespace Axn\LaravelNotifier;

use Illuminate\Contracts\Session\Session as SessionInterface;

class Notifier implements Contract
{
    /**
     * Instance du manager de session de Laravel.
     *
     * @var SessionInterface $session
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
     * @param string $message Le message à afficher
     * @param string|null $title Un titre (null)
     * @param string|null $view Un template de vue (null)
     * @return \Illuminate\Contracts\View\View
     */
    public function showSuccess($message, $title = null, $view = null)
    {
        return $this->show('success', $message, $title, $view);
    }

    /**
     * Affiche un message de type information.
     *
     * @param string $message Le message à afficher
     * @param string|null $title Un titre (null)
     * @param string|null $view Un template de vue (null)
     * @return \Illuminate\Contracts\View\View
     */
    public function showInfo($message, $title = null, $view = null)
    {
        return $this->show('info', $message, $title, $view);
    }

    /**
     * Affiche un message de type avertissement.
     *
     * @param string $message Le message à afficher
     * @param string|null $title Un titre (null)
     * @param string|null $view Un template de vue (null)
     * @return \Illuminate\Contracts\View\View
     */
    public function showWarning($message, $title = null, $view = null)
    {
        return $this->show('warning', $message, $title, $view);
    }

    /**
     * Affiche un message de type erreur.
     *
     * @param string $message Le message à afficher
     * @param string|null $title Un titre (null)
     * @param string|null $view Un template de vue (null)
     * @return \Illuminate\Contracts\View\View
     */
    public function showError($message, $title = null, $view = null)
    {
        return $this->show('error', $message, $title, $view);
    }

    /**
     * Affiche un message enregistré en session flash.
     *
     * @param  string|null $view
     * @return string
     */
    public function showFlash($view = null)
    {
        if (!$this->session->has('notify')) {
            return;
        }

        $args = array_merge(
            $this->session->get('notify'),
            ['view' => $view ?? config('notifier.default_view')]
        );

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
     * @param string $message Le message à afficher
     * @param string|null $title Un titre (null)
     * @param string|null $view Un template de vue (null)
     * @return \Illuminate\Contracts\View\View
     */
    protected function show($type, $message, $title = null, $view = null)
    {
        return view(
            $view ?? config('notifier.default_view'),
            compact('type', 'message', 'title')
        );
    }
}
