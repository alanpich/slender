<?php

namespace Slender\Core\Autoloader;

use Slender\Interfaces\FactoryInterface;


class AutoloaderFactory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $loader = new ClassLoader();
//        $loader->setUseIncludePath(true);
        $loader->register();
        $loader->setPsr4Loader($app['autoloader.psr4']);
        return $loader;
    }
}