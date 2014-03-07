---
title: Automatic Dependency Injection
---

Slender's DependencyInjector service is a tool for
automagically injecting dependencies into your classes.

It works using docblock annotations (powered by the `Doctrie\Annotations`
package).


## Annotating classes for automatic injection
Marking class properties for auto-injection is easy. Simply add a docblock
comment to the property definition to mark it for injection. The required
docblock annotation is `@Slender\Core\DependencyInjector\Annotation\Inject`
although you can shorten this by importing the class with a `use` statement
(see example below)

```php
use Slender\Core\DependencyInjector\Annotation as Slender;

class MyClass
{
   /**
    * @var RouteManager
    * @Slender\Inject
    */
    protected $routeManager;

   /**
    * @var EventManager
    * @Slender\Inject("event-manager")
    */
    protected $myCustomNamedProperty;
}
```

## Dependency name resolution
When trying to inject class dependencies, the dependencyInjector tries to
automate things for you as much as possible by converting property names
to DI container identifiers on the fly.

### Canonical Usage
When used without a value, the Inject annotation will try to convert the
name of the property to a DI container identifier. Property names
are converted to service identifiers using `Slender\Util\Util::hyphenCase()`

Therefore, the annotation below is asking for the service `route-manager`.
```php
use Slender\Core\DependencyInjector\Annotation as Slender;
class MyClass
{
   /**
    * @var RouteManager
    * @Slender\Inject
    */
    protected $routeManager;
    ...
}
```

### Explicit Usage
If your property name does not match the name of the service you require (it
happens), you can explicitly define the service identifier to load by providing
a value to the annotation.

Therefore, the annotation below is asking for the service `event-manager`.
```php
use Slender\Core\DependencyInjector\Annotation as Slender;
class MyClass
{
   /**
    * @var EventManager
    * @Slender\Inject("event-manager")
    */
    protected $eventManager;
    ...
```

## Private & Protected properties
By default, the DependencyInjector will try to set property values directly
(`$myInstance->routeManager = $routeManager`)

If you want to protect your property access, you can also provide setter
methods for the properties you want to inject. If the DependencyInjector
cannot write to the property directly (if it is protected or private) then
it will look for a setter method instead.

To make the above example $routeManager property private,
you would make to following changes:
```php
use Slender\Core\DependencyInjector\Annotation as Slender;
class MyClass {

   /**
    * @var RouteManager
    * @Slender\Inject
    */
    private $routeManager;

    /**
     * Publicly accessible setter method to allow
     * injecting private/protected properties
     */
    public function setRouteManager($rm)
    {
        $this->routeManager = $rm;
    }

}

```

## Using the DependencyInjector
The dependency injector is used in various core services and modules, but you
can also use it yourself when creating classes. The easiest way to do this is
 as follows:
```php
$myInstance = new MyClass();
$app['dependency-injector']->prepare($myInstance);
// $myInstance now has its routeManager and
// myCustomNamedProperty properties set
```
