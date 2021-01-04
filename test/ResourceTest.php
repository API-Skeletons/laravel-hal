<?php

declare(strict_types=1);

use ApiSkeletons\Laravel\HAL\Exception\UnsafeObject;
use ApiSkeletons\Laravel\HAL\Resource;
use ApiSkeletonsTest\Laravel\HAL\HydratorManager;
use ApiSkeletonsTest\Laravel\HAL\Model\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class ResourceTest extends TestCase
{
    public function testSetState(): void
    {
        $hydratorManager = new HydratorManager();
        $resource = $hydratorManager->resource();
        $resource->setState(['test' => 'state']);

        $this->assertEquals('state', $resource->toArray()['test']);
    }

    public function testSetStateWithCarbonObject(): void
    {
        $hydratorManager = new HydratorManager();
        $resource = $hydratorManager->resource();
        $resource->setState(['date' => new Carbon(new DateTime('2020-01-04 09:45:00'))]);

        $this->assertEquals('2020-01-04T09:45:00.000000Z', $resource->toArray()['date']);
    }

    public function testSetStateWithObject(): void
    {
        $this->expectException(UnsafeObject::class);
        $this->expectExceptionMessage("Unsafe Object: 'stdClass'");

        $hydratorManager = new HydratorManager();
        $resource = $hydratorManager->resource();
        $resource->setState(new stdClass());
    }

    public function testEmbeddedResource(): void
    {
        $hydratorManager = new HydratorManager();
        $resource1 = $hydratorManager->resource();
        $resource2 = $hydratorManager->resource();
        $resource2->setState(['test' => 'testing']);

        $resource1->addEmbeddedResource('embedded', $resource2);

        $this->assertEquals('testing', $resource1->toArray()['_embedded']['embedded']['test']);
    }

    public function testEmbeddedResources(): void
    {
        $hydratorManager = new HydratorManager();
        $resource = $hydratorManager->resource();

        // Test all three possible types pushed to addEmbeddedResources
        $collection = new Collection();
        $user = new User();
        $user->id = 1;
        $user->name = 'Test';
        $user->email = 'test@testing.net';
        $collection->push($user);

        $userResource = $hydratorManager->extract($user);
        $collection->push($userResource);

        $collection->push([
            'adhoc' => 'array',
        ]);

        $resource->addEmbeddedResources('resources', $collection);

        $this->assertEquals('Test', $resource->toArray()['_embedded']['resources'][0]['name']);
        $this->assertEquals('Test', $resource->toArray()['_embedded']['resources'][1]['name']);
        $this->assertEquals('array', $resource->toArray()['_embedded']['resources'][2]['adhoc']);
    }

    public function testToEmptyArray(): void
    {
        $hydratorManager = new HydratorManager();
        $resource1 = $hydratorManager->resource();

        $emptyArray = $resource1->toArray();

        $this->assertEquals([], $emptyArray);
    }
}
