<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Contracts;

use ApiSkeletons\Laravel\HAL\Resource;
use Illuminate\Support\Collection;

interface HydratorManagerContract
{
    /** @param mixed $value */
    public function canExtract($value): bool;

    /**
     * @param mixed $class
     *
     * @return Resource|Collection
     */
    public function extract($class);
}
