---
title: Modules
---

Modules help to break up an app into managable blocks of functionality or
scope. Most frameworks support some sort of modularization of code,
but many require a large amount of boilerplate code.

Slender aims to made modularization of code as easy as possible. All
that is required for a module is a directory to call it's own, and a
`slender.yml` config file.

## Configuration
All modules MUST have a module.yml config file in their root directory.
This is used to resolve the module paths, autoload classes and add
configuration to the application. An example module config file.

```yaml
# /modules/my-module/slender.yml
module:
  name: my-module
  namespace: MyVendor\\MyModule
  autoload:
    psr-4:
      MyModule: ./src
```

## Extending application settings
A modules's slender.yml config file can also contain other settings,
outside the `module` block. This can be used to overload or merge
with existing application configuration, such as adding additional
services to register, or defining a new http route.
```yaml
module:
  name: my-module
  ...

##
# Any values placed outside the module block
# will automatically be merged with the application
# config at the time the module is loaded
#
services:
  foo-service: MyVendor\MyModule\FooServiceFactory
```

## Bootstrapping
Modules often need to listen to application lifecycle events etc..
When loading a module, Slender will look for a class called SlenderModule
in the namespace defined in the module and try to load it:
```php
// Vague pseudocode, but you get the idea...
$class = $module->namespace . "\SlenderModule
if(class_exists($class)){
    if($class instanceof Slender\Interfaces\ModuleInvokableInterface){
        $bootstrap = new $class();
        $bootstrap->invoke( Slender\App $app );
    }
}
```

You can also configure additional classes to be invoked on application
startup by adding them to the `invoke` block of a module config file.
```yaml
# /modules/my-module/slender.yml
module:
  namespace: MyVendor\MyModule
  ...
  ##
  # Array of classes to invoke on startup. Must implement
  #
  #    Slender\Interfaces\ModuleInvokableInterface
  #
  # If class __MODULE_NAMSPACE__\SlenderModule exists, and
  # also implements the interface, then this class is
  # prepended to the invoke array
  #
  invoke:
    # MyVendor\MyModule\SlenderModule  is added automatically
    - MyVendor\MyModule\SomeOtherInvokable

```



## Interacting with Slender

When Slender invokes a class implementing `Slender\Interfaces\ModuleInvokableInterface`
it passes the `Slender\App` instance so the invokable has full access to both Slender
and the underlying Slim API. This might be handy for people migrating Slim code to Slender.

```php
class MyClass implements Slender\Interfaces\ModuleInvokableInterface
{
    public function invoke(Slender\App $app){

        // You can dig straight into the Slim API
        $app->get('/foo/:bar',function($bar) {
            die( $bar );
        });

        // Or use a service from the DI container
        $foo_cache_value = $app['cache']->get('foo');

        // Or hook a Slim runtime event
        $app->hook('slim.before.dispatch',function(){
            die("Died before dispatch");
        });

    }
}

```
