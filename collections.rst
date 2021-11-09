Collecitons and Paginated HAL
=============================

Collections
-----------

You may assign a collection of resources to the ``_embedded`` section of any
resource.  And you can directly assign a collection of mapped [or unmapped!]
models to a resource as a collection

.. code:: php

  HALHydratorManager::resource()
    ->addEmbeddedResources('users', HALHydratorManager::extract($model->users))
Or

.. code:: php

  HALHydratorManager::resource()
    ->addEmbeddedResources('users', HALHydratorManager::extract($model->users, CustomHydrator::class))

Or

.. code:: php

  $userCollection = collect();

  foreach ($model->users as $user) {
      $userCollection->push(
          HALHydratorManager::extract($user)->toArray()
      );
  }

  return HALHydratorManager::resource()
      ->addEmbeddedResources('users', $userCollection);

Or

.. code:: php

  $userCollection = collect();

  foreach ($model->users as $user) {
      $userCollection->push(
          HALHydratorManager::extract($user, CustomHydrator::class)->toArray()
      );
  }

  return HALHydratorManager::resource()
      ->addEmbeddedResources('users', $userCollection)
      ;


Pagination
----------

HAL supports pagination using the ``_links`` section, ``_embedded`` section,
and includes pagination info in the state.  Automatic HAL pagination
is supported for the ``use Illuminate\Pagination\LengthAwarePaginator``
class.  This paginator is created from a controller::

    public function fetchAll(Request $request)
    {
        $data = DataModel::filtered()->sorted()->paginate(50);

        return HALHydratorManager::paginate('data', $data)->toArray();
    }

The above example uses the `searchable <https://github.com/jedrzej/searchable>`_
and `sortable <https://github.com/jedrzej/sortable>`_ libraries to turn an api
endpoint into a rich database queryable and sortable resources.  This
technique is strongly encouraged.
