<?php

namespace Axn\LaravelNotifier;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    public static function getFacadeAccessor()
    {
        return '\Axn\LaravelNotifier\Contract';
    }
}
