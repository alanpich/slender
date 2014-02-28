---
title: Route Configuration
---

# Configuring HTTP Routes

You can configure http routes in your configuration files by specifying a
controller Class and action method to execute on each route.

**Note:** Route Middleware can't currently be configured in this way due
          to not being able to write closures in text files.
          See [Route Middleware](route-middleware.html) for more info



## Example route configurations



### YAML
```yaml

# NOTE: YAML uses '@' instead of ':' for specifying route parameters
#       This is to avoid conflicts with the yaml parser

routes:
  users:
    route: /users(/@id)
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