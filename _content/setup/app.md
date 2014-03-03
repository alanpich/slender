---
title: Application Initialization
---

Slender apps are created in exactly the same way as Slim applications.
The only difference is the existance of the extra configuration options.

```php

// This specific config is redundant, as all
// the values are Slender defaults, so you could just
// skip the config alltogether
$config = array(
    'config' => array(
        'autoload' => array('./config'),
        'cache' => './data/cache/config'
    ),
    'viewPaths' => array('./view'),
    'moduleDirs' => array('./module'),
);

$app = new Slender\App($config);
$app->run();
```