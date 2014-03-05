<?php
namespace Slender\Core\ModuleResolver;

use Slender\Interfaces\ConfigFileParserInterface;
use Slender\Interfaces\ModuleResolverInterface;

abstract class AbstractResolver implements ModuleResolverInterface
{
    /**
     * @var ConfigFileParserInterface
     */
    protected $parser;

    protected $cache = array(
            'paths' => array(),
            'configs' => array()
        );

    /**
     * Sets the Parser to use on config files
     *
     * @param  ConfigFileParserInterface $parser
     * @return mixed
     */
    public function setConfigParser(ConfigFileParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Return parsed config file for module
     *
     * @param $module
     * @return mixed
     */
    public function getConfig($module)
    {
        if (!$this->cache['paths'][$module]) {
            $path = $this->getPath($module);
            $file = $path.DIRECTORY_SEPARATOR.'slender.yml';
            $parsed = $this->parser->parseFile($file);
            $this->cache['paths'][$module] = $parsed;
        }

        return $this->cache['paths'][$module];
    }

}
