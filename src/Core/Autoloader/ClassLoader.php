<?php
/**
 * Slender - Slim, but with a bit more meat
 *
 * @author      Alan Pich <alan.pich@gmail.com>
 * @copyright   2014 Alan Pich
 * @link        http://alanpich.github.io/slender
 * @license     https://github.com/alanpich/slender/blob/develop/LICENSE
 * @version     0.0.0
 * @package     Slender
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Slender\Core\Autoloader;

use Slender\Exception\AutoloadException;
use Symfony\Component\ClassLoader\UniversalClassLoader;

class ClassLoader extends UniversalClassLoader
{

    /**
     * @var PSR4ClassLoader
     */
    protected $psr4Loader;

    /**
     * Overridden to allow injecting psr-4 into the mix.
     * Also opens up doors for alternative formats to be used
     * later. Either way, it's a mess here right now
     *
     * @param  string                               $namespace
     * @param  array|string                         $paths
     * @param  string                               $type      Either 'psr-0' or 'psr-4'. Defaults to 'psr-0'
     * @throws \Slender\Exception\AutoloadException
     */
    public function registerNamespace($namespace, $paths, $type = 'psr-0')
    {
        switch ($type) {
            case 'psr-0':
                parent::registerNamespace($namespace, $paths);
                break;
            case 'psr-4':
                $this->psr4Loader->addNamespace($namespace,$paths);
                break;
            default:
                throw new AutoloadException("$type is not a known autoloader format");
        }
    }

    /**
     * @param \Slender\Core\Autoloader\PSR4ClassLoader $psr4Loader
     */
    public function setPsr4Loader($psr4Loader)
    {
        $this->psr4Loader = $psr4Loader;
    }

    /**
     * @return \Slender\Core\Autoloader\PSR4ClassLoader
     */
    public function getPsr4Loader()
    {
        return $this->psr4Loader;
    }

}
