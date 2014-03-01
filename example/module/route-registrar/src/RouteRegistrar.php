<?php
namespace Slender\Module\RouteRegistrar;

use Slim\Route;

class RouteRegistrar
{
    /** @var  \Slim\Router Router instance to register routes to */
    protected $router;

    /** @var  \Slender\App */
    protected $app;

    public function addRoute($rConf)
    {
        $rConf = array_replace_recursive(array(
                'route' => null,
                'name' => null,
                'controller' => null,
                'action' => null,
                'methods' => array('GET'),
                'conditions' => array()
            ),$rConf);

        // Prepare callback
        $handler = $this->getCallableControllerAction($rConf['controller'],$rConf['action']);

        // Create Route
        $route = $this->app->map($rConf['route'],$handler);

        $route->setName($rConf['name']);
        $route->setConditions($rConf['conditions']);
        call_user_func_array(array($route,'setHttpMethods'),$rConf['methods']);

    }


    public function getCallableControllerAction($controller,$action)
    {
        $app = $this->app;
        // Check if controller is registered to the IoC container
        if(isset($app[$controller])){
            // Get the controller instance from DI Container
            return function() use ($app,$controller,$action){
                $controller = $app[$controller];
                call_user_func_array(array($controller,$action),func_get_args());
            };
        } else {
            // Instantiate controller class ourselves
            return function() use ($app,$controller,$action){
                $controller = new $controller;
                call_user_func_array(array($controller,$action),func_get_args());
            };
        }
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

    /**
     * @param \Slender\App $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }

    /**
     * @return \Slender\App
     */
    public function getApp()
    {
        return $this->app;
    }


} 