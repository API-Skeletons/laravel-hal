Specifying a Custom Hydrator For Extracting
===========================================

There are instances where a model may not exist for data such as *pivot* data.
There are very good reasons for including pivot data with a HAL response but
it has two big drawbacks:

1. It may not have a primary key or a URL it can use as a self referential
   link.
2. It probably does not have a model which can be associated with a hydrator
   in the hydrator manager.

For these types of scenarios there is still a way to hydrate the data to HAL
by using custom hydrators::

  $hal = HALHydratorManager::extract($pivot, PivotHydrator::class)->toArray();

The PivotHydrator does not need to be added to the hydrator manager but it
wouldn't hurt.  In the above code the $pivot data may be a `stdClass` or
a class not mapped in the hydrator manager, but you can still create a resource
from it by specifying the hydrator to use.

It is for cases like these that the ``self`` link is not a required datapoint.
