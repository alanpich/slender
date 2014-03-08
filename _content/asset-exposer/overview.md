---
title: Module Asset Exposer
---

#### `Slender\Module\AssetExposer`

<hr>

The AssetExposer module allows modules to expose assets
over http without the need to copy or otherwise link the files
to the main `./public/` directory.

Exposing assets is managed via a module config file, using the
`module>public>expose` block. This block accepts an array of
strings to use as Regular Expression patterns. An asset must
match one of these patterns before it will be served. This
means you can keep source files (sass,uncompressed js etc) in
the public folder without fear of it being available over http

```yaml
# ./module/my-module/slendery.yml
module:
  name: my-module
  ...
  public:
    expose:
      - \.js
      - \.css
```

## Future
I also have plans to roll some sort of optimization tool which would copy,
symlink, apache alias, apache rewrite or similar to somehow avoid needing
php to process the assets when in production
