<?php

declare(strict_types=1);

namespace ApiSkeletonsTest\Laravel\HAL\Hydrator;

use ApiSkeletons\Laravel\HAL\Hydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use Illuminate\Foundation\Application;

use function get_class;

final class DiHydrator extends Hydrator
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /** {@inheritdoc} */
    public function extract($class): Resource
    {
        $data = [
            'app' => get_class($this->app),
        ];

        return $this->hydratorManager->resource($data);
    }
}
