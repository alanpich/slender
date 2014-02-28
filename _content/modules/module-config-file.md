---
title: Module Config Files
---

# Module Config Files

`Describe module config file and available options`


## Declaring Dependencies

Modules can sometimes rely on other modules as dependencies. Module dependencies are
loaded before the dependant module automatically. To define module dependencies, add
a requires array to your module config file.

```yaml
# ./module/my-module/slender.module.yml
module:
  ...
  requires:
    - asset-exposer

```