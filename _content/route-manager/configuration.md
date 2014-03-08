---
title: Route Configuration
---

Slender core comes with the **route-manager** module which makes it easy to
set up http routes from within config files.

RouteManager routing is centered around the idea of **Controllers**
and **Actions** that you will probably recognise from other frameworks.

**Controllers** are classes that are invoked to handle requests,
and **actions** are the methods of the controller that should be executed to
respond to the request.

When defining the **controller** to use for a route, it can be either the
(FQCN) name of a class that should be instantiated, or the identifier of a
Factory or Service registered to the IoC container.

The **action** method will then be called on the controller instance

**Note:** Route Middleware can't currently be used with RouteRegistrar due
          to not being able to write closures in text files.


## Available properties

### `route :String`
The route property defines the url pattern to match
```yaml
routes:
  foo:
    ...
    route: /users/:id
```
### `controller :String`
Either the FQCN of a class, or the identifier of
a service in the DI container.
```yaml
routes:
  foo:
    ...
    controller: MyController
```

### `action :String`
Action to dispatch on controller. Usually the name
of a method on the controller class.
```yaml
routes:
  foo:
    ...
    action: getUser
```

### `methods :Array`
Array of HTTP methods this route should respond to. Also
accepts custom methods as per `Slim\Route::via()`.
If `ANY` is passed as a method, all methods will be listened for.
```yaml
routes:
  foo:
    ...
    methods:
      - GET
      - POST
```

### `conditions :Array`
Conditions are used to restrict route parameters to make
sure they match Regular Expression filters. For example, you
may wish to ensure the the `/users/:id` pattern only matches
if `:id` is an integer
```yaml
routes:
  foo:
    route: /users/:id
    ...
    conditions:
      id: [0-9]+
```


## Route Inheritance
Routes registered via the RouteManager service are inheritable. This means
that you can define child routes and have them inherit the properties of their
parents.

Route inheritence is described using the route name, or key, in dot notation.
Take the following example:
```yaml
routes:

  users:
    route: /users
    controller: UserController
    action: index

  users.user:
    route: /:id
    action: getUser
```
The `users.user` route seems a little light on the configuration, but this is
OK because it inherits other properties from the `users` route (see the
dot-notation in the route name?)

When the `users.user` route is registered, it's actual properties are like
this:
```yaml
routes:
  users.user:
    route: /users/:id
    controller: UserController
    action: getUser
```




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
