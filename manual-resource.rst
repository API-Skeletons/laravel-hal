Manually Creating Resources
===========================

Most of the time resources are created within a hydrator.  But imagine you want
to compose a response which is a combination of unrelated data by including it
in the ``_embedded`` section.  Build this inside your controller

.. code:: php

  return HALHydratorManager::resource()
      ->addEmbeddedResource('model1', HALHydratorManager::extract($model1))
      ->addEmbeddedResources('model2', HALHydratorManager::extract($model2))
      ->addLink('self', $request->url())
      ->toArray();
