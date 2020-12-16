<?php

namespace ApiSkeletons\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\Contracts\HydratorManagerContract;
use ApiSkeletons\Laravel\HAL\Resource;
use Illuminate\Pagination\LengthAwarePaginator;

class AbstractHydratorManager implements HydratorManagerContract
{
    protected $classHydrators = [];

    public function paginate($description, LengthAwarePaginator $paginator)
    {
        $resource = (new Resource())->setHydratorManager($this);
        $resource->addEmbeddedResources($description, $paginator->getCollection());
        $resource->addPaginationData([
            'page_count' => $paginator->lastPage(),
            'page_size' => $paginator->perPage(),
            'total_items' => $paginator->total(),
            'page' => $paginator->currentPage(),
        ]);

        $resource->addLink('self', $paginator->url($paginator->currentPage()));
        $resource->addLink('first', $paginator->url(1));
        $resource->addLink('last', $paginator->url($paginator->lastPage()));

        if ($paginator->currentPage() !== $paginator->lastPage()) {
            $resource->addLink('next', $paginator->nextPageUrl());
        }
        if (! $paginator->onFirstPage()) {
            $resource->addLink('prev', $paginator->previousPageUrl());
        }

        return $resource;
    }

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
        if (! $class) {
            return new Resource($this);
        }

        if (! isset($this->classHydrators[get_class($class)])) {
            throw new Exception\NoHydrator(get_class($class));
        }

        $extractorClass = $this->classHydrators[get_class($class)];

        if ($this->canExtract($class)) {
            return (new $extractorClass())->setHydratorManager($this)->extract($class);
        }

        throw new Exception\UnsafeObject();
    }
}
