Hypertext Application Language for Laravel
==========================================

This library is modeled off of Apigility written by Zend and myself.
The HAL component of Apigility in turn implements HAL according to the
`specification <http://stateless.co/hal_specification.html>`_.

This library is written exclusively for Laravel.  It implements the expected
*_links* and *_embedded* sections as well as pagination links for supported
collections.

Installation
------------

Installation of this module uses composer. For composer documentation, please
refer to `getcomposer.org <http://getcomposer.org/>`_.

``composer require api-skeletons/laravel-hal``

This library is a collection of classes and does not require a ServiceProvider
to be configured.

.. toctree::

  overview
  configuration
