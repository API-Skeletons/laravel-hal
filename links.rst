Links
=====

HAL defines two specific structures:  ``_links`` and ``_embedded``.  This
document discusses using links, self referential links, related links, and
complex links. For pagination links see `collections <collections.html>`_.

Links are URLs to URL resources (not to be confused with a HAL resourcee
class).  You may create any number of uniquely named links for each HAL
Resource.  The only strongly-suggested link is the ``self`` link.

Self Link
---------

Every HAL resource should include a ``self`` link.  The self link reflects
the URL to the current resource.  This is not just the current requested url:
in a collection of resources each resource ``self`` link is a link to that
individual resource.

Every hydrator should assign a ``self`` link

.. code:: php

  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName', $class->id));

Related Link
------------

If you are extracting a class with embedded / related data but you do not want
to include the embedded class with the HAL response you may choose to just
include a link to the API data instead.  For an example consider a
User > Address 1:1 relationship.  When hydrating the User it is not necessary
to return the Address every time so just include a link to the data

.. code:: php

  return $this->hydratorManager->resource($data)
      ->addLink('self', route('routeName', $class->id))
      ->addLink('address', route('addressRoute', $class->address->id));

Complex Link
------------

By default, when you add a new link, the link is added to the href property of
the link.  However the HAL specification allows for multiple properties and even
arrays of objects.  For this reason you may pass an array as a second parameter
to ``addLink``.  The array will be rendered exaclty as it was assigned

.. code:: php

  ->addLink('ea:find', ['href' => '/orders{?id}', 'templated' => true]);

The special name ``curies``, see `curie syntax <https://www.w3.org/TR/2010/NOTE-curie-20101216/>`_
allows for arrays of link data::

  ->addLink('curies', [['name' => 'ea', 'href' => 'http://example.com/docs/rels/{rel}', 'templated' => true]]);

.. include:: footer.rst
