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
namespace Slender\Core\Util;

class Util
{

    public function implementsInterface($subjectClass, $interfaceName)
    {
        if (!is_string($subjectClass)) {
            $subjectClass = get_class($subjectClass);
        }

        $reflector = new \ReflectionClass($subjectClass);

        return $reflector->implementsInterface($interfaceName);
    }


    /**
     * Convert a string to hyphen-case
     *
     * MyCamelCaseClass becomes my-camel-case-class
     *
     * @param string $str
     * @return string
     */
    public static function hyphenCase($str)
    {
        return strtolower(preg_replace('/([a-z0-9])([A-Z])/', "$1-$2", $str));
    }


    public static function setterMethodName($propertyName)
    {
        return 'set' . ucfirst($propertyName);
    }


    /**
     * Does a string start with another string?
     *
     * @param string $str    The string to check
     * @param string|array $prefix The prefix to check for
     * @return bool
     */
    public static function stringStartsWith($str, $prefix)
    {
        if(is_array($prefix)){
            foreach($prefix as $p){
                if($p === "" || strpos($str, $p) === 0){
                    return true;
                };
            }
            return false;
        }
        return $prefix === "" || strpos($str, $prefix) === 0;
    }


    /**
     * Does a string end with another string?
     *
     * @param string $str The string to check
     * @param string|array $postfix The postfix to check for
     * @return bool
     */
    public static function stringEndsWith($str, $postfix)
    {
        if(is_array($postfix)){
            foreach($postfix as $p){
                if($p === "" || substr($str, -strlen($p)) === $p){
                    return true;
                }
            }
            return false;
        }
        return $postfix === "" || substr($str, -strlen($postfix)) === $postfix;
    }


    public static function stringMatchesPattern($str, $pattern)
    {
        if(is_array($pattern)){
            foreach($pattern as $p){
                if(self::stringMatchesPattern($str,$p)){
                    return true;
                }
            }
            return false;
        }
        if(substr($pattern,0,1) != '/'){
            $pattern = '/'.$pattern;
        }
        if(substr($pattern,-1,1) != '/'){
            $pattern.= '/';
        }
        return preg_match($pattern,$str) === 1;
    }

}
