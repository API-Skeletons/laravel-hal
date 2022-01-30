Embedded Resources
==================

The HAL specification lays out an ``_embedded`` section that is optional for
each resource.  Instead of using embedded data in your HAL resource state,
that kind of data belongs in the ``_embedded`` section.

Embedding Resources
-------------------

You may embed as many resources as you see fit.  Embedded resources should have
a relationship with the parent data.  Below, a related class with its own
hydrator is assigned as a resource to the currently extracting class

.. code:: php

  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName', $class->id))
      ->addEmbeddedResource('example', $class->example);

You may embed an array of objects under a single resource:

.. code:: php

  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName', $data['id']))
      ->addEmbeddedResources('example', $class->roles);

**THE WRONG WAY**
.. code:: json

  {
    "name": "example",
    "roles": [
      "guest",
      "user",
      "admin"
    ],
    "address": {
      "zipcode": "12345"
    }

**THE HAL WAY**
.. code::json

  {
    "_links": {
      "self": {
        "href": "https://myapi/name/1"
      }
    }
    "name": "example",
    "_embedded": {
      "roles": [
          {
            "_links": {
              "self": {
                "href": "https://myapi/role/1"
              }
            },
            "id": 1,
            "roleId": "guest"
          },
          {
            "_links": {
              "self": {
                "href": "https://myapi/role/2"
              }
            },
            "id": 2,
            "roleId": "user"
          },
          {
            "_links": {
              "self": {
                "href": "https://myapi/role/3"
              }
            },
            "id": 3,
            "roleId": "admin"
          },
      ],
      "address": {
        "_links": {
          "self": {
            "href": "https://myapi/address/5"
          }
        },
        "id": 5,
        "zipcode": "12345"
      }
    }
  }

.. include:: footer.rst
