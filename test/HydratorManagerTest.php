<?php

declare(strict_types=1);

use ApiSkeletons\Laravel\HAL\Resource;
use ApiSkeletonsTest\Laravel\HAL\HydratorManager;
use ApiSkeletonsTest\Laravel\HAL\Model\User;
use PHPUnit\Framework\TestCase;

final class HydratorManagerTest extends TestCase
{
    public function testResourceCreate(): void 
    {
        $hydratorManager = new HydratorManager();
        $resource = $hydratorManager->resource();

        $this->assertInstanceOf(Resource::class, $resource);
    }

    public function testCanExtract(): void 
    {
        $hydratorManager = new HydratorManager();
        $user = new User();
        $invalid = new stdClass();

        $this->assertTrue($hydratorManager->canExtract($user));
        $this->assertFalse($hydratorManager->canExtract($invalid));
    }

    public function testExtract(): void 
    {
        $hydratorManager = new HydratorManager();
        $user = new User();
        $user->id = 1;  
        $user->name = 'Test';
        $user->email = 'testing@test.net';

        $resource = $hydratorManager->extract($user);
        $this->assertInstanceOf(Resource::class, $resource);

        $array = $resource->toArray();
        $this->assertEquals('1', $array['id']);
        $this->assertEquals('Test', $array['name']);
        $this->assertEquals('testing@test.net', $array['email']);
        $this->assertEquals('https://test/user/1', $array['_links']['self']['href']);
        
    }
}
