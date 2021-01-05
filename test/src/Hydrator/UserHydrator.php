<?php

declare(strict_types=1);

namespace ApiSkeletonsTest\Laravel\HAL\Hydrator;

use ApiSkeletons\Laravel\HAL\AbstractHydrator;
use ApiSkeletons\Laravel\HAL\Resource;

final class UserHydrator extends AbstractHydrator
{
    /** {@inheritdoc} */
    public function extract($class): Resource
    {
        $data = [];

        $fields = [
            'id',
            'name',
            'email',
        ];

        foreach ($fields as $field) {
            $data[$field] = $class->$field;
        }

        return $this->hydratorManager->resource($data)
            ->addLink('self', 'https://test/user/' . $data['id']);
    }
}
