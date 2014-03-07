<?php
namespace Slender\Core\DependencyInjector;

use Slender\Interfaces\FactoryInterface;

class Factory implements FactoryInterface
{
    public function create(\Slender\App $app)
    {
        return new DependencyInjector($app);
    }
}
