<?php

declare(strict_types=1);

namespace ApiSkeletonsTest\Laravel\HAL;

use ApiSkeletons\Laravel\HAL\Exception\NoHydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use ApiSkeletonsTest\Laravel\HAL\Hydrator\DiHydrator;
use ApiSkeletonsTest\Laravel\HAL\Hydrator\WildcardHydrator;
use ApiSkeletonsTest\Laravel\HAL\Model\User;
use Illuminate\Foundation\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use stdClass;

use function count;

final class HydratorManagerTest extends TestCase
{
    public function testResourceCreate(): void
    {
        $hydratorManager = new HydratorManager();
        $resource        = $hydratorManager->resource();

        $this->assertInstanceOf(Resource::class, $resource);
    }

    public function testCanExtract(): void
    {
        $hydratorManager = new HydratorManager();
        $user            = new User();
        $invalid         = new stdClass();

        $this->assertTrue($hydratorManager->canExtract($user));
        $this->assertFalse($hydratorManager->canExtract($invalid));
        $this->assertFalse($hydratorManager->canExtract('non-object'));
    }

    public function testExtract(): void
    {
        $hydratorManager = new HydratorManager();
        $user            = new User();
        $user->id        = 1;
        $user->name      = 'Test';
        $user->email     = 'testing@test.net';

        $resource = $hydratorManager->extract($user);
        $this->assertInstanceOf(Resource::class, $resource);

        $array = $resource->toArray();
        $this->assertEquals('1', $array['id']);
        $this->assertEquals('Test', $array['name']);
        $this->assertEquals('testing@test.net', $array['email']);
        $this->assertEquals('https://test/user/1', $array['_links']['self']['href']);
    }

    public function testDiExtract(): void
    {
        $hydratorManager = new HydratorManager();
        $user            = new User();

        $resource = $hydratorManager->extract($user, DiHydrator::class);
        $this->assertInstanceOf(Resource::class, $resource);

        $array = $resource->toArray();
        $this->assertEquals(Application::class, $array['app']);
    }

    public function testNoHydratorInExtract(): void
    {
        $this->expectException(NoHydrator::class);
        $this->expectExceptionMessage("No hydrator exists for class 'stdClass'");

        $hydratorManager = new HydratorManager();
        $invalid         = new stdClass();

        $hydratorManager->extract($invalid);
    }

    public function testWildcardHydrator(): void
    {
        $hydratorManager = new WildcardHydratorManager();
        $user            = new User();
        $user->id        = 1;
        $user->name      = 'Test';
        $user->email     = 'testing@test.net';

        $resource = $hydratorManager->extract($user);
        $this->assertInstanceOf(Resource::class, $resource);

        $array = $resource->toArray();
        $this->assertEquals(User::class, $array['class']);
    }

    public function testExtractCollection(): void
    {
        $hydratorManager = new HydratorManager();
        $user            = new User();
        $user->id        = 1;
        $user->name      = 'Test';
        $user->email     = 'testing@test.net';
        $user2           = new User();
        $user2->id       = 2;
        $user2->name     = 'Test 2';
        $user2->email    = 'testing2@test.net';
        $user3           = new User();
        $user3->id       = 3;
        $user3->name     = 'Test 3';
        $user3->email    = 'testing3@test.net';

        $collection = new Collection();
        $collection->push($user)->push($user2)->push($user3);

        $result = $hydratorManager->extract($collection);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(3, count($result));
    }

    public function testPagination(): void
    {
        $hydratorManager = new HydratorManager();
        $user            = new User();
        $user->id        = 1;
        $user->name      = 'Test';
        $user->email     = 'testing@test.net';
        $user2           = new User();
        $user2->id       = 2;
        $user2->name     = 'Test 2';
        $user2->email    = 'testing2@test.net';
        $user3           = new User();
        $user3->id       = 3;
        $user3->name     = 'Test 3';
        $user3->email    = 'testing3@test.net';
        $user4           = new User();
        $user4->id       = 4;
        $user4->name     = 'Test 4';
        $user4->email    = 'testing4@test.net';
        $user5           = new User();
        $user5->id       = 5;
        $user5->name     = 'Test 5';
        $user5->email    = 'testing5@test.net';
        $user6           = new User();
        $user6->id       = 6;
        $user6->name     = 'Test 6';
        $user6->email    = 'testing6@test.net';

        $collection = new Collection();
        $collection->push($user)->push($user2)->push($user3)->push($user4)->push($user5)->push($user6);

        $paginator = new LengthAwarePaginator(
            $collection->forPage($page = 2, $perPage = 2),
            $collection->count(),
            $perPage = 2,
            $page    = 2,
            ['path' => 'https://test/paginate']
        );

        $resource  = $hydratorManager->paginate('user', $paginator);
        $paginated = $resource->toArray();

        $this->assertEquals($paginated['page'], 2);
        $this->assertEquals($paginated['total_items'], 6);
        $this->assertEquals($paginated['page_count'], 3);
        $this->assertEquals($paginated['page_size'], 2);

        $this->assertEquals(count($paginated['_embedded']['user']), 2);
    }
}
