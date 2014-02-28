<?php
namespace Slender\Module;

interface ModuleInterface
{

    /**
     * Return the absolute path to this module directory
     *
     * @return string
     */
    public static function getModulePath();


    /**
     * Register/bootstrap this module into Slim
     *
     * @param \Slim\App $app
     * @return mixed
     */
    public function register(\Slim\App $app);
} 