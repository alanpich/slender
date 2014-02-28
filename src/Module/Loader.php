<?php
namespace Slender\Module;

use Slender\Slender;
use Symfony\Component\Yaml\Yaml;

class Loader
{
    /** @var  \Slim\App */
    protected $slim;
    protected $searchPaths = array();
    protected $modulePaths = array();
    protected $moduleConfs = array();


    protected $conf = array(
        'resolved-modules' => array(),
    );

    /**
     * Resolve & load configs for an array of modules
     * Will also automatically load any module dependencies
     *
     * @param $modules
     */
    public function loadModules($modules)
    {
        foreach($modules as $module){
            $this->loadModule($module);
        }
    }

    public function loadModule($module)
    {
        // Grab module's config file
        $path = $this->resolveModulePath($module);
        $conf = $this->getModuleConfig($module);

        // Extract the module section
        $moduleConf = array_merge_recursive(array(
            'views' => array('./view')
        ),$conf['module']);
        unset($conf['module']);

        // Check for dependencies
        if(isset($moduleConf['requires'])){
            foreach($moduleConf['requires'] as $dep){
                $this->loadModule($dep);
            }
        }

        // Is there a view folder?
        if(isset($moduleConf['views'])){
            foreach($moduleConf['views'] as $viewDir){
                $this->conf['view-paths'][] = preg_replace("/^\\.\\//",$path.'/',$viewDir);
            }
        }

        // Merge with current config
        $this->conf = array_merge_recursive($this->conf,$conf);

        // Add to registered list
        $this->conf['resolved-modules'][$module] = $this->resolveModulePath($module);

        // Composer autoloading?
        if(isset($moduleConf['autoload']) && $moduleConf['autoload'] == 'composer'){
            $file = $this->resolveModulePath($module).'/vendor/autoload.php';
            if(is_readable($file)){
                include $file;
            }
        }

    }

    /**
     * Look up and parse module config file,
     * returning an array
     *
     * @param string $module Module name
     * @return array
     */
    public function getModuleConfig($module)
    {
        $path = $this->resolveModulePath($module);
        $file = $path.'/module.yml';
        $data = Yaml::parse(file_get_contents($file));
        return $data;
    }



    public function resolveModulePath($module)
    {
        if(!isset($this->modulePaths[$module])){
            $path = null;
            foreach($this->searchPaths as $root){
                $dir = $root.'/'.$module;
                if(is_readable($dir.'/module.yml')){
                    $path = $dir;
                    break;
                }
            }
            if(!$path){
                // Not found in module paths
                // - last chance is a namespace lookup
                $className = $module."\\Slender";
                if(class_exists($className)){
                    $ref = new \ReflectionClass($className);
                    $path = $ref->getFileName();
                } else {
                    throw new ModuleResolverException("Unable to resolve module $module");
                }
            }
            $this->modulePaths[$module] = $path;
        }
        return $this->modulePaths[$module];
    }

    public function getConfig()
    {
        return $this->conf;
    }


    /**
     * @param array $searchPaths
     */
    public function setSearchPaths($searchPaths)
    {
        $this->searchPaths = $searchPaths;
    }

    /**
     * @return array
     */
    public function getSearchPaths()
    {
        return $this->searchPaths;
    }


}