<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\Contracts\HydratorManagerContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use function app;
use function collect;
use function get_class;
use function is_object;

abstract class HydratorManager implements HydratorManagerContract
{
    /** @var array<string> */
    protected array $classHydrators = [];

    public function paginate(string $description, LengthAwarePaginator $paginator, ?string $overrideHydrator = null): Resource
    {
        $resource = (new Resource())->setHydratorManager($this);
        $resource->addEmbeddedResources($description, $paginator->getCollection(), $overrideHydrator);
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

    /** @param mixed $value */
    public function canExtract($value): bool
    {
        if (! is_object($value)) {
            return false;
        }

        return isset($this->classHydrators[get_class($value)]);
    }

    public function extract(mixed $class, ?string $overrideHydrator = null): Collection|Resource
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

        if (! $overrideHydrator && ! isset($this->classHydrators[get_class($class)])) {
            if (! isset($this->classHydrators['*']) || ! $this->classHydrators['*']) {
                throw new Exception\NoHydrator(get_class($class));
            }

            $overrideHydrator = $this->classHydrators['*'];
        }

        // Use DI for hydrators
        $extractorClass = $overrideHydrator ?: $this->classHydrators[get_class($class)];
        $hydrator       = app($extractorClass);

        return $hydrator->setHydratorManager($this)->extract($class);
    }

    /**
     * Return an empty resource or use supplied state
     *
     * @param array<mixed> $state
     */
    public function resource(?array $state = null): Resource
    {
        return $this->extract(null)->setState($state);
    }
}
