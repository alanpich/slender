<?php
namespace Slender\Config\Parser;

use Symfony\Component\Yaml\Yaml;

class YML implements ParserInterface
{

    public function parse($str)
    {
        return Yaml::parse($str);
    }
}