<?php
namespace Slender\Core\Autoloader;

use Slender\Interfaces\FactoryInterface;

class PSR4Factory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $loader = new PSR4ClassLoader();
        $loader->register();

        return $loader;
    }
}
