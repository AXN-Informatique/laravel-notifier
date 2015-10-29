<?php

namespace Axn\LaravelNotifier;

interface Contract
{
    public function success($message, $title = null);
    public function info($message, $title = null);
    public function warning($message, $title = null);
    public function error($message, $title = null);

    public function showSuccess($message, $title = null);
    public function showInfo($message, $title = null);
    public function showWarning($message, $title = null);
    public function showError($message, $title = null);

    public function showFlash();
}
