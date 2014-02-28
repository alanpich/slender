---
title: Route Configuration
---

# Configuring HTTP Routes

`How to set up routes in config files`


`haven't quite figured the format out yet`


```yaml
routes:
    home:
        route: /
        controller: IndexController
        action: index
    users:
        route: /users
          controller: UserController
        GET:
          action: index



```


## A bit nicer to read perhaps...


### YAML
```yaml

# NOTE: YAML uses '@' instead of ':' for specifying route parameters
#       This is to avoid conflicts with the yaml parser

routes:
  - route: /users/@id
    controller: UserController
    action: getUser
    methods:
      - GET
      - POST
      - PUT

  - route: /users
    controller: UserController
    action: index
    methods:
      - ANY

```


### PHP
```php
return array(
    'routes' => array(
        array(
            'route' => '/users/:id',
            'controller' => 'UserController',
            'action' => 'getUser',
            'methods' => array(
                'GET','POST','PUT'
            );
        ),

        array(
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
    "routes": [{
            "route": "/users/:id",
            "controller": "UserController",
            "action": "getUser",
            "methods": ["GET","POST","PUT"]
       },{
            "route": "/users",
            "controller": "UserController",
            "action": "index",
            "methods": ["ANY"]
       }]
}
```