<?php

namespace ApiSkeletons\Laravel\HAL\Contracts;

use ApiSkeletons\Laravel\HAL\Resource;

interface HydratorManagerContract
{
    public function canExtract($value);

    /**
     * @return Resource|Collection
     */
    public function extract($class);
}
