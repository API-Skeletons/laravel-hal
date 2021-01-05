<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Contracts;

use ApiSkeletons\Laravel\HAL\Resource;

interface HydratorContract
{
    /** @param mixed $class */
    public function extract($class): Resource;
}
