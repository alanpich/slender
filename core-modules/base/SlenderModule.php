<?php
namespace Slender\Base;

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
        return dirname(__FILE__);
    }
}
