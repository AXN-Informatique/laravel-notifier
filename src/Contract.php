<?php

namespace Axn\LaravelNotify;

interface Contract
{
    public function flashSuccess($message, $title = null);

    public function showSuccess($message, $title = null);

    public function flashError($message, $title = null);

    public function showError($message, $title = null);

    public function flashWarning($message, $title = null);

    public function showWarning($message, $title = null);

    public function flashInfo($message, $title = null);

    public function showInfo($message, $title = null);

    public function showFlash();
}
