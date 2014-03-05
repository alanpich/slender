<?php
namespace Slender\Module\RouteRegistrar;

use Slender\Interfaces\FactoryInterface;

class Factory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $registrar = new RouteRegistrar();

        // get the Slim\Router object
        $router = &$app['router'];
        $registrar->setRouter($router);
        $registrar->setApp($app);
        $registrar->setEventManager($app['event-manager']);

        return $registrar;
    }
}
