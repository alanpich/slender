<?php
namespace Slender\Core\ConfigParser;

use Slender\Interfaces\ConfigFileParserInterface;
use Symfony\Component\Yaml\Yaml as YamlParser;

class YAML implements ConfigFileParserInterface
{

    /**
     * Parse $path and return array of config from within
     *
     * @param string $path Path to file
     * @return array
     */
    public function parseFile($path)
    {
        $content = file_get_contents($path);
        return YamlParser::parse($path);
    }
}