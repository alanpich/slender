<?php
namespace Slender\Module\RouteRegistrar;

use Slender\Interfaces\ModuleInvokableInterface;
use Slender\Interfaces\ModulePathProviderInterface;

class SlenderModule implements ModulePathProviderInterface,
                               ModuleInvokableInterface
{

    /**
     * Returns path to module root
     *
     * @return string Path
     */
    public static function getModulePath()
    {
        return dirname(__DIR__);
    }

    public function invoke( \Slender\App $app)
    {
        $routes = array();
        foreach($app['settings']['routes'] as $name => $r){
            if(is_array($r) && isset($r['route'])){
                $r['name'] = $name;
                $routes[] = $r;
            }
        }

        foreach($routes as $route){
            $app['route-registrar']->addRoute($route);
        }

    }
}