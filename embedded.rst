Embedded Resources
==================

The HAL specification lays out an ``_embedded`` section optional for each
resource.  Instead of using embedded data in your HAL resource state, that kind
of data belongs in the ``_embedded`` section.

**THE WRONG WAY**::

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
  }

**THE RIGHT WAY**::

  {
    "name": "example",
    "_embedded": {
      "roles": [
        "guest",
        "user",
        "admin"
      ],
      "address": {
        "zipcode": "12345"
      }
    }
  }

Now you know.  The author hopes this lesson has not come too late for you.

Embedding Resources
-------------------

You may embed as many resources as you see fit.  Embedded resources should have
a relationship with the parent data.  Below a related class with its own
hydrator is assigned as a resource to the currently extracting class::

  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName', $data['id']))
      ->addEmbeddedResource('example', $this->hydratorManager->extract($class->example));

.. include:: footer.rst
