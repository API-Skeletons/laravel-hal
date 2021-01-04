<?php

declare(strict_types=1);

use ApiSkeletons\Laravel\HAL\Link;
use ApiSkeletons\Laravel\HAL\Exception\InvalidProperty;
use PHPUnit\Framework\TestCase;

final class LinkTest extends TestCase
{
    public function testCreateHref(): void
    {
        $link = new Link('self', 'https://test/self');

        $this->assertEquals('self', $link->getReference());
        $this->assertEquals(['href' => 'https://test/self'], $link->getDefinition());
    }

    public function testCreateComplexLink(): void
    {
        $link = new Link('test', [
            'name' => 'test name',
            'href' => 'https://test/test',
            'title' => 'Test Link',
            'templated' => false,
            'type' => 'test',
            'deprecation' => 'https://test/test-deprecation',
            'profile' => 'https://test/profile',
            'hreflang' => 'en',
        ]);

        $this->assertEquals('test', $link->getReference());
        $this->assertEquals('test name', $link->getDefinition()['name']);
        $this->assertEquals('https://test/test', $link->getDefinition()['href']);
        $this->assertEquals('Test Link', $link->getDefinition()['title']);
        $this->assertEquals(false, $link->getDefinition()['templated']);
        $this->assertEquals('test', $link->getDefinition()['type']);
        $this->assertEquals('https://test/test-deprecation', $link->getDefinition()['deprecation']);
        $this->assertEquals('https://test/profile', $link->getDefinition()['profile']);
        $this->assertEquals('en', $link->getDefinition()['hreflang']);
    }

    public function testHrefIsRequired(): void
    {
        $this->expectException(InvalidProperty::class);
        $this->expectExceptionMessage("'href' is required");

        $link = new Link('test', ['invalid' => true]);
    }

    public function testInvalidDefinition(): void
    {
        $this->expectException(InvalidProperty::class);
        $this->expectExceptionMessage("'invalid' is an invalid property name");

        $link = new Link('test', ['href' => 'https://test/test', 'invalid' => true]);
    }

    public function testDefinitionValuesCannotBeArray(): void
    {
        $this->expectException(InvalidProperty::class);
        $this->expectExceptionMessage('Properties cannot be arrays');

        $link = new Link('test', ['href' => 'https://test/test', 'title' => array('test', 'test2')]);
    }

    public function testCuries(): void
    {
        $link = new Link('curies', [['href' => 'https://test/curie1', 'title' => 'Curie 1'], ['href' => 'https://test/curie2', 'title' => 'Curie 2']]);

        $this->assertEquals('curies', $link->getReference());
    }

    public function testInvalidCuriesDefinition(): void
    {
        $this->expectException(InvalidProperty::class);
        $this->expectExceptionMessage("curies property 'invalid' in an invalid property name");

        $link = new Link('curies', [['href' => 'https://test/curie1', 'invalid' => 'Curie 1'], ['href' => 'https://test/curie2', 'title' => 'Curie 2']]);

        $this->assertEquals('curies', $link->getReference());
    }

    public function testCuriesMustBeArray(): void
    {
        $this->expectException(InvalidProperty::class);
        $this->expectExceptionMessage("curies must be an array");

        $link = new Link('curies', ['href' => 'https://test/curie1', 'invalid' => 'Curie 1']);

        $this->assertEquals('curies', $link->getReference());
    }
}
