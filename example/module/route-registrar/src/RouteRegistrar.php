<?php
namespace Slender\Module\RouteRegistrar;

use Slim\Route;

class RouteRegistrar
{
    /** @var  \Slim\Router Router instance to register routes to */
    protected $router;

    public function addRoute($rConf)
    {
        $rConf = array_replace_recursive(array(
                'route' => null,
                'name' => null,
                'controller' => null,
                'action' => null,
                'methods' => array(),
                'conditions' => array()
            ),$rConf);


//        dump($rConf);

        // Prepare callback
        $handler = function(){
            die("HANDLER");
        };

        // Create Route
        $route = new Route($rConf['route'],$handler);

        $route->setName($rConf['name']);
        $route->setConditions($rConf['conditions']);
        $route->setHttpMethods($rConf['methods']);

        // Add to app
        $this->router->map($route);

    }


    /**
     * @param \Slim\Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return \Slim\Router
     */
    public function getRouter()
    {
        return $this->router;
    }


} 