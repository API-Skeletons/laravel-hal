<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL;

use function array_keys;
use function in_array;
use function is_array;

class Link
{
    /**
     * See https://tools.ietf.org/html/draft-kelly-json-hal-08#section-5
     *
     * @var array<string> $properties
     */
    private array $properties = [
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

    protected string $reference;

    /** @var array<mixed> $definition */
    protected array $definition;

    /** @param mixed $definition */
    public function __construct(string $reference, $definition)
    {
        $this->setReference($reference);
        $this->setDefinition($definition);
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    protected function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /** @return mixed */
    public function getDefinition()
    {
        return $this->definition;
    }

    /** @param mixed $definition */
    protected function setDefinition($definition): self
    {
        if (! is_array($definition)) {
            $this->definition = ['href' => $definition];

            return $this;
        }

        if ($this->getReference() !== 'curies' && ! in_array('href', array_keys($definition))) {
            throw new Exception\InvalidProperty("'href' is required");
        }

        foreach ($definition as $property => $value) {
            if ($this->getReference() !== 'curies' && ! in_array($property, $this->properties)) {
                throw new Exception\InvalidProperty("'" . $property . "' is an invalid property name");
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
                        throw new Exception\InvalidProperty(
                            "curies property '" . $curieProperty . "' in an invalid property name"
                        );
                    }
                }
            }

            if ($property !== 'templated' || $value === true) {
                continue;
            }

            $definition[$property] = false;
        }

        $this->definition = $definition;

        return $this;
    }
}
