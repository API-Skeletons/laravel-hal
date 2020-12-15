<?php

namespace ApiSkeletons\Laravel\HAL\Exception;

use Exception;

class NoHydrator extends Exception
{
    public function __construct($message, $code = 500) {
        parent::__construct('No hydrator exists for class ' . $message, $code);
    }
}
