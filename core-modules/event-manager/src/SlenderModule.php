<?php
namespace Slender\Module\EventManager;

use Slender\Interfaces\ModulePathProviderInterface;

class SlenderModule implements ModulePathProviderInterface
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
}
