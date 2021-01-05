<?php

declare(strict_types=1);

namespace ApiSkeletonsTest\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\HydratorManager;

final class HydratorManager extends HydratorManager
{
    /** {@inheritdoc} */
    protected $classHydrators = [
        Model\User::class => Hydrator\UserHydrator::class,
    ];
}
