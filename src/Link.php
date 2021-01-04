<?php

namespace ApiSkeletons\Laravel\HAL;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Link
{
    // See https://tools.ietf.org/html/draft-kelly-json-hal-08#section-5
    private $properties = [
        'href',
        'templated',
        'type',
        'deprecation',
        'name',
        'profile',
        'title',
        'hreflang',
        'curies',
    ];

    protected $reference;
    protected $definition;

    public function __construct($reference, $definition)
    {
        $this->setReference($reference);
        $this->setDefinition($definition);
    }

    public function getReference()
    {
        return $this->reference;
    }

    protected function setReference($reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    protected function setDefinition($definition): self
    {
        if (! is_array($definition)) {
            $this->definition = ['href' => $definition];

            return $this;
        }

        if (! in_array('href', array_keys($definition)) && ! in_array('curies', array_keys($definition))) {
            throw new Exception\InvalidProperty("'href' is required");
        }

        foreach ($definition as $property => $value) {
            if ($this->getReference() !== 'curies' && ! in_array($property, $this->properties)) {
                throw new Exception\InvalidProperty("'$property' is an invalid property name");
            }

            if ($this->getReference() !== 'curies' && is_array($value)) {
                throw new Exception\InvalidProperty('Properties cannot be arrays');
            }

            if ($this->getReference() === 'curies') {
                if (! is_array($value)) {
                    throw new Exception\InvalidProperty('curies must be an array');
                }

                foreach ($value as $curieProperty => $curieValue) {
                    if (! in_array($curieProperty, $this->properties)) {
                        throw new Exception\InvalidProperty("curies property '$curieProperty' in an invalid property name");
                    }
                }
            }

            if ($property === 'templated' && $value !== true) {
                $definition[$property] = false;
            }
        }

        $this->definition = $definition;

        return $this;
    }
}
