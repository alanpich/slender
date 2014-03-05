<?php
namespace Slender\Interfaces;

interface ConfigFileFinderInterface
{

    /**
     * Return an array of file paths to config files
     *
     * @return array of string paths
     */
    public function findFiles();
}
