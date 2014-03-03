---
title: Installation & Setup
---




## Overview
Slender is an extension of the excellent [Slim](http://slimframework.com) framework. It provides a few
additional utilities to assist with modular application development, while trying to
avoid some of the bloat & bootstrapping incurred with some larger frameworks like Zend or Symfony.


Slender extends Slim directly, and as such can be used in exactly the same way.

```php
$app = new Slender\App();
$app->get('/',function() use($app){
    echo 'HELLO WORLD';
}
$app->run();
```

## The Utilities

Broadly speaking, Slender currently provides 2 utilities to help with application development.

### Configuration Loader
The Configuration loader provide a mechanism for defining application config in arrays. Multiple configuration
files will automatically be merged together. Files can be written as PHP Arrays, YML or JSON.

The configuration loaders also provide several extra config settings to speed things up:

#### Service definitions
Services & Factories can be defined in config that will be automatically registered to the
Dependency Injection container.

#### Route definitions
HTTP Routes can also be defined in config, and will be automatically registered on application startup.


### Modules
Slender has a module system built in to make it easier to load additional functionality into your application
while keeping the codebase tidy and managable. The only requirement of a module is a config file, and can be
loaded by path (for local modules) or by namespace (for 3rd-party/composer-installed modules).
For more information on modules, see the [Modules](modules) section.

---

### Configuration Files
Slender applications are configured via one (or more) config files. Application-wide
configurations are defined in the  `config` directory. At present, all configs must
be in YAML format.

Each module also contains a config file that is merged (mostly, see below) with application configs before startup.

Configuration files are merged recursively, overwriting previous values where they exist. The order that config files are merged is as follows:

```
- [Module configs]
- /config/global.yml
- /config/*.global.yml
- /config/local.yml
- /config/*.local.yml
```


## Installation & Setup


### via Composer
```bash
$>  php composer.phar require slender/slender
```


### Skeleton Application `NOT IPLEMENTED YET`

Slender provides a skeleton application that can be installed via composer.

```bash
$> php composer.phar create-project slender/skeleton path/to/project
```


### Yeoman Generator `NOT IPLEMENTED YET`

Use a generator to skeleton your next app

```bash
# Creates a new app skeleton at path/to/project
$> yo slender:app path/to/project

# Creates a new modules skeleton at path/to/module
$> yo slender:module path/to/module
```

