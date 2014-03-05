<?php
/**
 * Copyright blah blah blah
 *
 * @category Slender\Core
 * @package  Slender\Core\ModuleLoader
 * @author   Alan Pich <alan.pich@gmail.com>
 * @license  MIT http://alanpich.github.io/slender
 * @link     http://alanpich.github.io/slender

 */
namespace Slender\Core\ModuleLoader;

use Slender\Interfaces\FactoryInterface;

/**
 * Class Factory
 *
 * @category Slender\Core
 * @package  Slender\Core\ModuleLoader
 */
class Factory implements FactoryInterface
{
    /**
     * Create a ModuleLoader instance
     *
     * @param \Slender\App $app Slender Application instance
     *
     * @return ModuleLoader
     */
    public function create(\Slender\App $app)
    {
        $loader = new ModuleLoader();
        $loader->setResolver($app['module-resolver']);
        $loader->setConfig($app['settings']);
        $loader->setClassLoader($app['autoloader']);

        return $loader;
    }
}
