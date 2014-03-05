<?php
namespace Slender\Module\Twig;

use Slender\Interfaces\ModulePathProviderInterface;

class SlenderModule implements ModulePathProviderInterface
{

    /**
     * Return the path to this module directory
     *
     * @return string
     */
    public static function getModulePath()
    {
        return dirname(__DIR__);
    }

//    public function invoke(\Slender\App $app)
//    {
//        // TODO: Implement invoke() method.
//    }
}
