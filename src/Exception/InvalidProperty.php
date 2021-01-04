<?php

namespace ApiSkeletons\Laravel\HAL\Exception;

use Exception;

class InvalidProperty extends Exception
{
    public function __construct($message, $code = 500) {
        parent::__construct($message, $code);
    }
}
