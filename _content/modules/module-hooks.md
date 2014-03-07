---
title: Module Invokables
---

Modules can also register invokable classes, which are instanciated when
the module is loaded and passed the `Slender\App` instance in order to
perform additional logic on the app.

Invokable classes are called *after* Slim has been initialized, but *before* the
Slender constructor returns.

## Registering invokable classes

Module invokables are registered in the `invoke` section of the module
configuration file. It is an array of classes that should be instanciated
when the Module is loaded.

````yml
module:
    name: my-module
    namespace: MyVendor\MyModule
    autoload:
        psr-4:
            MyVendor\MyModule: ./src
    invoke:
        - MyVendor\MyModule\MyInvokableClass
```

If the module config also contains a `namespace` definition, or the module
was loaded via ClassLookup, Slender will automatically look
for a class of `NAMESPACE\SlenderModule` and try to invoke it.


## Invokable class signature

All classes registered as invokable MUST implement the following interface:
```php
interface Slender\InvokableInterface {
    public function invoke(\Slender\App $app);
}
```

An example invokable class that sets the app version setting from composer.json
```php
// ./module/my-module/src/SlenderModule.php
namespace MyVendor\MyModule;

use Slender\InvokableInterface;
use Slender\App;

class SlenderModule implements InvokableInterface {
    public function invoke(App $app){
        $composerData = json_decode(file_get_content('./package.json'));
        $version = $composerData['version'];
        $app->config('version',$version);

        $app['settings']['version'] === $version
    }
}
```

