<?php

namespace Axn\LaravelNotifier;

interface Contract
{
    public function success($message, $title = null);
    public function info($message, $title = null);
    public function warning($message, $title = null);
    public function error($message, $title = null);

    public function showSuccess($view, $message, $title = null);
    public function showInfo($view, $message, $title = null);
    public function showWarning($view, $message, $title = null);
    public function showError($view, $message, $title = null);

    public function showFlash($view);
}
