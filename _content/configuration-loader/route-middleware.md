---
title: Route Middleware
---

Route Middleware can't currently be controller via config files due to not being able to write
closures in text files. Until a brainwave strikes, this page will hold ideas and concepts
for discussion.


### Idea #1 - Simplified API access
This could potentially be submitted to Slim directly as a PR
```php
$app->getRoute('users')->addMiddleware(function(){
    // Middleware function
})
```


### Idea #2 - Class based registration
```yaml
# config

routes:
    example:
        route: /foo
        ...
        middleware:
            - MyVendor\Middleware\MyMiddleware
```

```php
interface Slender\MiddlewareInterface {
    public static function run();
}

class MyMiddleware implements MiddlewareInterface {
    public function __run(){
        // Do stuff
    }
}
```