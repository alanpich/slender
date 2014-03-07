---
title: Service Manager
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
# Services are registered as Shared resources in the Slim DI container
# If the class registered implements Slender\FactoryInterface then
# the factory will be run to create the instance, otherwise the
# class listed will be initialized
services:
    MyService: MyModule\ServiceFactory

# Factories are registered as resources in the Slim DI container.
# A new instance is created each time this object is requested.
# If the class registered implements FactoryInterface, then the
# object returned will be the result of the factory method, not
# the factory class
factories:
    NewObject:  MyModule\Object
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
`factories` block in the form `$identifier => $class`

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
```php
namespace MyVendor\MyNamespace;

use Slender\Interfaces\FactoryInterface;

/**
 * Factory Class
 *
 * Creates a MyService instance and initializes
 * it using the application config.
 */
class MyServiceFactory
    implements FactoryInterface
{
    /**
     * Factory method
     *
     * @param Slender\App $app Slender App instance
     * @return mixed The initialized service
     */
    public function create( Slender\App $app )
    {
        $myService = new MyService();
        $myService->setRouter( $app['router'] );

        // This is what the DI container returns
        return $myService;
    }
}
```
```yaml
# ./config/slender.yml
...
services:
  ## Registering a service factory
  my-service: MyVendor\MyNamespace\MyServiceFactory
  # will return an instance of
  #
  #  MyVendor\MyModule\MyService
  #
  # as this is what is returned by the factory class
  #

```
