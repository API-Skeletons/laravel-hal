<?php

declare(strict_types=1);

namespace ApiSkeletonsTest\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\HydratorManager as HalHydratorManager;

final class HydratorManager extends HalHydratorManager
{
    /** {@inheritdoc} */
    protected $classHydrators = [
        Model\User::class => Hydrator\UserHydrator::class,
    ];
}
