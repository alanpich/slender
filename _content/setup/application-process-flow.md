---
title: Application Process Flow
---

This is an outline of the process flow that happens when
a `Slender\App` instance is created in the normal way.


### Application instance created
An instance of `Slender\App` is created, passing it the root
level config options such as where to find the base config file.

### Normal Slim constructor called
Slender calls the native `Slim\App::__construct` method to set
up the application object ready for use.

### Core Slender Services registered
Registers core services that are required for configuration
and module loading.

### Load configuration files
ConfigLoader service searches for and loads config files.
Each file that it loads are merged in order found into
the application settings

### Load Modules
ModuleLoader service resolves paths to all modules required
by the application, and once found merges their configurations
into the application settings

### Register Services & Factories
ServiceRegistrar is run to register any configured Services or
Factories.

### Register Routes
RouteRegistrar service is run to register any configured http routes.

### Invoke any module classes
Any invokable module classes are invoked, allowing the modules
access to the App object before it is run




