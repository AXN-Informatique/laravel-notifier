<?php

namespace Axn\LaravelNotify;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    public static function getFacadeAccessor()
    {
        return 'Axn\LaravelNotify\Contract';
    }
}
