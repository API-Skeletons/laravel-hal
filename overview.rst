Overview
========

Hydrators
---------

Hydrators are not a common term in Laravel.
Hydrators are responsible for moving data into and out of an object.  For their
use in this library hydrators are used for extraction only and not hydration.

Hydrator Manager
----------------

This library works around a Hydrator Manager which has a configuration
of hydrators mapped to the classes they hydrate.  The hydrators are
individually composed classes thereby allowing any markup you desire, but it is
strongly recommended you stick to the HAL specification.

The hydrator manager is used to extract a class using a mapped hydrator.

Note it is possible to have two fully separate hydrator managers.

ApiSkeletons\Laravel\HAL\Resource
---------------------------------
As you extract data from classes using a hydrator you will be creating HAL
Resources.  A Resource object is the common object used to compose a HAL
response.  Resource objects hold the object data (the state), links, and
embedded data which can also be a Resource.  Collections of resources can
create an array as a response such as a paginated collection.

When you're ready to turn a resource into HAL JSON you will have a tree of
resources.

Abstract Classes
----------------

Because it is possible to have multiple hydrator managers an instance of the
hydrator manager is assigned to each hydrator and resource.  For this reason it
is necessary that classes involved in HAL in Laravel extend from certain
abstract classes.  This is a common pattern in Laravel services.

.. include:: footer.rst
