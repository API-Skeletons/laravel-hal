<?php

declare(strict_types=1);

namespace ApiSkeletonsTest\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\HydratorManager as HalHydratorManager;

final class WildcardHydratorManager extends HalHydratorManager
{
    /** {@inheritdoc} */
    protected array $classHydrators = [
        '*' => Hydrator\WildcardHydrator::class,
    ];
}
