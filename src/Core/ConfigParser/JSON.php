<?php
namespace Slender\Core\ConfigParser;

use Slender\Interfaces\ConfigFileParserInterface;

/**
 * Class JSON
 *
 * Parses a JSON file and returns an array of data
 *
 * @package Slender\Core\ConfigParser
 */
class JSON implements ConfigFileParserInterface
{

    /**
     * Parse $path and return array of config from within
     *
     * @param  string $path Path to file
     * @return array
     */
    public function parseFile($path)
    {
        $content = file_get_contents($path);

        return json_decode($content);
    }
}
