<?php
/**
 * Created by PhpStorm.
 * User: alan
 * Date: 28/02/14
 * Time: 23:04
 */

namespace Slender\Interfaces;


interface ModuleResolverInterface {

    /**
     * Sets the Parser to use on config files
     *
     * @param ConfigFileParserInterface $parser
     * @return mixed
     */
    public function setConfigParser(ConfigFileParserInterface $parser);

    /**
     * Return path to module directory
     *
     * @param string $module Module name
     * @return string|false
     */
    public function getPath($module);

    /**
     * Return parsed config file for module
     *
     * @param $module
     * @return mixed
     */
    public function getConfig($module);
} 