---
title: Application Folder Structure
---

`Overview of what the different directories are for and how to change them`

The following tree represents the default Slender directory structure
```tree
├─ config/
│   # autoload config files from here
│   └ slender.yml
│
├─ data/
│    └─ cache/
│        └─ config/
│            # Generated configuration cache
│
├─ module/
│   # local modules stored here
│   └─ my-module/
│       └ slender.yml
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
├ composer.json

```