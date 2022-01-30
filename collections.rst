Collecitons and Paginated HAL
=============================

Collections
-----------

You may assign a collection of resources to the ``_embedded`` section of any
resource.  And you can directly assign a collection of mapped (or unmapped!)
models to a resource as a collection


Assign an array of objects to a resource
.. code:: php

  HALHydratorManager::resource()
    ->addEmbeddedResources('users', $model->users)

Assign an array of objects to a resource using a custom hydrator
.. code:: php

  HALHydratorManager::resource()
    ->addEmbeddedResources('users', $model->users, CustomHydrator::class)

Create a collection, push objects into it, and assign as embedded resources
.. code:: php

  $userCollection = collect();

  foreach ($model->users as $user) {
      $userCollection->push(
          HALHydratorManager::extract($user)->toArray()
      );
  }

  return HALHydratorManager::resource()
      ->addEmbeddedResources('users', $userCollection);

Extract resources into an arry using a custom hydrator and assign as
embedded resources
.. code:: php

  $userCollection = collect();

  foreach ($model->users as $user) {
      $userCollection->push(
          HALHydratorManager::extract($user, CustomHydrator::class)->toArray()
      );
  }

  return HALHydratorManager::resource()
      ->addEmbeddedResources('users', $userCollection);


Pagination
----------

HAL supports pagination using the ``_links`` section, ``_embedded`` section,
and includes pagination info in the state.  Automatic HAL pagination
is supported for the ``Illuminate\Pagination\LengthAwarePaginator``
class.  This paginator is created from a controller

.. code:: php

  public function fetchAll(Request $request)
  {
      $data = Model::filtered()->sorted()->paginate(50);

      return HALHydratorManager::paginate('data', $data)->toArray();
  }

.. code:: json
  {
    "_links":{
      "self":{
        "href": "http://website.com/data?page=1"
      },
      "first":{
        "href": "http://website.com/data?page=1"
      },
      "next":{
        "href": "http://website.com/data?page=2"
      }
      "last":{
        "href": "http://website.com/data?page=3"
      }
    },
    "_embedded":{
      "data":[{"_links":{"self":{"href": "http://website.com/data/1"…]
    },
    "page_count": 3,
    "page_size": 50,
    "total_items": 150,
    "page": 1
  }

The above example uses the `searchable <https://github.com/jedrzej/searchable>`_
and `sortable <https://github.com/jedrzej/sortable>`_ libraries to turn an api
endpoint into a rich database queryable and sortable resources.  This
technique is strongly encouraged.
