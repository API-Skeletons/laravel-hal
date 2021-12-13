Configuration
=============

Create a new directory to hold your hydrators and hydrator manager.  These docs
recommend ``~/app/HAL`` and this path will be used throughout this
documentation.

Create a new class called ``HydratorManager``

.. code:: php

  namespace App\HAL;

  use ApiSkeletons\Laravel\HAL\HydratorManager as HALHydratorManager;

  final class HydratorManager extends HALHydratorManager
  {
  }

Now create your first hydrator

.. code:: php

  namespace App\HAL\Hydrator;

  use ApiSkeletons\Laravel\HAL\Hydrator;
  use ApiSkeletons\Laravel\HAL\Resource;

  final class UserHydrator extends Hydrator
  {
      /**
       * The extract function will extract a model into a HAL Resource object
       */
      public function extract($class): Resource
      {
          $data = [];

          $fields = [
              'id',
              'name',
              'created_at',
              'updated_at',
          ];

          // Extract model fields into an array to be used as the resource
          foreach ($fields as $field) {
              $data[$field] = $class->$field;
          }

          // Create a new resource and assign self link
          return $this->hydratorManager->resource($data)
              ->addLink('self', route('hal/user::fetch', $class->id));
      }
  }

There's a bit going on here.  First, we extend from the abstract Hydrator
which defines the extract() function and the return value of a Resource.

A simple but effective pattern is used to map the fields we want to the model's
properties and assign that to an array.  This is the act of extraction.  But
because this is a HAL hydrator we need to return a Resource.   So, using the
hydrator manager property of the abstract hydrator we assign the array of data
and add a self referential link.

Before we can use this hydrator we must assign it to a model to hydrate from
within the hydrator manager

.. code:: php

  public function __construct() 
  {
      $this->classHydrators = [
          \App\Models\User::class => \App\HAL\Hydrator\UserHydrator::class,
      ];
  }

Having finished these steps, next return a HAL response for a
User model from a controller

.. code:: php

  public function fetch(User $user, Request $request)
  {
      $hydratorManger = new HydratorManager();
      return $hydratorManager->extract($user)->toArray();
  }

The experienced developer will take one look at this and say, "Why aren't you
using a facade?"  And you're right.  It is recommended you use a facade for
your hydrator manager.  The rest of this documentation will assume the use of
a ``HALHydratorManager`` facade for the hydrator manager.

.. include:: footer.rst
