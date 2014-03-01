<?php
namespace Slender\Interfaces;

/**
 * Interface ConfigFileParserInterface
 *
 * Required interface for custom config file language parsers
 *
 * @package Slender\Interfaces
 */
interface ConfigFileParserInterface
{
    /**
     * Parse $path and return array of config from within
     *
     * @param string $path Path to file
     * @return array
     */
    public function parseFile($path);
} 