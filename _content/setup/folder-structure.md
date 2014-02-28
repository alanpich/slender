---
title: Folder Structure
---
# Application Folder Structure

`Overview of what the different directories are for and how to change them`

The following tree represents the default Slender directory structure
```tree
├─ config/
│   # autoload config files from here
│   └ global.yml
│
├─ data/
│    └─ cache/
│        └─ config/
│            # Generated configuration cache
│            └ config.cache.php
│
├─ module/
│   # local modules stored here
│   └─ my-module/
│       └ slender.module.yml
│
├─ public/
│   └ index.php # main http gateway
│
├─ view/
│   # contains view templates
│   └ layout.twig
│
├─ vendor/
│   # composer dependencies
│   └ ...
│
│
│
├ composer.json
│
├
│
│
│
│

```