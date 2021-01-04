<?php

namespace ApiSkeletonsTest\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\AbstractHydratorManager;

final class HydratorManager extends AbstractHydratorManager
{
    protected $classHydrators = [
        Model\User::class => Hydrator\UserHydrator::class,       
    ];
}
