<?php
namespace Slender\Core\ConfigParser;

use Slender\Interfaces\ConfigFileParserInterface;

class PHP implements ConfigFileParserInterface
{

    /**
     * Parse $path and return array of config from within
     *
     * @param string $path Path to file
     * @return array
     */
    public function parseFile($path)
    {
        return include($path);
    }
}