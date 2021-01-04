<?php

namespace ApiSkeletons\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\Contracts\HydratorContract;
use ApiSkeletons\Laravel\HAL\Contracts\HydratorManagerContract;

abstract class AbstractHydrator implements HydratorContract
{
    protected $hydratorManager;

    public function setHydratorManager(HydratorManagerContract $hydratorManager)
    {
        $this->hydratorManager = $hydratorManager;

        return $this;
    }
}

