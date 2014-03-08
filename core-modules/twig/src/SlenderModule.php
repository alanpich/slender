<?php
namespace Slender\Module\Twig;

use Slender\Interfaces\ModuleInvokableInterface;
use Slender\Interfaces\ModulePathProviderInterface;

class SlenderModule implements ModulePathProviderInterface,
    ModuleInvokableInterface
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


    /**
     * Sets up the Twig environent and registers any extensions
     *
     * @param \Slender\App $app
     */
    public function invoke(\Slender\App &$app)
    {
        $twig = $app['twig'];
    }
}
