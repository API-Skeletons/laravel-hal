<?php

namespace ApiSkeletons\Laravel\HAL\Exception;

use Exception;

class UnsafeObject extends Exception
{
    public function __construct($object, $code = 500) {
        parent::__construct("Unsafe Object: '" . get_class($object) . "'", $code);
    }
}
