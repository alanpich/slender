<?php
namespace Slender\Core\ModuleLoader;

use Slender\Interfaces\FactoryInterface;

class Factory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $loader = new ModuleLoader();
        $loader->setResolver($app['module-resolver']);
        $loader->setConfig($app['settings']);
        $loader->setClassLoader($app['autoloader']);
        return $loader;
    }
}