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

    public function invoke()
    {
        echo "<pre>RouteRegistrar invoked</pre>";
    }
}