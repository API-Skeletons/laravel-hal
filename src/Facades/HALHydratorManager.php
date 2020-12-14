<?php

namespace App\HAL\Facades;

use Illuminate\Support\Facades\Facade;

class HALHydratorManager extends Facade
{
    protected static function getFacadeAccessor() {
        return 'halHydratorManager';
    }
}
