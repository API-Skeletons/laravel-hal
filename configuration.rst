Configuration
=============

Create a new directory to hold your hydrators and hydrator manager.  These docs
recommend ``~/app/Support/ERD`` and this path will be used throughout this
documentation.

Create a new class called ``HydratorManager``::

  <?php

  namespace App\Support\ERD;

  use ApiSkeletons\Laravel\HAL\HydratorManager as HALHydratorManager;

  final class HydratorManager extends HALHydratorManager
  {
      protected array $classHydrators = [
      ];
  }

Now create your first hydrator::

  <?php

  namespace App\Support\ERD\Hydrator;

  use ApiSkeletons\Laravel\HAL\Hydrator;
  use ApiSkeletons\Laravel\HAL\Resource;

  final class UserHydrator extends Hydrator
  {
      public function extract($class): Resource
      {
          $data = [];

          $fields = [
              'id',
              'name',
              'created_at',
              'updated_at',
          ];

          foreach ($fields as $field) {
              $data[$field] = $class->$field;
          }

          return $this->hydratorManager->resource($data)
              ->addLink('self', route('hal/user::fetch', $class->id));
      }
  }

There's a bit going on here.  First off we extend from the abstract Hydrator
which defines the extract() function and the return value of a Resource.

A simple but effective pattern is used to map the fields we want to the model's
properties and assign that to an array.  This is the act of extraction.  But because
this is a HAL hydrator we need to return a Resource.   So, using the hydrator
manager property of the abstract hydrator we assign the array of data and add
a self referential link.

Before we can use this hydrator we must assign it to a model to hydrate from
within the hydrator manager::

  protected array $classHydrators = [
    \App\Models\User::class => Hydrator\UserHydrator::class,
  ];

Having finished these steps we're now ready to return a HAL response for a
User model from a controller::

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
