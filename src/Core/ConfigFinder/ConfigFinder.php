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
namespace Slender\Core\ConfigFinder;

use Slender\Interfaces\ConfigFileFinderInterface;
use Symfony\Component\Finder\Finder;

class ConfigFinder implements ConfigFileFinderInterface
{
    protected $paths = array();
    protected $files = array();

    public function __construct(array $paths = array(),array $files = array())
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
        foreach ($this->paths as $path) {
            if (is_readable($path)) {
                $search->in($path);
            }
        }
        // Add in names
        foreach ($this->files as $pattern) {
            $search->name($pattern);
        }

        $array = array();
        foreach ($search as $f) {
            $array[] = $f->getRealPath();
        }

        return $array;

    }
}
