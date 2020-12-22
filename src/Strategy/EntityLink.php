<?php

namespace ApiSkeletons\Laravel\HAL\Strategy;

use ApiSkeletons\Laravel\HAL\Resource;

class EntityLink extends AbstractStrategy
{
    public function __invoke(...$args)
    {
        return $this->hydratorManager->resource()
            ->addLink('self', $args[0]);
    }
}
