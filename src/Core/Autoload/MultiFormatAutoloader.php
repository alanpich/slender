<?php
namespace Slender\Core\Autoload;

use Slender\Exception\AutoloadException;
use Slender\Interfaces\ClassAutoloaderInterface;

class MultiFormatAutoloader
{
    /** @var ClassAutoloaderInterface  */
    protected $loaders = array();

    function __construct($loaders)
    {
        foreach($loaders as $key => $autoloader){
            if($autoloader instanceof ClassAutoloaderInterface){
                $autoloader->register();
                $this->loaders[$key] = $autoloader;
            }
        }
    }

    public function registerNamespace($format,$namespace,$path)
    {
        if(!isset($this->loaders[$format])){
            throw new AutoloadException("Unknown autoload protocol $format");
        }
        $loader = $this->loaders[$format];
        $loader->addNamespace($namespace,$path);
    }

} 