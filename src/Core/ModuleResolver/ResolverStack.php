<?php
namespace Slender\Core\ModuleResolver;

use Slender\Interfaces\ConfigFileParserInterface;
use Slender\Interfaces\ModuleResolverInterface;

class ResolverStack implements ModuleResolverInterface
{
    /**
     * @var ModuleResolverInterface[]
     */
    protected $resolvers = array();

    /** @var  ConfigFileParserInterface */
    protected $parser;

    protected $cache = array(
        'paths' => array(),
        'confs' => array()
    );

    function __construct()
    {
        foreach(func_get_args() as $resolver){
            if($resolver instanceof ModuleResolverInterface){
                $this->addResolver($resolver);
            }
        }
    }

    /**
     * Sets the Parser to use on config files
     *
     * @param ConfigFileParserInterface $parser
     * @return mixed
     */
    public function setConfigParser(ConfigFileParserInterface $parser)
    {
        $this->parser = $parser;
    }


    /**
     * Add a Resolver to the end of the stack
     *
     * @param ModuleResolverInterface $resolver
     */
    public function addResolver(ModuleResolverInterface $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * Add a resolver to the beginning of the stack
     *
     * @param ModuleResolverInterface $resolver
     */
    public function prependResolver(ModuleResolverInterface $resolver)
    {
        array_unshift($this->resolvers,$resolver);
    }

    /**
     * Return the path to Module $module, or false
     * if not found
     *
     * @param string $module Module name or Namespace
     * @return string|false
     */
    public function getPath($module)
    {
        foreach($this->resolvers as $resolver){
            $path = $resolver->getPath($module);
            if($path !==false){
                return $path;
            }
        }
        return false;
    }


    /**
     * Return parsed config file for module
     *
     * @param $module
     * @return mixed
     */
    public function getConfig($module)
    {
        if(!isset($this->cache['paths'][$module])){
            $path = $this->getPath($module);
            $file = $path.DIRECTORY_SEPARATOR.'slender.yml';
            $parsed = $this->parser->parseFile($file);
            $parsed['path'] = $path;
            $this->cache['paths'][$module] = $parsed;
        }
        return $this->cache['paths'][$module];
    }
}