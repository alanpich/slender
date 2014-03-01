---
title: Module Path Resolver
---

# Module Path Resolver
The Module Path Resolver service is used internally to find the filesystem directory
that contains a Slender module, and returns the path to a module directory.

A resolver service MUST implement `Slender\Interfaces\ModuleResolverInterface`

By default, Slender uses a stack to allow chaining of multiple resolvers
when searching for modules. The default flow is as follows:

### Directory Resolver
Each path set in App `modulePaths` settings (`$basePath`) will be checked to see if it contains a suitable
directory. A 'suitable directory' is defined as one matching the following rules:
-   `$basePath/$moduleName` is a directory
-   `$basePath/$moduleName/slender.yml|php|json` exists and is readable

### Namespace Resolver
If all file paths are searched and the module is still not found, it is handed over to
the Namespace Resolver to track down. This resolver treats the module name as a PHP
namespace, and attempts to track the module down using a special class within the namespace.

The resolver checks for the existence of a class `NAMESPACE\SlenderModule`. If the class exists,
and implements `Slender\Core\ModulePathProviderInterface` then the corresponding `::getModulePath()`
static method is called and the return value used as module path.

This means that you could require another using only it's namespace, and letting the autoloader do
all of the work. Most of Slender's internal modules are loaded in this way after being included as
composer dependencies

```yaml
# ./config/slender.yml
...
modules:
  - MyVendor\MyNamespace
```
