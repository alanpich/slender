<?php
namespace Slender\Module\RouteManager;

use Slender\Core\Util\Util;
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

    public function invoke(\Slender\App &$app)
    {
        $routes = array();
        foreach ($app['settings']['routes'] as $name => $r) {
            if (is_array($r) && isset($r['route'])) {
                $group = null;
                $r['name'] = $name;

                array_walk($routes, function ($value) use (&$group, $name) {
                    $length = strlen($value['name']);
                    if (Util::stringStartsWith($name,$value['name'])) {
//                    if (substr($name, 0, $length) === $value['name']) {
                        $group = $value;
                    }
                });

                if (!empty($group)) {
                    $r['route'] = $group['route'] . $r['route'];
                    $r = array_merge($group, $r);
                }

                $routes[] = $r;
            }
        }

        foreach ($routes as $route) {
            $app['route-manager']->addRoute($route);
        }

    }
}
