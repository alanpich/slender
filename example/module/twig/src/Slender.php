<?php
namespace Slender\Modules\Twig;

use Slender\Module\ModuleInterface;

class Slender implements ModuleInterface
{

    public function register(\Slim\App $app)
    {

    }

    /**
     * Return the absolute path to this module directory
     *
     * @return string
     */
    public static function getModulePath()
    {
        return dirname(__DIR__);
    }
}