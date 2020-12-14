<?php

namespace ApiSkeletons\Laravel\HAL;

use Illuminate\Support\Collection;

class Resource
{
    protected $state = [];
    protected $links = [];
    protected $embedded = [];

    private $hydratorManager;

    public function setHydratorManager($hydratorManager)
    {
        $this->hydratorManager = $hydratorManager;

        return $this;
    }

    public function setState($data): self
    {
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
        $ref = trim(strtolower($ref));

        if (!isset($this->embedded[$ref])) {
            $this->embedded[$ref] = [];
        }
        $this->embedded[$ref][] = $resource;

        return $this;
    }

    public function addEmbeddedResources($ref, Collection $collection): self
    {
        $collection->each(function ($item) use ($ref) {
            if ($this->hydratorManager->canExtract($item)) {
                $this->addEmbeddedResource(
                    $ref,
                    (new self)->setHydratorManager($this->hydratorManager)->setState($this->hydratorManager->extract($item))
                );
            } else {
                $this->addEmbeddedResource($ref, (new self)->setHydratorManager($this->hydratorManager)->setState($item));
            }
        });

        return $this;
    }

    public function toArray(): array
    {
        $data = $this->state;

        foreach ($this->links as $ref => $href) {
            $data['_links'][$ref]['href'] = $href;
        }

        if ($this->embedded) {
            $data['_embedded'] = [];

            foreach ($this->embedded as $ref => $resources) {
                $data['_embedded'][$ref] = [];
                foreach ($resources as $resource) {
                    $data['_embedded'][$ref][] = $resource->toArray();
                }
            }
        }

        return $data;
    }
}
