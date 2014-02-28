---
title: Default Configuration
---
# Default configuration
This is the vanilla config used as defaults by Slender. This is how the configuration
looks before any application or module config files are loaded.

```

# Defaults to development mode
mode: development
debug: true


# Customize the ConfigurationLoader
config:

  # An array of directory paths which should be scanned for config files on startup
  autoload:
    - ./config

  # File names to match in autoload directories
  # Note: Uses Symfony\Finder::name()
  files:
    - global.*
    - local.*

  # Path to cache directory.
  # Note: Cache is automatically disabled in development mode
  cache: ./data/cache/config


# Array of paths to use as view template directories
viewPaths:
    - ./views



```