---
title: Introduction to the Core Services
---

In an effort to eat its own dogfood, the majority of core Slender
functionality is loaded via the public Module system. Some services,
however, are required before the ModuleLoader has been initialized and
so must be included in the Slender core.

These services are required for Slender to run, but you can override them
with your own classes as long as they implement the relevant interface.

## The Services


### Event Manager
`Slender\Interfaces\EventManagerInterface`

Extends core Slim event/hook management to add a few new features.


### File Parser
`Slender\Interfaces\FileParserInterface`

Responsible for all configuration file reading and parsing




## Extending Core Services

The *Core Services* provide basic functionality to Slender, like automated
route & service configuration and extended Event management. While you cannot
run Slender without them, you can replace them with your own implementation
as long as it implements the relevant service interface.

```php
Service Name    | ...MUST use interface
________________________________________________
event-manager   | Slender\Interfaces\CoreService\EventManagerInterface
module-loader   | Slender\Interfaces\CoreService\ModuleLoaderInterface
```