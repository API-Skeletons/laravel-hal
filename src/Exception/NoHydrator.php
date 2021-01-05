<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Exception;

use Exception;

class NoHydrator extends Exception
{
    /**
     * {@inheritDoc}
     */
    public function __construct($message, $code = 500)
    {
        parent::__construct("No hydrator exists for class '" . $message . "'", $code);
    }
}
