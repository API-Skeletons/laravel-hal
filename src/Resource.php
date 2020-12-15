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
        foreach ($data as $key => $value) {
            if ($value instanceof Carbon) {
                $data[$key] = $value->toJson();
            }
        }

        $this->state = $data;

        return $this;
    }

    public function addLink($ref, $href): self
    {
        $this->links[$ref] = trim(strtolower($href));

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
            if ($this->hydratorManager->canExtract($item)) {
                $this->embedded[$ref][] = $this->hydratorManager->extract($item);
            } else {
                $this->addEmbeddedResource($ref, (new self)->setHydratorManager($this->hydratorManager)->setState($item));
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

        foreach ($this->links as $ref => $href) {
            $data['_links'][$ref]['href'] = $href;
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
