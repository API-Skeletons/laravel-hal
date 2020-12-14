Hypertext Application Language for Laravel
==========================================

HAL - Hypertext Application Language is a simple format which gives
a consistent and easy way to add HATEOAS to your API.

See the HAL Specification:
[http://stateless.co/hal_specification.html](http://stateless.co/hal_specification.html)


Install
-------

Installation of this module uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
$ composer require api-skeletons/laravel-hal ^1.0
```


Configuration
-------------

Extend `ApiSkeletons\Laravel\HAL\AbstractHydratorManager`
and add your models and hydrators to the `$classHydrators` array as such:
```php
protected $classHydrators = [
    App\Models\User::class => App\HAL\Hydrators\UserHydrator::class,
];
```

It is suggested you create a facade for your new HydratorManager class.  This 
doc will call it `HALHydratorManager`

Next you'll need to create your hydrators.  A hydrator moves data into and out of an
object.  In the case of HAL we'll just be moving data out of an object.  Each 
hydrator will correspond to a class (usually a model) and will return a 
`ApiSkeletons\Laravel\HAL\Resource` with the HAL data populated.  Here is an example:

```php
<?php

namespace App\HAL\Hydrators;

use DB;
use App\HAL\Resource;
use App\HAL\AbstractHydrator;
use Illuminate\Support\Collection;

class UserHydrator extends AbstractHydrator
{
    public function extract($class): Resource
    {
        $result = DB::select('
            SELECT roles.*
              FROM roles, model_has_roles
             WHERE role_id = roles.id
               AND model_type = ?
               AND model_id = ?
        ', ['App\User', $class->id]);

        $roles = Collection::make(array_map(function ($value) {
            return (array)$value;
        }, $result));

        // Always add self link
        $data = [
            '_links' => ['self' => ['href' => route('hal/me')]],
            'id' => $class->id,
            'name' => $class->name,
            'email' => $class->email,
            'created_at' => $class->created_at,
            'updated_at' => $class->updated_at,
        ];

        return $this->createResource($data)
            ->addEmbeddedResources('roles', $roles);
    }
}
```

Creating Links
--------------
Resource has a function `addLink($ref, $href)`  To add a new link to a 
resources `_links` array call this function with the ref/title such as
"self" and a URL such as the self referential link back to the current
HAL resource.


Embedding Resources
-------------------
You may add individual resources such as a blog to a comment.  Call
`addEmbeddedResource($ref, Resource $resource)` on the resource.  Pass
in the name of the embedded resource such as "blog" and a resource
with the class passed into it.  

You may add collections too.  When you create a collection of classes
you do not need to wrap your classes in Resource objects.  That will be
done automatically for you.


Use
---

With your hydrators in place you may return your HAL response using
```
return HALHydratorManager::extract($user)->toArray();
```


With Thanks
-----------

This work would not have been possible without an article written by
Giulio Troccoli-Allard: 
(Using HAL in content APIs)[https://troccoli.it/using-hal-in-content-api/]
