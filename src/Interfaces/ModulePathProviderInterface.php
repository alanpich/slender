<?php
namespace Slender\Interfaces;

interface ModulePathProviderInterface
{
    /**
     * Returns path to module root
     *
     * @return string Path
     */
    public static function getModulePath();

}
