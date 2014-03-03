---
title: Services & Factories
---


Slim, and Slender by inheritance, use Pimple as an IoC container. It is very powerful, but can be
cumbersome to configure. Slender ConfigLoader helps to automate this process by allowing service
and factory classes to be registered inside config files.


## Services
Services are singleton class instances that are shared across an
application. The are lazy-loaded; not created until the first time
they are asked for.

Registering services from config files is easy, simply add them to the
`services` block in the form $identifier=>$class

```yaml
...
services:
    MyService: MyVendor/MyNamespace/MyService
    OtherService: MyVendor/MyNamespace/OtherServiceFactory
```

Once a service is registered, you can retrieve it using the $app IoC container:

```php
/** @var $app Slender\App */
$myService = $app['MyService'];
$secondTime = $app['MyService'];
$myService === $secondTime; // TRUE
```

## Factories
Factories work in pretty much the same way as Services, with the exception that
each time a factory class is requested from the IoC container, a completely new
instance is returned.

Registering services from config files is easy, simply add them to the
`factories` block in the form $identifier=>$class

```yaml
...
factories:
    NewObject: MyVendor/MyNamespace/MyObject
```

Once a service is registered, you can retrieve it using the $app IoC container:
```php
/** @var $app Slender\App */
$myService = $app['NewObject'];
$secondTime = $app['NewObject'];
$myService === $secondTime; // FALSE
```


## Service Factories
If a registered Service or Factory class implements `Slender\FactoryInterface`, the
return value of the class' `create()` method will be used instead of the class itself.

This allows runtime configuration of service classes