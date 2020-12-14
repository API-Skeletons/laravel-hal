<?php

namespace App\Hal\Contracts;

use App\Hal\Resource;

interface HydratorContract
{
    public function extract($class): Resource;
}
