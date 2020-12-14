<?php

namespace App\Hal\Contracts;

use App\Hal\Resource;

interface HydratorManagerContract
{
    public function canExtract($value);
    public function extract($class): Resource;
}
