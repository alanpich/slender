---
title: Modules
---

Modules help to break up an app into managable blocks of functionality or scope. Most frameworks support some sort of modularization of code, but many require a large amount of boilerplate code.

## Configuration
All modules MUST have a module.yml config file in their root directory. This is used to resolve the module paths, autoload classes and add configuration to the application. An example module config file

```yaml
# /modules/my-module/module.yml
module:
    name: my-module
    namespace: MyModule
    autoload:
        psr-4:
            MyModule: ./src

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

## Bootstrapping
Modules often need to listen to application lifecycle events etc..
When loading a module, Slender will look for a class called Slender
in the namespace defined in the module
