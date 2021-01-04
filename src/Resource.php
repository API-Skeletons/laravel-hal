<?php

namespace ApiSkeletons\Laravel\HAL;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Resource
{
    protected $state = [];
    protected $links = [];
    protected $embedded = [];
    protected $paginationData = [];

    private $hydratorManager;

    public function setHydratorManager($hydratorManager)
    {
        $this->hydratorManager = $hydratorManager;

        return $this;
    }

    public function setState($data): self
    {
        if (! $data) {
            return $this;
        }

        foreach ($data as $key => $value) {
            if ($value instanceof Carbon) {
                $data[$key] = $value->toJson();
            }
        }

        $this->state = $data;

        if ($data && is_object($data)) {
            //debug_print_backtrace();
            throw new Exception\UnsafeObject($data);
        }

        return $this;
    }

    public function addLink($reference, $definition): self
    {
        array_push($this->links, new Link($reference, $definition));

        return $this;
    }

    public function addEmbeddedResource($ref, Resource $resource): self
    {
        $this->embedded[$ref] = $resource;

        return $this;
    }

    public function addEmbeddedResources($ref, Collection $collection): self
    {
        if (! isset($this->embedded[$ref])) {
            $this->embedded[$ref] = [];
        }

        $collection->each(function ($item) use ($ref) {
            if ($item instanceof Resource) {
                $this->embedded[$ref][] = $item;
            } else if ($this->hydratorManager->canExtract($item)) {
                $this->embedded[$ref][] = $this->hydratorManager->extract($item);
            } else {
                $this->embedded[$ref][] = (new self)->setHydratorManager($this->hydratorManager)->setState($item);
            }
        });

        return $this;
    }

    public function addPaginationData(array $paginationData): self
    {
        $this->paginationData = $paginationData;

        return $this;
    }

    public function toArray()
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

        if (! $data) {
            return null;
        }

        return $data;
    }
}
