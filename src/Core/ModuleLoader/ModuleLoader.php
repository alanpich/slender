<?php
/**
 * Slender - Slim, but with a bit more meat
 *
 * @author      Alan Pich <alan.pich@gmail.com>
 * @copyright   2014 Alan Pich
 * @link        http://alanpich.github.io/slender
 * @license     https://github.com/alanpich/slender/blob/develop/LICENSE
 * @version     0.0.0
 * @package     Slender
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Slender\Core\ModuleLoader;

use Slender\Core\Autoloader\ClassLoader;
use Slender\Exception\ModuleLoaderException;
use Slender\Interfaces\ModuleLoaderInterface;
use Slender\Interfaces\ModuleResolverInterface;

/**
 * Class ModuleLoader
 *
 * @package Slender\Core\ModuleLoader
 */
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

    /**
     * @param $module
     * @throws \Slender\Exception\ModuleLoaderException
     */
    public function loadModule($module)
    {
        if (isset($this->loadedModules[$module])) {
            return;
        }

        // Resolve module path
        $path = $this->resolver->getPath($module);

        if ($path === false) {
            throw new ModuleLoaderException("Unable to resolve path to $module");
        }

        // Get module config content
        $conf = array_merge_recursive(
            array(
                'module' => array(
                    'name' => null,
                    'version' => '0.0.0',
                    'author' => 'unknown',
                    'autoload' => [],
                    'invoke' => [],
                    'path' => $path,
                ),
            ),
            $this->resolver->getConfig($module)
        );

        // Extract module block from config
        $mConf = $conf['module'];
        unset($conf['module']);

        $this->loadedModules[$module] = true;

        // Check for any dependencies
        if (isset($mConf['require'])) {
            foreach ($mConf['require'] as $dependency) {
                $this->loadModule($dependency);
            }
        }

        // Check for an auto-invoke class
        // __NAMESPACE__\SlenderModule
        if (isset($mConf['namespace']) && class_exists($mConf['namespace'] . '\\SlenderModule')) {
            $class = $mConf['namespace'] . '\\SlenderModule';
            $reflector = new \ReflectionClass($class);
            $interfaces = $reflector->getInterfaceNames();
            if (!is_array($interfaces)) {
                $interfaces = array($interfaces);
            }
            if (in_array('Slender\Interfaces\ModuleInvokableInterface', $interfaces)) {
                $mConf['invoke'][] = $mConf['namespace'] . '\\SlenderModule';
            }
        }

        // Merge non-module config with app
        $this->addConfig($conf);

        // Store module config
        $this->addConfig(
            array(
                'module-config' => array(
                    $module => $mConf
                )
            )
        );

        $this->setupAutoloaders($path,$mConf);

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
     * Copy autoloader settings to global collection ready for registering
     *
     */
    protected function setupAutoloaders($modulePath, array $mConf)
    {
        if (isset($mConf['autoload']['psr-4'])) {
            foreach ($mConf['autoload']['psr-4'] as $ns => $path) {
                $path = $modulePath . DIRECTORY_SEPARATOR . preg_replace("/^\\.\\//", "", $path);
                $this->classLoader->registerNamespace($ns, $path, 'psr-4');
            }
        }
    }

    /**
     * @param ModuleResolverInterface $resolver
     */
    public function setResolver(ModuleResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param \Slim\Configuration $conf
     */
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
