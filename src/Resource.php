<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

use function array_merge;
use function array_push;
use function is_array;

class Resource
{
    /** @var mixed[] */
    protected array $state = [];

    /** @var Link[] */
    protected array $links = [];

    /** @var mixed[] */
    protected array $embedded = [];

    /** @var mixed[] */
    protected array $paginationData = [];

    private Contracts\HydratorManagerContract $hydratorManager;

    public function setHydratorManager(Contracts\HydratorManagerContract $hydratorManager): self
    {
        $this->hydratorManager = $hydratorManager;

        return $this;
    }

    /** @param mixed[] $data */
    public function setState(?array $data): self
    {
        if (! $data) {
            return $this;
        }

        foreach ($data as $key => $value) {
            if (! ($value instanceof Carbon)) {
                continue;
            }

            $data[$key] = $value->toJson();
        }

        $this->state = $data;

        return $this;
    }

    /** @param string|mixed[] $definition */
    public function addLink(string $reference, $definition): self
    {
        array_push($this->links, new Link($reference, $definition));

        return $this;
    }

    public function addEmbeddedResource(string $ref, Resource $resource): self
    {
        $this->embedded[$ref] = $resource;

        return $this;
    }

    public function addEmbeddedResources(string $ref, Collection $collection, ?string $hydrator = null): self
    {
        if (! isset($this->embedded[$ref])) {
            $this->embedded[$ref] = [];
        }

        $collection->each(function ($item) use ($ref, $hydrator): void {
            if ($item instanceof Resource) {
                $this->embedded[$ref][] = $item;
            } elseif ($hydrator) {
                $this->embedded[$ref][] = $this->hydratorManager->extract($item, $hydrator);
            } elseif ($this->hydratorManager->canExtract($item)) {
                $this->embedded[$ref][] = $this->hydratorManager->extract($item);
            } else {
                $this->embedded[$ref][] = (new self())->setHydratorManager($this->hydratorManager)->setState($item);
            }
        });

        return $this;
    }

    /** @param mixed[] $paginationData */
    public function addPaginationData(array $paginationData): self
    {
        $this->paginationData = $paginationData;

        return $this;
    }

    /** @return mixed[] */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->links as $link) {
            $data['_links'][$link->getReference()] = $link->getDefinition();
        }

        $data = array_merge($data, $this->state);

        if ($this->embedded) {
            $data['_embedded'] = [];

            foreach ($this->embedded as $ref => $resources) {
                if (is_array($resources)) {
                    $data['_embedded'][$ref] = [];
                    foreach ($resources as $resource) {
                        $data['_embedded'][$ref][] = $resource->toArray();
                    }
                } else {
                    $data['_embedded'][$ref] = $resources->toArray();
                }
            }
        }

        if ($this->paginationData) {
            $data = array_merge($data, $this->paginationData);
        }

        return $data;
    }
}
