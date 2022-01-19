<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Contracts;

use ApiSkeletons\Laravel\HAL\Resource;
use Illuminate\Support\Collection;

interface HydratorManagerContract
{
    /** @param mixed $value */
    public function canExtract($value): bool;

    public function extract(mixed $class, ?string $overrideHydrator = null): Collection|Resource;

    /**
     * @param mixed[]|null $state
     */
    public function resource(?array $state = null): Resource;
}
