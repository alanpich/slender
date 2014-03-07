---
title: Controllers
---

Controllers are responsible for handling http routes and rendering a
response. A controller, in its simplest form, is just a class with a
method matching the required action name.

```yaml
# ./config/slender.yml
...
routes:
  foo:
    route: /foo
    controller: FooController
    action: foobar
```
```php
// ./modules/foo/src/FooController.php
class FooController
{
    public function foobar()
    {
        die("/foo was requested!");
    }
}
```

## Controller classes
While Slender allows any instantiable class as a Controller, it also provides
an abstract class as a base for creating custom controllers.

The `Slender\Module\RouteManager\Controller\AbstractController` class sets up a
few helpful dependencies automatically and also allows you to use the
automatic dependency injection feature.

An example controller class:
```php
class MyController extends AbstractController
{

   /**
    * This is the action to be called
    * @return void
    */
    public function index()
    {
       /**
        * @var Slim\Http\Request $req The request object
        */
        $req = $this->request;

       /**
        * @var Slim\Http\Response $res The response object
        */
        $res = $this->response;

        /**
         * This is a sugar method for rendering a template
         * to the body of the response
         */
        $this->render('my-template',array(
            'foo' => 'bar'
        ));
    }

}
```



## Automatic Dependency Injection
Slender's AbstractController uses the DependencyInjector
module to automatically inject required dependencies into
your controller before dispatching the action.

Dependencies can be referenced in your class definition using
annotations on the injectable properties. Take the example below:
```php
use Slender\Core\DependencyInjector\Annotation as Slender;

class MyController extends AbstractController
{
   /**
    * @Slender\Inject
    *
    * Will call $obj->setRouteManager() and
    * pass it $app['route-manager']
    */
    protected $routeManager;

   /**
    * @Slender\Inject("event-manager")
    *
    * Will call $obj->setMyCustomNamedProperty() and
    * pass it $app['event-manager']
    */
    protected $myCustomNamedProperty;
}


```
