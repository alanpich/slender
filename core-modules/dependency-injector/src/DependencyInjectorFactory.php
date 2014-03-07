<?php
namespace Slender\Module\DependencyInjector;

use Slender\Interfaces\FactoryInterface;

class DependencyInjectorFactory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $injector = new DependencyInjector($app);

        return $injector;
    }
}
