<?php
namespace Slender;

use Slender\Core\ModuleLoader\ModuleLoader;
use Slender\Core\ModuleResolver\DirectoryResolver;
use Slender\Core\ModuleResolver\NamespaceResolver;
use Slender\Core\ModuleResolver\ResolverStack;
use Slender\Core\ConfigParser;
use Slender\Interfaces\ConfigFileFinderInterface;
use Slender\Interfaces\ConfigFileParserInterface;
use Slender\Interfaces\ModuleInvokableInterface;
use Slender\Interfaces\ModuleLoaderInterface;
use Slender\Interfaces\FactoryInterface;
use Slender\Interfaces\ModuleResolverInterface;
use Slender\Core\Util\Util;

class App extends \Slim\App
{

    public function __construct(array $userSettings = array())
    {
        // Do the normal Slim construction
        parent::__construct($userSettings);

        $this->registerCoreServices();
        $this->loadConfigDefaults($userSettings);
        $this->loadApplicationConfigFiles($userSettings);


        /**
         * Load modules
         */
        foreach ($this['settings']['modules'] as $module) {
            $this['module-loader']->loadModule($module);
        }

        /**
         * Call module Invokables
         */
        $moduleConfigs = $this['settings']['module-config'];
        foreach ($moduleConfigs as $module => $mConf) {
            if (isset($mConf['invoke'])) {
                foreach ($mConf['invoke'] as $class) {
                    /** @var ModuleInvokableInterface $obj */
                    $obj = new $class;
                    $obj->invoke($this);
                }
            }
        }

    }

    /**
     * Load up config defaults
     *
     * This is a hardcoded config file within Slender core that sets
     * up sane default values for config
     *
     * @var ConfigFileParserInterface $parser
     */
    protected function loadConfigDefaults($userSettings)
    {
        $parser = $this['config-parser'];
        $defaults = $parser->parseFile(__DIR__ . '/slender.yml');
        $userSettings = array_merge_recursive($defaults, $userSettings);
        $this['settings']->setArray($userSettings);
    }

    /**
     * Load any application config files
     *
     * @var ConfigFileFinderInterface $loader
     */
    protected function loadApplicationConfigFiles($userSettings)
    {
        $parser = $this['config-parser'];
        $loader = $this['config-finder'];
        $userSettings = array();
        foreach ($loader->findFiles() as $path) {
            if (is_readable($path)) {
                $parsedFile = $parser->parseFile($path);
                if ($parsedFile !== false) {
                    $this->addConfig($parsedFile);
                }
            } else {
                echo "Invalid path $path\n";
            }
        }
    }

    /**
     * Register a Service to the DI container.
     * Services are singletons, and the same instance
     * is returned every time the identifier is requested
     *
     * @param string $service identifier
     * @param string $class   Class to create
     */
    public function registerService($service, $class)
    {
        $this[$service] = function ($app) use ($class) {
                $inst = new $class;
                if ($inst instanceof FactoryInterface) {
                    return $inst->create($app);
                } else {
                    return $inst;
                }
            };
    }

    /**
     * Register a Factory to the DI container.
     * Factories return a new instance of a class each
     * time they are called
     *
     * @param string $factory identifier
     * @param string $class   Class to create
     */
    public function registerFactory($factory, $class)
    {
        $this[$factory] = $this->factory(function ($app) use ($class) {
            $obj = new $class;
            if ($obj instanceof FactoryInterface) {
                return $obj->create($app);
            } else {
                return $obj;
            }
        });
    }

    /**
     * Recursively merge array of settings into app
     * config. This is needed because \Slim\ConfigurationHander doesn't
     * seem to like recursing...
     *
     * @param array $conf
     */
    public function addConfig(array $conf = array())
    {
        $appConfig =& $this['settings'];

        // Iterate through new top-level keys
        foreach ($conf as $key => $value) {

            // If doesnt exist yet, create it
            if (!isset($appConfig[$key])) {
                $appConfig[$key] = $value;
                continue;
            }

            // If it exists, and is already an array
            if (is_array($appConfig[$key])) {
                $mergedArray = array_merge_recursive($appConfig[$key], $value);
                $appConfig[$key] = $mergedArray;
                continue;
            }

            //@TODO check for iterators?

            // Just set the value already!
            $appConfig[$key] = $value;
        }
    }


    /**
     * Registers core services to the IoC Container
     */
    protected function registerCoreServices()
    {
        $this['util'] = function ($app) {
            return new \Slender\Core\Util\Util();
        };

        $this->registerService('dependency-injector','Slender\Core\DependencyInjector\Factory');
        $this->registerService('autoloader', 'Slender\Core\Autoloader\AutoloaderFactory');
        $this->registerService('autoloader.psr4', 'Slender\Core\Autoloader\PSR4Factory');




        /**
         * The configParser is used to translate various file
         * formats into PHP arrays
         *
         * @var ConfigFileParserInterface
         * @return \Slender\Core\ConfigParser\Stack
         */
        $this['config-parser'] = function () {
                return new ConfigParser\Stack(array(
                    'yml' => new ConfigParser\YAML,
                    'php' => new ConfigParser\PHP,
                    'json' => new ConfigParser\JSON,
                ));
            };


        /**
         * ConfigFinder is responsible for finding, loading and merging
         * configuration files
         *
         * @var ConfigFileFinderInterface
         * @return ConfigFileFinderInterface
         */
        $this['config-finder'] = function ($app) {
                $configLoader = new \Slender\Core\ConfigFinder\ConfigFinder(
                    $this['settings']['config']['autoload'],
                    $this['settings']['config']['files']
                );

                return $configLoader;
            };


        /**
         * ModuleResolver is used for tracking down a module's path
         * from it's name
         *
         * @var ModuleResolverInterface
         * @return \Slender\Core\ModuleResolver\ResolverStack
         */
        $this['module-resolver'] = function ($app) {
                $stack = new ResolverStack(new NamespaceResolver);
                $stack->setConfigParser($app['config-parser']);
                foreach ($this['settings']['modulePaths'] as $path) {
                    if (is_readable($path)) {
                        $stack->prependResolver(new DirectoryResolver($path));
                    }
                }

                return $stack;
            };

        /**
         * ModuleLoader is used to load modules & their dependencies,
         * registering services & routes etc along the way
         *
         * @var ModuleLoaderInterface
         * @param \Slender\App $app
         * @return ModuleLoaderInterface
         */
        $this->registerService('module-loader', 'Slender\Core\ModuleLoader\Factory');

    }

}
