<?php

namespace ApiSkeletons\Laravel\HAL\Contracts;

use ApiSkeletons\Laravel\HAL\Resource;

interface HydratorContract
{
    public function extract($class): Resource;
}
