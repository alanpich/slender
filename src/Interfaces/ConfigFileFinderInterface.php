<?php
namespace Slender\Interfaces;

use Symfony\Component\Finder\SplFileInfo;

interface ConfigFileFinderInterface
{

    /**
     * Return an array of file paths to config files
     *
     * @return array of string paths
     */
    public function findFiles();
} 