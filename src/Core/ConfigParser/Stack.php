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
namespace Slender\Core\ConfigParser;

use Slender\Exception\ConfigFileFormatException;
use Slender\Exception\ConfigFileNotFoundException;
use Slender\Interfaces\ConfigFileParserInterface;

class Stack implements ConfigFileParserInterface
{
    protected $parsers = array();

    public function __construct($parsers)
    {
        foreach ($parsers as $ext => $parser) {
            if ($parser instanceof ConfigFileParserInterface) {
                $this->parsers[$ext] = $parser;
            }
        }
    }

    /**
     * Parse $path and return array of config from within
     *
     * @param  string                                         $path Path to file
     * @throws \Slender\Exception\ConfigFileFormatException   when unable to parse file
     * @throws \Slender\Exception\ConfigFileNotFoundException when $path does not exist
     * @return array
     */
    public function parseFile($path)
    {
        // Check file exists
        if (!is_readable((string) $path)) {
            throw new ConfigFileNotFoundException("$path does not exist, or is not readable");
        }
        $extension = pathinfo($path,PATHINFO_EXTENSION);

        if (isset($this->parsers[$extension])) {
            return $this->parsers[$extension]->parseFile($path);
        }

        throw new ConfigFileFormatException("$extension is not a known config file format");
    }
}
