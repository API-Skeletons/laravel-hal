Hypertext Application Language for Laravel
==========================================

[![Build Status](https://travis-ci.com/API-Skeletons/laravel-hal.svg?branch=master)](https://travis-ci.com/API-Skeletons/laravel-hal)
[![Coverage Status](https://coveralls.io/repos/github/API-Skeletons/laravel-hal/badge.svg?branch=master)](https://coveralls.io/github/API-Skeletons/laravel-hal?branch=master)
[![Documentation Status](https://readthedocs.org/projects/api-skeletons-laravel-hal/badge/?version=latest)](https://api-skeletons-laravel-hal.readthedocs.io/en/latest/?badge=latest)
[![PHP Version](https://img.shields.io/badge/PHP-7.3%20to%208.0-blue)](https://img.shields.io/badge/PHP-7.3%20to%208.0-blue)
[![Laravel Version](https://img.shields.io/badge/Laravel-5.7%20to%208.x-red)](https://img.shields.io/badge/Laravel-5.7%20to%208.x-red)
[![Total Downloads](https://poser.pugx.org/api-skeletons/laravel-hal/downloads)](//packagist.org/packages/api-skeletons/laravel-hal)
[![License](https://poser.pugx.org/api-skeletons/laravel-hal/license)](//packagist.org/packages/api-skeletons/laravel-hal)


HAL - Hypertext Application Language is a JSON dialect which gives
a consistent and easy way to add HATEOAS - Hypertext As The Engine
Of Application State - to your API.  This library makes
composing HAL responses easy including embedded data.

This is a direct implementation of [https://tools.ietf.org/html/draft-kelly-json-hal-08](https://tools.ietf.org/html/draft-kelly-json-hal-08)


## [Read The Documentation](https://api-skeletons-laravel-hal.readthedocs.io/en/latest/index.html)


A brief example

Create a hydrator manager

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

Create a hydrator for the User model

```php
namespace App\HAL\Hydrator;

use ApiSkeletons\Laravel\HAL\Hydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use App\Models\User;

final class UserHydrator extends Hydrator
{
    /**
     * The extract function will extract a model into a HAL Resource object
     */
    public function extract(User $user): Resource
    {
        $data = [];

        $fields = [
            'id',
            'name',
            'email',
        ];

        // Extract model fields into an array to be used as the resource
        foreach ($fields as $field) {
            $data[$field] = $user->$field;
        }

        // Create a new resource and assign self link
        return $this->hydratorManager->resource($data)
            ->addLink('self', route('hal/user::fetch', $user->id))
            // Add roles resource collection (RoleHydrator not shown)
            ->addEmbeddedResource('roles', $this->hydratorManager->extract($user->roles))
            ;
    }
}
```

Extract the User model from a controller

```php
public function fetch(User $user, Request $request)
{
    $hydratorManager = new \App\HAL\HydratorManager();
    return $hydratorManager->extract($user)->toArray();
}
```

And the Hypertext Application Language output will be

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
