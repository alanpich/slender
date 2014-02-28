---
title: Configuration Options
---

## Configuration Options



### config.autoload
An array of directory paths which should be scanned for config files on
startup. Files in these directories that match `config.files` will be
autoloaded and merged into the application config.
```php
$c['config']['autoload'] = array(
    '/path/to/configs',
    './alternative/path'
);
```


### config.files
An array of file names to match when searching for config files. Globbing
options are avaialable as supported by `symfony/finder`
```php
$c['config']['files'] = array(
    'global.*',
    '*.yml'
);
```


### config.cache
Path to directory in which to store a cached config file. Config caching
is disabled when in development mode. If the directory does not exist,
it will be created automatically.
```php
$c['config']['cache'] = './data/cache/config';
```





### viewPaths
Array of directories that contain view templates. This enables an application
and it's modules to have separate view directories.
```php
$c['viewPaths'] = array(
    './views'
);
```


### services
Array of singleton services to be added to the Slender IoC container. They should be
added in the form array( alias => class ), where alias is the alias registered in the
IoC container and class is the class to be instanciated.
If class is an instance of `Slender\FactoryInterface`, then the factories' `create` method
will be called and the return value used instead.
```php
$c['services'] = array(
    'view' => 'MyModule\View\TwigViewFactory'
);
```


### factories
Work in much the same way as services, but are not registered as singletons - each time
this object is requested from the IoC container, a different, new, instance is returned.
```php
$c['factories'] = array(
    'myObject' => 'MyModule\MyObject'
);
```


### modules
List of modules to require on application startup. Modules are covered in much more detail
[here](modules)
```php
$c['modules'] = array(
    'asset-exposer',
    'twig'
);
```


