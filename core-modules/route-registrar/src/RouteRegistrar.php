<?php
namespace Slender\Module\RouteRegistrar;

use Slender\App;
use Slender\Interfaces\CoreModules\EventManagerInterface;
use Slim\Route;

class RouteRegistrar
{
    /** @var  \Slim\Router Router instance to register routes to */
    protected $router;

    /** @var  \Slender\App */
    protected $app;

    /** @var  EventManagerInterface */
    protected $eventManager;

    public function addRoute($rConf)
    {
        $rConf = array_replace_recursive(
            array(
                'route' => null,
                'name' => null,
                'controller' => null,
                'action' => null,
                'methods' => array('GET'),
                'conditions' => array()
            ),
            $rConf
        );

        // Prepare callback
        $controller = $rConf['controller'];
        $action = $rConf['action'];
        $handler = function () use ($controller,$action) {
            $this->handleRouteCallback($controller,$action,func_get_args());
        };

        // Create Route
        $route = $this->app->map($rConf['route'], $handler);

        $route->setName($rConf['name']);
        $route->setConditions($rConf['conditions']);
        call_user_func_array(array($route, 'setHttpMethods'), $rConf['methods']);

    }

    protected function handleRouteCallback($controller,$action, $args = array())
    {
        $app = $this->app;
        // Check if controller is registered to the IoC container
        if (isset($app[$controller])) {
            // Get the controller instance from DI Container
            $controller = $app[$controller];
            $args = func_get_args();
            $args = array_unshift($args, $app);
            call_user_func_array(array($controller, $action), $args);

        } else {
            // Pluggable controller invokers
            $stackResponse = $this->eventManager->triggerChain(
                'route-registrar.resolve-controller-callable',
                array(
                    'controller' => $controller,
                    'action' => $action
                )
            );
            if ($stackResponse !== null && is_callable($stackResponse)) {
                return $stackResponse($args);
            }

            // Fallback to nstantiate controller class ourselves
            return function () use ($app, $controller, $action) {
                $args = func_get_args();
                call_user_func_array(array($controller, $action), $args);
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
    public function setApp(App &$app)
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

    /**
     * @param \Slender\Interfaces\CoreModules\EventManagerInterface $eventManager
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return \Slender\Interfaces\CoreModules\EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

}
