<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\Contracts\HydratorContract;
use ApiSkeletons\Laravel\HAL\Contracts\HydratorManagerContract;

abstract class Hydrator implements HydratorContract
{
    protected HydratorManagerContract $hydratorManager;

    public function setHydratorManager(HydratorManagerContract $hydratorManager): self
    {
        $this->hydratorManager = $hydratorManager;

        return $this;
    }
}
