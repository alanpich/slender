<?php
namespace Slender\Module\Controllers;

use Slender\Interfaces\ModuleInvokableInterface;
use Slender\Interfaces\ModulePathProviderInterface;

class SlenderModule implements ModuleInvokableInterface,
    ModulePathProviderInterface
{

    public function invoke(\Slender\App &$app)
    {
        $app->hook(
            'route-registrar.resolve-controller-callable',
            function ($args) use ($app) {
                $class = $args['controller'];
                $action = $args['action'];
                if($app['util']->implementsInterface($class,'Slender\Module\Controllers\ControllerInterface')){
                    return function($args) use ($app,$class,$action) {
                        /** @var ControllerInterface $controller */
                        $controller = new $class;
                        $controller->setDiContainer($app);
                        return $controller->dispatchAction($action,$args);
                    };
                }
            }
        );

    }

    /**
     * Returns path to module root
     *
     * @return string Path
     */
    public static function getModulePath()
    {
        return dirname(__DIR__);
    }
}