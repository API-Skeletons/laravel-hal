<?php

declare(strict_types=1);

namespace ApiSkeletonsTest\Laravel\HAL;

use ApiSkeletonsTest\Laravel\HAL\Hydrator\UserHydrator;
use ApiSkeletonsTest\Laravel\HAL\Model\User;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class ResourceTest extends TestCase
{
    public function testSetState(): void
    {
        $hydratorManager = new HydratorManager();
        $resource        = $hydratorManager->resource();
        $resource->setState(['test' => 'state']);

        $this->assertEquals('state', $resource->toArray()['test']);
    }

    public function testSetStateWithCarbonObject(): void
    {
        $hydratorManager = new HydratorManager();
        $resource        = $hydratorManager->resource();
        $resource->setState(['date' => new Carbon(new DateTime('2020-01-04 09:45:00'))]);

        $this->assertEquals('2020-01-04T09:45:00.000000Z', $resource->toArray()['date']);
    }

    public function testEmbeddedResource(): void
    {
        $hydratorManager = new HydratorManager();
        $resource1       = $hydratorManager->resource();
        $resource2       = $hydratorManager->resource();
        $resource2->setState(['test' => 'testing']);

        $resource1->addEmbeddedResource('embedded', $resource2);

        $this->assertEquals('testing', $resource1->toArray()['_embedded']['embedded']['test']);
    }

    public function testNullEmbeddedResource(): void
    {
        $hydratorManager = new HydratorManager();
        $resource1       = $hydratorManager->resource();
        $resource1->addEmbeddedResource('embedded', null);
        $result = $resource1->toArray();

        $this->assertNull($result['_embedded']['embedded']);
    }

    public function testObjectEmbeddedResource(): void
    {
        $user        = new User();
        $user->id    = 1;
        $user->name  = 'Test';
        $user->email = 'test@testing.net';

        $hydratorManager = new HydratorManager();
        $resource1       = $hydratorManager->resource();
        $resource1->addEmbeddedResource('user', $user);
        $result = $resource1->toArray();

        $this->assertEquals(1, $result['_embedded']['user']['id']);
    }

    public function testEmbeddedResources(): void
    {
        $hydratorManager = new HydratorManager();
        $resource        = $hydratorManager->resource();

        // Test all three possible types pushed to addEmbeddedResources
        $collection = new Collection();
        // Hydratable class
        $user        = new User();
        $user->id    = 1;
        $user->name  = 'Test';
        $user->email = 'test@testing.net';
        $collection->push($user);

        // Resource
        $user2        = new User();
        $user2->id    = 2;
        $user2->name  = 'Test 2';
        $user2->email = 'test2@testing.net';
        $userResource = $hydratorManager->extract($user2);
        $collection->push($userResource);

        // Raw Array
        $collection->push(['adhoc' => 'array']);

        $resource->addEmbeddedResources('resources', $collection);

        $this->assertEquals('Test', $resource->toArray()['_embedded']['resources'][0]['name']);
        $this->assertEquals('Test 2', $resource->toArray()['_embedded']['resources'][1]['name']);
        $this->assertEquals('array', $resource->toArray()['_embedded']['resources'][2]['adhoc']);
    }

    public function testEmbeddedResourcesWithCustomHydrator(): void
    {
        $hydratorManager = new HydratorManager();
        $resource        = $hydratorManager->resource();

        // Test all three possible types pushed to addEmbeddedResources
        $collection = new Collection();
        // Hydratable class
        $user        = new User();
        $user->id    = 1;
        $user->name  = 'Test';
        $user->email = 'test@testing.net';
        $collection->push($user);

        // Resource
        $user2        = new User();
        $user2->id    = 2;
        $user2->name  = 'Test 2';
        $user2->email = 'test2@testing.net';
        $userResource = $hydratorManager->extract($user2);
        $collection->push($userResource);

        $resource->addEmbeddedResources('resources', $collection, UserHydrator::class);

        $this->assertEquals('Test', $resource->toArray()['_embedded']['resources'][0]['name']);
        $this->assertEquals('Test 2', $resource->toArray()['_embedded']['resources'][1]['name']);
    }

    public function testToEmptyArray(): void
    {
        $hydratorManager = new HydratorManager();
        $resource1       = $hydratorManager->resource();

        $emptyArray = $resource1->toArray();

        $this->assertEquals([], $emptyArray);
    }
}
