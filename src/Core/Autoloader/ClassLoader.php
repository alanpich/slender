<?php
namespace Slender\Core\Autoloader;

use Slender\Exception\AutoloadException;
use Symfony\Component\ClassLoader\UniversalClassLoader;

class ClassLoader extends UniversalClassLoader
{

    /**
     * @var PSR4ClassLoader
     */
    protected $psr4Loader;

    /**
     * Overridden to allow injecting psr-4 into the mix.
     * Also opens up doors for alternative formats to be used
     * later. Either way, it's a mess here right now
     *
     * @param  string                               $namespace
     * @param  array|string                         $paths
     * @param  string                               $type      Either 'psr-0' or 'psr-4'. Defaults to 'psr-0'
     * @throws \Slender\Exception\AutoloadException
     */
    public function registerNamespace($namespace, $paths, $type = 'psr-0')
    {
        switch ($type) {
            case 'psr-0':
                parent::registerNamespace($namespace, $paths);
                break;
            case 'psr-4':
                $this->psr4Loader->addNamespace($namespace,$paths);
                break;
            default:
                throw new AutoloadException("$type is not a known autoloader format");
        }
    }

    /**
     * @param \Slender\Core\Autoloader\PSR4ClassLoader $psr4Loader
     */
    public function setPsr4Loader($psr4Loader)
    {
        $this->psr4Loader = $psr4Loader;
    }

    /**
     * @return \Slender\Core\Autoloader\PSR4ClassLoader
     */
    public function getPsr4Loader()
    {
        return $this->psr4Loader;
    }

}
