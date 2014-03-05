<?php
/**
 *
 */
namespace Slender\Core\ModuleLoader;

use Slender\Interfaces\FactoryInterface;

/**
 * Class Factory
 *
 * @package Slender\Core\ModuleLoader
 * @author Alan Pich <alan.pich@gmail.com>
 * @license MIT
 * @link http://alanpich.github.io/slender
 */
class Factory implements FactoryInterface
{
    /**
     *
     * @param \Slender\App $app Slender Application instance
     *
     * @return ModuleLoader
     *
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