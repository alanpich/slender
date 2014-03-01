<?php
namespace Slender\Core\ModuleLoader;

use Slender\Core\Autoloader\ClassLoader;
use Slender\Exception\ModuleLoaderException;
use Slender\Interfaces\ModuleLoaderInterface;
use Slender\Interfaces\ModuleResolverInterface;

class ModuleLoader implements ModuleLoaderInterface
{
    /** @var  ModuleResolverInterface */
    protected $resolver;

    /** @var  \Slim\Configuration */
    protected $config;

    /** @var ClassLoader */
    protected $classLoader;

    /** @var array */
    protected $loadedModules = array();

    public function loadModule($module)
    {
        if(isset($this->loadedModules[$module])){
            return;
        }

        // Resolve module path
        $path = $modulePath = $this->resolver->getPath($module);

        if($path === false){
            throw new ModuleLoaderException("Unable to resolve path to $module");
        }

        // Get module config content
        $conf = array_merge_recursive(array(
            'module' => array(
                'name' => null,
                'version' => '0.0.0',
                'author' => 'unknown',
                'autoload' => [],
                'invoke' => []
            ),
        ),$this->resolver->getConfig($module));

        // Extract module block from config
        $mConf = $conf['module'];
        unset($conf['module']);

        // Check for any dependencies
        if(isset($mConf['requires'])){
            foreach($mConf['requires'] as $dependency){
                $this->loadModule($dependency);
            }
        }

        // Check for an auto-invoke class
        // __NAMESPACE__\SlenderModule
        if(isset($mConf['namespace']) && class_exists($mConf['namespace'].'\\SlenderModule')){
            $class = $mConf['namespace'].'\\SlenderModule';
            $reflector = new \ReflectionClass($class);
            $interfaces = $reflector->getInterfaceNames();
            if(!is_array($interfaces)){
                $interface = array($interfaces);
            }
            if(in_array('Slender\Interfaces\ModuleInvokableInterface',$interfaces)){
                $mConf['invoke'][] = $mConf['namespace'].'\\SlenderModule';
            }
        }

        // Merge non-module config with app
        $this->addConfig($conf);

        // Store module config
        $this->addConfig(array(
               'module-config' => array(
                   $module => $mConf
               )
            ));

        /**
         * Copy autoloader settings to global collection ready for registering
         *
         */
        if($mConf === 'composer' || in_array('composer',$mConf['autoload'])){
            require $path.'/vendor/autoload.php';
        }

        if(isset($mConf['autoload']['psr-4'])){
            foreach($mConf['autoload']['psr-4'] as $ns => $path){
                $path = $modulePath.DIRECTORY_SEPARATOR.preg_replace("/^\.\//","",$path);
                $this->classLoader->registerNamespace($ns,$path,'psr-4');
            }
        }
        if(isset($mConf['autoload']['psr-0'])){
            foreach($mConf['autoload']['psr-0'] as $ns => $path){
                $path = $modulePath.DIRECTORY_SEPARATOR.preg_replace("/^\.\//","",$path);
                $this->classLoader->registerNamespace($ns,$path,'psr-4');
            }
        }


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
        $appConfig =& $this->config;

        // Iterate through new top-level keys
        foreach($conf as $key => $value){

            // If doesnt exist yet, create it
            if(!isset($appConfig[$key])){
                $appConfig[$key] = $value;
                continue;
            }

            // If it exists, and is already an array
            if(is_array($appConfig[$key])){
                $mergedArray = array_merge_recursive($appConfig[$key],$value);
                $appConfig[$key] = $mergedArray;
                continue;
            }

            //@TODO check for iterators?

            // Just set the value already!
            $appConfig[$key] = $value;
        }
    }



    public function setResolver(ModuleResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function setConfig(\Slim\Configuration $conf)
    {
        $this->config = $conf;
    }

    /**
     * @param mixed $classLoader
     */
    public function setClassLoader($classLoader)
    {
        $this->classLoader = $classLoader;
    }

    /**
     * @return mixed
     */
    public function getClassLoader()
    {
        return $this->classLoader;
    }



}