Specifying a Custom Hydrator For Extracting
===========================================

Dependency Injection
--------------------

All hydrators created through the HydratorManager are created using the
service manager built into the Application object of Laravel.

Wildcard Hydrator
-----------------

You may specify a default hydrator on the hydrator manager using '*'.  The
default will be used if an entity is encountered which does not have a custom
hydrator or a hydrator specifically assigned to it.  This eases the
configuration of the hydrator manager if your entities can be extracted this
way.

.. code:: php
  public function __construct()
  {
      $this->classHydrators = [
          '*' => \App\HAL\Hydrator\DefaultHydrator::class,
      ];
  }


Embedded Resources
------------------

For each of the `addEmbeddedResource` functions you may include a third parameter
to the hydrator to extract the resource with.  This is useful when using proxy objects
(such as Doctrine) or when you're using alternative hydrators such as a controller
action which returns data in a specific format, such as removing all the links from the
hydrator responses.

.. code:: php
  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName', $data['id']))
      ->addEmbeddedResources('example', $class->roles, CustomHydrator::class);

Pagination
----------

For the ``paginate`` function  you may include a third parameter to the hydrator
to extract the resource with. This is useful when using proxy objects
(such as Doctrine) or when you're using alternative hydrators.

.. code:: php
  return HALHydratorManager::paginate('data', $data, CustomHydrator::class)->toArray();


Pivot Data
----------

There are instances where a model may not exist for data such as *pivot* data.
There are very good reasons for including pivot data with a HAL response but
it has two big drawbacks:

1. It may not have a primary key or a URL it can use as a self referential
   link.
2. It probably does not have a model which can be associated with a hydrator
   in the hydrator manager.

For these types of scenarios there is still a way to hydrate the data to HAL
by using custom hydrators

.. code:: php

  $hal = HALHydratorManager::extract($pivot, PivotHydrator::class)->toArray();

The PivotHydrator does not need to be added to the hydrator manager but it
wouldn't hurt.  In the above code the $pivot data may be a `stdClass` or
a class not mapped in the hydrator manager, but you can still create a resource
from it by specifying the hydrator to use.

It is for cases like these that the ``self`` link is not a required datapoint.
