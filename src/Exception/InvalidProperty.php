<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Exception;

use Exception;

class InvalidProperty extends Exception
{
    /**
     * {@inheritDoc}
     */
    public function __construct($message, $code)
    {
        if (! $code) {
            $code = 500;
        }

        parent::__construct($message, $code);
    }
}
