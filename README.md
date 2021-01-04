Hypertext Application Language for Laravel
==========================================

[![Latest Stable Version](https://poser.pugx.org/api-skeletons/laravel-hal/v)](//packagist.org/packages/api-skeletons/laravel-hal) 
[![Total Downloads](https://poser.pugx.org/api-skeletons/laravel-hal/downloads)](//packagist.org/packages/api-skeletons/laravel-hal) 
[![Documentation Status](https://readthedocs.org/projects/api-skeletons-laravel-hal/badge/?version=latest)](https://api-skeletons-laravel-hal.readthedocs.io/en/latest/?badge=latest)
[![Latest Unstable Version](https://poser.pugx.org/api-skeletons/laravel-hal/v/unstable)](//packagist.org/packages/api-skeletons/laravel-hal) 
[![License](https://poser.pugx.org/api-skeletons/laravel-hal/license)](//packagist.org/packages/api-skeletons/laravel-hal)





HAL - Hypertext Application Language is a simple format which gives
a consistent and easy way to add HATEOAS to your API.

See the HAL Specification:
[http://stateless.co/hal_specification.html](http://stateless.co/hal_specification.html)


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
        "id":1,
        "name":"admin",
        "guard_name":"web",
      }
    ]
  }
}
```
