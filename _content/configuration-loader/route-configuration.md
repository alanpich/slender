---
title: Route Configuration
---

Slender core comes with the **route-registrar** module which makes it easy to
set up http routes from within config files.

RouteRegistrar routing if centered around the idea of **Controllers** and **Actions** that
you will probably recognise from other frameworks.

**Controllers** are classes that are invoked to handle requests, and **actions** are the methods
of the controller that should be executed to respond to the request.

When defining the **controller** to use for a route, it can be either the (FQCN) name of a class
that should be instantiated, or the identifier of a Factory or Service registered to the IoC
container.

The **action** method will then be called on the controller instance

**Note:** Route Middleware can't currently be used with RouteRegistrar due
          to not being able to write closures in text files.
          See [Route Middleware](route-middleware.html) for more info



## Example route configurations



### YAML
```yaml
routes:
  users:
    route: /users(/:id)
    controller: UserController
    action: getUser
    methods:
      - GET
      - POST
      - PUT

  logout:
    route: /logout
    controller: UserController
    action: index
    methods:
      - ANY

  archive.year:
    route: /archive/:year
    controller: ArchiveController
    action: getYear
    conditions:
        year: (19|20)\d\d

```


### PHP
```php
return array(
    'routes' => array(
        'users' => array(
            'route' => '/users/:id',
            'controller' => 'UserController',
            'action' => 'getUser',
            'methods' => array(
                'GET','POST','PUT'
            );
        ),

        'logout' => array(
            'route' => '/users',
            'controller' => 'UserController',
            'action' => 'index',
            'methods' => array('ANY'),
        ),
    );
);
```


### JSON
```javascript
{
    "routes": {
        "users": {
            "route": "/users/:id",
            "controller": "UserController",
            "action": "getUser",
            "methods": ["GET","POST","PUT"]
        },
       "logout": {
            "route": "/users",
            "controller": "UserController",
            "action": "index",
            "methods": ["ANY"]
       }
    }
}
```