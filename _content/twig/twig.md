---
title: Twig Templating Engine
---

#### `Slender\Module\Twig`

<hr>

Ok, this ones nice and easy.
`@TODO Improve this doc for people with less Twig knowledge`

## Twig Environment
Twig environment auto-registers to the IoC container
so you can get at it anywhere. It is also pre-filled
with all view paths registered in the Slender config.
```php
$twig = $app['twig'];
```

## Setting Environment Options
You can configure the runtime options passed to the
`Twig_Environment` in your module or application
config files.
```yaml
twig:
  environment:
    debug: true
    charset: utf-8
    ...
```


## Twig Extensions
You can use the standard Twig API as normal to register
extensions, or you can also do it via config files
```yaml
twig:
  ...
  extensions:
    MyVendor\MyModule\MyTwigExtension
    ...
```


