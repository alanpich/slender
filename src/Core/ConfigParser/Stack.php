<?php
namespace Slender\Core\ConfigParser;

use Slender\Exception\ConfigFileFormatException;
use Slender\Exception\ConfigFileNotFoundException;
use Slender\Interfaces\ConfigFileParserInterface;

class Stack implements ConfigFileParserInterface
{
    protected $parsers = array();

    function __construct($parsers)
    {
        foreach($parsers as $ext => $parser){
            if($parser instanceof ConfigFileParserInterface){
                $this->parsers[$ext] = $parser;
            }
        }
    }

    /**
     * Parse $path and return array of config from within
     *
     * @param string $path Path to file
     * @throws \Slender\Exception\ConfigFileFormatException when unable to parse file
     * @throws \Slender\Exception\ConfigFileNotFoundException when $path does not exist
     * @return array
     */
    public function parseFile($path)
    {
        // Check file exists
        if(!is_readable((string)$path)){
            throw new ConfigFileNotFoundException("$path does not exist, or is not readable");
        }
        $extension = pathinfo($path,PATHINFO_EXTENSION);

        if(isset($this->parsers[$extension])){
            return $this->parsers[$extension]->parseFile($path);
        }

        throw new ConfigFileFormatException("$extension is not a known config file format");
    }
}