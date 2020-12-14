<?php

namespace App\HAL;

use App\HAL\Contracts\HydratorContract;
use App\HAL\Contracts\HydratorManagerContract;

abstract class AbstractHydrator implements HydratorContract
{
    protected $hydratorManager;

    public function setHydratorManager(HydratorManagerContract $hydratorManager)
    {
        $this->hydratorManager = $hydratorManager;

        return $this;
    }

    public function createResource($data)
    {
        return (new Resource())
            ->setHydratorManager($this->hydratorManager)
            ->setState($data);
    }
}

