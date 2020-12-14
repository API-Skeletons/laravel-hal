<?php

namespace ApiSkeletons\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\Contracts\HydratorManagerContract;

class AbstractHydratorManager implements HydratorManagerContract
{
    protected $classHydrators = [];

    public function canExtract($value): bool
    {
        if (! is_object($value)) {
            return false;
        }

        if (! in_array(get_class($value), array_keys($this->classHydrators))) {
            return false;
        }

        return true;
    }

    public function extract($class): Resource
    {
        $extractorClass = $this->classHydrators[get_class($class)];

        if ($this->canExtract($class)) {
            return (new $extractorClass())->setHydratorManager($this)->extract($class);
        }

        throw new Exception\UnsafeObject();
    }
}
