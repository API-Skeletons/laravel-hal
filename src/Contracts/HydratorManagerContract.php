<?php

namespace ApiSkeletons\Laravel\HAL\Contracts;

use ApiSkeletons\Laravel\HAL\Resource;

interface HydratorManagerContract
{
    public function canExtract($value);
    public function extract($class): Resource;
}
