<?php
namespace Slender\Module\RouteManager;

use Slender\Interfaces\FactoryInterface;

class Factory implements FactoryInterface
{

    public function create(\Slender\App $app)
    {
        $registrar = new RouteManager();

        // get the Slim\Router object
        $router = &$app['router'];
        $registrar->setRouter($router);
        $registrar->setApp($app);
        $registrar->setEventManager($app['event-manager']);
        $registrar->setDependencyInjector($app['dependency-injector']);


        return $registrar;
    }
}
