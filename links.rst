Links
=====

HAL defines two specific structures:  ``_links`` and ``_embedded``. This
page discusses using links, self-referential links, related links, and
complex links. For pagination links, see `collections <collections.html>`_.

Links are URLs to URL resources (not to be confused with a HAL resource
class).  You may create any number of uniquely named links for each HAL
Resource.  The only strongly-suggested link is the ``self`` link.

Self Link
---------

.. code:: json

  "_links":{
    "self":{
      "href": "http://website.com/customer/1"
    }
  },

Every HAL resource should include a ``self`` link.  The ``self`` link gives
the URL to the current resource.  This is not just the current requested url:
in a collection of resources each resource ``self`` link is a link to that
individual resource.

Every hydrator resource should assign a ``self`` link.

.. code:: php

  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName', $class->id));

Related Link
------------

If you are extracting a class with embedded, related, or many-to-one data,
but you do not want to include the embedded class with the HAL response,
you may choose to include a link to the API data.  For an example consider a
User > Address one-to-many relationship.  When hydrating the User it is not
necessary to return the Address every time so just include a link to the data.
This example shows a many-to-one relationship with Company and one-to-many
with Address.

.. code:: php

  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName::fetch', $class->id))
      ->addLink('company', route('company::fetch', $class->company->id))
      ->addLink('addresses',
        route('address::fetchAll', [
          'filter' => ['user' => $class->getId()],
        ]));

Complex Link
------------

By default, when you add a new link, the link is added to the href property of
the link.  However the HAL specification allows for multiple properties and even
arrays of objects.  For this reason you may pass an array as a second parameter
to ``addLink``.  The array will be rendered exaclty as it was assigned.

.. note:: This is included for completeness and it is rare to use this feature.

.. code:: php

  ->addLink('ea:find', ['href' => '/orders{?id}', 'templated' => true]);

The special name ``curies``, see `curie syntax <https://www.w3.org/TR/2010/NOTE-curie-20101216/>`_
allows for arrays of link data::

  ->addLink('curies', [['name' => 'ea', 'href' => 'http://example.com/docs/rels/{rel}', 'templated' => true]]);

.. include:: footer.rst
