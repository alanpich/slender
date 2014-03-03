---
title: Route Registrar
---

`Slender\Module\RouteRegistrar`

***

The RouteRegistrar module is bundled within the Slender core and is responsible for
registering route definitions configured in application settings as actual HTTP
routes in the application.

Configuring routes via configuration files makes adding new pages and areas of the
application lightning-fast and very configurable


## Anatomy of a route
Routes are what links a request URL to the PHP functionality that should be executed
when a user visits it. When constructing routes using the RouteRegistrar service,
a route is comprised of the following fields:

#### Name
A unique identifier representing the route. This is used for accessing the route object
at a later time, and for nesting routes

#### Route
The URL pattern to match for this route, including any captured params.

#### Controller
The name of registered Service, or the FQCN of a class to handle the request

#### Action
The name of a method to call on the Controller class

#### Methods
An array of HTTP method verbs (all caps) that this route is applicable for. Accepts
any values that $slim->map()->via(XXX) would.

#### Conditions
Restrict route params by filtering them based on a Regular Expression


## Configuring a route

All routes are configured under the `routes` block inside a slender config file.
```yaml
# /config/slender.yml
...
routes:
  my-route-name:
    route: /my-route
    controller: MyController
    action: index
    methods:
      - GET
      - POST
```


## Nesting routes
Routes can be nested (emulating Slim's group() feature) to form groups of urls inheriting
from each other.

Take the following example
```yaml
# config/slender.yml
...
routes:
  users:
    route: /users
    controller: UserController
    action: index
    methods:
        - GET
        - POST
  users.user:
    route: /:id
    action: user
    methods:
        - GET
        - PUT
        - DELETE
```

See how the dot notation is used in the route name to imply that `users.user` is descended from
`users`? That's exactly how Slender sees it too! Controllers & action properties are inherited
from the parent so they don't need to be specified explicitely in the child route definition.

This means that in the config above, the following urls would be handled

```http
GET /users
    - Calls UserController::index()
POST /users
    - Calls UserController::index()
PUT /users
    - 404 Page Not Found

GET /users/123
    - Calls UserController::user()
POST /users/123
    - 404 Page Not Found
```


## Route Parameter Constraints
Sometime you might wish to restrict a route parameter to make sure it is of the correct type.
For instance, in the route `/users/:id` you may wish to ensure `$id` is an integer.

Route conditions allow you to specify Regular Expression patterns that must pass for a url
to satisfy a route definition. The example below requires the `$id` pattern be composed of
1 or more numbers and nothing else. THis means that `/users/123` will match, but `/users/fred`
or even `/users/12b` won't.

```yaml
# config/slender.yml
...
routes:
    user:
        route: /users/:id
        contraints:
            id: [0-9]+
```