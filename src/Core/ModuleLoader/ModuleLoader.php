<?php
namespace Slender\Core\ModuleLoader;

use Slender\Exception\ModuleLoaderException;
use Slender\Interfaces\ModuleLoaderInterface;
use Slender\Interfaces\ModuleResolverInterface;

class ModuleLoader implements ModuleLoaderInterface
{
    /** @var  ModuleResolverInterface */
    protected $resolver;

    /** @var  \Slim\Configuration */
    protected $config;

    /** @var array  */
    protected $loadedModules = array();

    public function loadModule($module)
    {
        if(isset($this->loadedModules[$module])){
            return;
        }

        // Resolve module path
        $path = $this->resolver->getPath($module);

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
            $mConf['invoke'][] = $mConf['namespace'].'\\SlenderModule';
        }

        // Merge non-module config with app
        $this->config->setArray($conf);

        // Store module config
        $this->config->setArray(array(
               'module-config' => array(
                   $module => $mConf
               )
            ));


        // Register any autoloaders
        if(in_array('composer',$mConf['autoload'])){
            require $path.'/vendor/autoload.php';
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
}