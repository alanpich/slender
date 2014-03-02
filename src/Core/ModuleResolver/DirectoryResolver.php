<?php
namespace Slender\Core\ModuleResolver;

use Slender\Interfaces\ModuleResolverInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Attempts to resolve a module path by looking in a directory
 *
 * @package Slender\Core\ModuleResolver
 */
class DirectoryResolver extends AbstractResolver
{
    protected $dir;

    /**
     * Constructor
     *
     * @param string $baseDir Path to search in
     */
    public function __construct($baseDir)
    {
        $this->dir = $baseDir;
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
        $dir = $this->dir . DIRECTORY_SEPARATOR . $module;

        if (!is_dir($dir)) {
            return false;
        }

        $find = new Finder();
        $files = $find->files()
            ->in($dir)
            ->name('slender.*');

        if ($files->count() < 1) {
            return false;
        }

        $filesIterator = iterator_to_array($files->getIterator());
        $iterator = array_shift($filesIterator);

        return $iterator->getPath();
    }
}