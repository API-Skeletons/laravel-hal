<?php

namespace ApiSkeletons\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\Contracts\HydratorManagerContract;
use ApiSkeletons\Laravel\HAL\Resource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

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

    protected function extractCollection($class, $overrideHydrator): Resource
    {

    }

    /**
     * @return Resource|Collection
     */
    public function extract($class, $overrideHydrator = null)
    {
        if (! $class) {
            return (new Resource())->setHydratorManager($this);
        }

        // Allow for collections of classes to be extracted into a collection
        if ($class instanceof Collection) {
            $resources = collect();
            foreach ($class as $entity) {
                $resources->push($this->extract($entity, $overrideHydrator ?: null));
            }

            return $resources;
        }

        $extractorClass = ($overrideHydrator) ?: $this->classHydrators[get_class($class)];

        if (! $overrideHydrator && ! isset($this->classHydrators[get_class($class)])) {
            throw new Exception\NoHydrator(get_class($class));
        }

        if ($overrideHydrator || $this->canExtract($class)) {
            return (new $extractorClass())->setHydratorManager($this)->extract($class);
        }

        throw new Exception\UnsafeObject($class);
    }

    /**
     * Return an empty resource
     */
    public function resource($data = null): Resource
    {
        return $this->extract(null)
            ->setState($data);
    }
}
