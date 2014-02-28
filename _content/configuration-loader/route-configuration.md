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