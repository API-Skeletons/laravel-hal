Hypertext Application Language for Laravel
==========================================

[![Latest Stable Version](https://poser.pugx.org/api-skeletons/laravel-hal/v)](//packagist.org/packages/api-skeletons/laravel-hal) 
[![Total Downloads](https://poser.pugx.org/api-skeletons/laravel-hal/downloads)](//packagist.org/packages/api-skeletons/laravel-hal) 
[![Documentation Status](https://readthedocs.org/projects/api-skeletons-laravel-hal/badge/?version=latest)](https://api-skeletons-laravel-hal.readthedocs.io/en/latest/?badge=latest)
[![Latest Unstable Version](https://poser.pugx.org/api-skeletons/laravel-hal/v/unstable)](//packagist.org/packages/api-skeletons/laravel-hal) 
[![License](https://poser.pugx.org/api-skeletons/laravel-hal/license)](//packagist.org/packages/api-skeletons/laravel-hal)


HAL - Hypertext Application Language is a JSON dialect which gives
a consistent and easy way to add HATEOAS - Hypertext As The Engine
Of Application State - to your API.  This library makes 
composing HAL responses easy including embedded data.

This is a direct implementation of `https://tools.ietf.org/html/draft-kelly-json-hal-08 <https://tools.ietf.org/html/draft-kelly-json-hal-08>`_


## [Read The Documentation](https://api-skeletons-laravel-hal.readthedocs.io/en/latest/index.html)


A brief output example

```json
{
  "_links":{
    "self":{
      "href":"https://apiskeletons.com/api/me"
    }
  },
  "id":1,
  "name":"Tom H Anderson",
  "email":"tom.h.anderson@gmail.com",
  "_embedded":{
    "roles":[
      {
        "_links":{
          "self":{
            "href":"https://apiskeletons.com/role/1"
          }
        },
        "id":1,
        "name":"admin",
        "guard_name":"web",
      }
    ]
  }
}
```
