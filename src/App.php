<?php
namespace Slender;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class App extends \Slim\App
{

    function __construct(array $userSettings = array())
    {
        // Load up config defaults
        $this->loadConfigFile(__DIR__ . '/slender.yml', $userSettings);


        // Search for and load any additional config files
        $userSettings = $this->getMergedConfig($userSettings);

        // Merge in module configs
        $moduleLoader = new Module\Loader;
        $moduleLoader->setSearchPaths($userSettings['module-directories']);
        $moduleLoader->loadModules($userSettings['modules']);
        $userSettings = array_merge_recursive($moduleLoader->getConfig(), $userSettings);

        // Do the normal Slim construction
        parent::__construct($userSettings);



        // Register DI services & factories
        $this->registerServices();

        // Register Routes
        $this->registerRoutes();

    }


    protected function getMergedConfig($conf)
    {
        $find = new Finder();
        $paths = $conf['config']['autoload'];
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $find->in($path);
            }
        }
        $find->name('slender.yml')
            ->name('global.*')
            ->name('*.global.*')
            ->name('local.*')
            ->name('*.local.*');

        $files = $find->files();
        foreach ($files as $f) {
            $this->loadConfigFile($f->getRealPath(), $conf);
        }

        return $conf;
    }


    public function loadConfigFile($path, &$target = null)
    {
        if (is_null($target)) {
            $target = $this['settings'];
        }
        if (is_array($path)) {
            $data = $path;
        } else {
            // Parse file
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data = array();
            switch ($ext) {
                case 'yml':
                    $data = (array)Yaml::parse(file_get_contents($path));
                    break;
            }
        }
        $target = array_merge_recursive($target, $data);
    }


    protected function registerServices()
    {
        $services = $this['settings']['services'];

        foreach ($services as $id => $class) {
            $this[$id] = $this->share(
                function ($c) use ($class) {
                    $instance = new $class;
                    if ($instance instanceof FactoryInterface) {
                        return $instance->create($this);
                    } else {
                        return $instance;
                    }
                }
            );
        }
    }


    public function registerRoutes()
    {

    }
}