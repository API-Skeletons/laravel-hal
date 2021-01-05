Hypertext Application Language for Laravel
==========================================

This library is modeled off of HAL in Apigility written by Zend and myself.
Apigility in turn implements HAL according to the
`specification <https://tools.ietf.org/html/draft-kelly-json-hal-08>`_.

This library is written exclusively for Laravel.  It implements the expected
``_links`` and ``_embedded`` sections as well as pagination links for supported
collections.

Installation
------------

Installation of this module uses composer. For composer documentation, please
refer to `getcomposer.org <http://getcomposer.org/>`_.

``composer require api-skeletons/laravel-hal``

This library is a collection of classes and does not require a ServiceProvider
to be configured.

Versions
--------

For PHP 7.0 - 7.3 use version 1.0.  For PHP 7.4+ and Laravel 8.0 use the latest
version.

With Thanks
-----------

This work was inspired by an article written by
Giulio Troccoli-Allard:
`Using HAL in content APIs <https://troccoli.it/using-hal-in-content-api/>`_


.. toctree::

  overview
  configuration
  links
  embedded
  collections
  custom-hydrator
  manual-resource

.. include:: footer.rst
