---
title: Loading Modules
---

# Loading Modules

Importing Slender modules is easy, just create an array of required modules in your `global.yml` file:
```yaml
# ./config/global.yml
...
modules:
  - my-module
  - asset-exposer
```

## Module Dependencies
Modules can sometimes rely on other modules as dependencies. Module dependencies are loaded before
the dependant module automatically. To define module dependencies, add a `requires` array to your
module config file
```yaml
# ./module/my-module/slender.module.yml
module:
  name: my-module
  ...
  requires:
    - asset-exposer
```

`Overview of how a module is loaded, paths it can load from and namespace loading`