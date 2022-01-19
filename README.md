# Hypertext Application Language for Laravel

[![Build Status](https://github.com/API-Skeletons/laravel-hal/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/API-Skeletons/laravel-hal/actions/workflows/continuous-integration.yml?query=branch%3Amain)
[![Code Coverage](https://codecov.io/gh/API-Skeletons/laravel-hal/branch/main/graphs/badge.svg)](https://codecov.io/gh/API-Skeletons/laravel-hal/branch/main)
[![Documentation Status](https://readthedocs.org/projects/api-skeletons-laravel-hal/badge/?version=latest)](https://api-skeletons-laravel-hal.readthedocs.io/en/latest/?badge=latest)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2b-blue)](https://img.shields.io/badge/PHP-7.3%20to%208.0%2b-blue)
[![Laravel Version](https://img.shields.io/badge/Laravel-8.x%2b-red)](https://img.shields.io/badge/Laravel-5.7%20to%208.x-red)
[![Total Downloads](https://poser.pugx.org/api-skeletons/laravel-hal/downloads)](//packagist.org/packages/api-skeletons/laravel-hal)
[![License](https://poser.pugx.org/api-skeletons/laravel-hal/license)](//packagist.org/packages/api-skeletons/laravel-hal)


HAL - Hypertext Application Language is a JSON dialect which gives
a consistent and easy way to add HATEOAS - Hypertext As The Engine
Of Application State - to your API.  This library makes
composing HAL responses easy including embedded data.

This library consists of a Hydrator Manager and you will write hydrators
for the classes you want to serve as HAL.  Central to this library is a 
Resource object on which HAL resources are attached.

Although this library is for Laravel, it is **not** specific to Eloquent.
This same library can be used with any datasource to compose a HAL response.

This is a direct implementation of [https://tools.ietf.org/html/draft-kelly-json-hal-08](https://tools.ietf.org/html/draft-kelly-json-hal-08)


### [Read The Documentation](https://api-skeletons-laravel-hal.readthedocs.io/en/latest/index.html)


## Quick Start

* Create a hydrator manager
* Create a hydrator for the User class
* Create a hydrator for the Role class
* Compose these into a HAL resource and return HAL from a controller action


### Create a hydrator manager

```php
namespace App\HAL;

use ApiSkeletons\Laravel\HAL\HydratorManager as HALHydratorManager;

final class HydratorManager extends HALHydratorManager
{
    public function __construct() 
    {
        $this->classHydrators = [
            \App\Models\Role::class => \App\HAL\Hydrator\RoleHydrator::class,
            \App\Models\User::class => \App\HAL\Hydrator\UserHydrator::class,
        ];
    }
```

### Create a hydrator for the User class

```php
namespace App\HAL\Hydrator;

use ApiSkeletons\Laravel\HAL\Hydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use App\Models\User;

final class UserHydrator extends Hydrator
{
    public function extract($class): Resource
    {
        $data = [];

        $fields = [
            'id',
            'name',
            'email',
        ];

        // Extract fields into an array to be used by the resource
        foreach ($fields as $field) {
            $data[$field] = $class->$field;
        }

        // Create a new resource and assign self link and extract the
        // roles into an embedded resource.  Note `addEmbeddedResources`
        // is used for arrays and `addEmbeddedResource` is used for classes
        return $this->hydratorManager->resource($data)
            ->addLink('self', route('hal/user::fetch', $class->id))
            ->addEmbeddedResources('roles', $this->hydratorManager->extract($class->roles))
            ;
    }
}
```

### Create a hydrator for the Role class

```php
namespace App\HAL\Hydrator;

use ApiSkeletons\Laravel\HAL\Hydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use App\Models\Role;

final class RoleHydrator extends Hydrator
{
    public function extract($class): Resource
    {
        $data = [];

        $fields = [
            'id',
            'name',
            'guard_name',
        ];

        // Extract fields into an array to be used by the resource
        foreach ($fields as $field) {
            $data[$field] = $class->$field;
        }

        // Create a new resource and assign self link and extract the
        // roles into an embedded resource.  Note `addEmbeddedResources`
        // is used for arrays and `addEmbeddedResource` is used for classes
        return $this->hydratorManager->resource($data)
            ->addLink('self', route('hal/role::fetch', $class->id))
            ;
    }
}
```

### Compose these into a HAL resource and return HAL from a controller action

```php
public function fetch(User $user, Request $request)
{
    $hydratorManager = new \App\HAL\HydratorManager();
    return $hydratorManager->extract($user)->toArray();
}
```

### HAL Response 

```json
{
  "_links":{
    "self":{
      "href":"https://apiskeletons.com/user/1"
    }
  },
  "id":1,
  "name":"Tom H Anderson",
  "email":"tom.h.anderson@gmail.com",
  "_embedded":{
    "roles":[
      {
        "_links":{
          "self":{
            "href":"https://apiskeletons.com/role/1"
          }
        },
        "id":1,
        "name":"admin",
        "guard_name":"web",
      }
    ]
  }
}
```
