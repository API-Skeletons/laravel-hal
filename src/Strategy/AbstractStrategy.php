<?php

namespace ApiSkeletons\Laravel\HAL\Strategy;

use ApiSkeletons\Laravel\HAL\Contracts\HydratorContract;
use ApiSkeletons\Laravel\HAL\Contracts\HydratorManagerContract;

abstract class AbstractStrategy
{
    protected $hydratorManager;

    public function __construct(HydratorManagerContract $hydratorManager)
    {
        $this->hydratorManager = $hydratorManager;
    }

    abstract public function __invoke(...$args);
}

