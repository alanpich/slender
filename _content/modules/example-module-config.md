---
title: Module Config Files
---

# Module Config Files

Here are example module configuration files in all supported languages
- [PHP](#php)
- [YAML](#yaml)
- [JSON](#json)


## Examples


### YAML
```yaml
########################################
#  Example Module config file
#      ./module/my-module/slender.module.yml
########################################

# The module block contains settings
# for this particular module
module:

  # Module name - should be file-path & url safe
  name: my-module

  # Module namespace - not required, but advised
  namespace: MyVendor\MyModule

  # You can add any other data you want here and it will be available
  version: 1.0.0
  foo: bar

  # Classes to invoke when the module is loaded
  invoke:
    - MyVendor\MyModule\MyInvokableClass

  # Modules can register namespaces to be autoloaded
  # Supports PSR-0 and PSR-4 autoloading
  autoload:
    psr-4:
   # Would look for MyVendor/MyModule/MyClass in ./src/MyClass.php
      MyVendor/MyModule: ./src
```



### PHP
```php
<?php
/**
 * Example Module config file
 *     ./module/my-module/slender.module.php
 */
return array(

    // The module block contains settings
    // for this particular module
    'module' => array(

        // Module name - should be file-path & url safe
        'name' => 'my-module',

        // Module namespace - not required, but advised
        'namespace' => 'MyVendor\MyModule',

        // You can add any other data you want to the module here and it
        // will be available via Application settings
        'version' => '1.0.0',
        'foo' => 'bar',

        // Classes to invoke when the module is loaded
        'invoke' => array(
            'MyVendor\MyModule\MyInvokableClass'
        ),

        // Modules can register namespaces to be autoloaded
        // Supports PSR-0 and PSR-4 autoloading
        'autoload' => array(
            'psr-4' => array(
                // Would look for MyVendor/MyModule/MyClass
                // in ./src/MyClass.php
                'MyVendor\MyModule\\' => './src'
            ),
        ),
    );


);

```


### JSON
```javascript
// ./module/my-module/slender.module.json
{
    "module": {
        "name": "my-module",
        "namespace": "MyVendor\\MyModule",
        "version": "1.0.0",
        "foo": "bar",
        "invoke": [
            "MyVendor\MyModule\MyInvokableClass"
        ],
        "autoload": {
            "psr-4": {
                "MyVendor\\MyModule\\": ".\/src"
            }
        }
    }
}
```