<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Exception;

use Exception;

use function get_class;

class UnsafeObject extends Exception
{
    /**
     * {@inheritDoc}
     */
    public function __construct($object, $code = 500)
    {
        parent::__construct("Unsafe Object: '" . get_class($object) . "'", $code);
    }
}
