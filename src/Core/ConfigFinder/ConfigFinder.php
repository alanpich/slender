<?php
namespace Slender\Core\ConfigFinder;

use Slender\Interfaces\ConfigFileFinderInterface;
use Symfony\Component\Finder\Finder;

class ConfigFinder implements ConfigFileFinderInterface
{
    protected $paths = array();
    protected $files = array();

    function __construct(array $paths = array(),array $files = array())
    {
        $this->paths = $paths;
        $this->files = $files;
    }

    /**
     * Return an array of file paths to config files
     *
     * @return array of string paths
     */
    public function findFiles()
    {
        $search = (new Finder())->files();
        // Add in paths
        foreach($this->paths as $path){
            if(is_readable($path)){
                $search->in($path);
            }
        }
        // Add in names
        foreach($this->files as $pattern){
            $search->name($pattern);
        }

        $array = array();
        foreach($search as $f){
            $array[] = $f->getRealPath();
        }

        return $array;

    }
}