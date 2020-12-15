<?php

namespace ApiSkeletons\Laravel\HAL\Strategy;

use ApiSkeletons\Laravel\HAL\Resource;

class EntityLink extends AbstractStrategy
{
    public function __invoke(...$args)
    {
        return (new Resource($this->hydratorManager))
            ->addLink('self', $args[0]);
    }
}
