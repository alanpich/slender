<?php
namespace Slender;

use Symfony\Component\Finder\Finder;

class Slender
{
    protected $config_dirs = array();
    protected $module_dirs = array();

    public $config;
    protected $moduleLoader;

    /** @var  \Slim\App */
    protected $app;

    public function __construct($opts = array())
    {
        if(isset($opts['config_dirs'])){
            $this->config_dirs = array_merge($this->config_dirs,$opts['config_dirs']);
        }
        if(isset($opts['module_dirs'])){
            $this->module_dirs = array_merge($this->module_dirs,$opts['module_dirs']);
        }

        $this->config = new Config\Loader;
        $this->loadConfigFiles();

        // Setup the module loader
        $this->initModuleLoader();
    }



    public function getApp()
    {
        if(!$this->app){
            $this->createApp();
        }
        return $this->app;
    }


    public function loadConfigFile($path)
    {
        $this->config->loadFile($path);
    }

    protected function initModuleLoader()
    {
        $moduleConf = $this->config['modules'];
        $this->moduleLoader = new Module\Loader();
        $this->moduleLoader->setPaths($moduleConf['paths']);
        $this->moduleLoader->resolveConfigs($this->config);



        die('<pre>'.print_r($moduleConf,1));
    }


    protected function loadConfigFiles() {
        foreach($this->config_dirs as $dir){
            $find = new Finder();
            $dirFiles = $find->files()
                ->in($dir)
                ->name('global.*')
                ->name('*.global.*')
                ->name('local.*')
                ->name('*.local.*');
            foreach($dirFiles as $f){
                $this->loadConfigFile($f->getRealPath());
            }
        }

    }



    protected function createApp()
    {
        $app = $this->app = new \Slim\App($this->config->toArray());




    }

} 