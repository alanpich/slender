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
namespace Slender\Core\DependencyInjector;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Slender\Core\Util\Util;

//@TODO DIRTY HACK EWWWW!!!!!
if (!class_exists('Slender\Core\DependencyInjector\Annotation\Inject as Slender', false)) {
    require dirname(__FILE__) . '/Annotation/Inject.php';
}

class DependencyInjector
{
    /** @var \Pimple */
    protected $container;
    /** @var  array */
    protected $classCache;
    /** @var  AnnotationReader */
    protected $annotationReader;

    public function __construct()
    {
        $this->annotationReader = new AnnotationReader();
    }

    public function setDiContainer($di)
    {
        $this->container = $di;
    }

    /**
     * Interrogate a class and see what dependencies
     * it wants to be passed into its constructor
     *
     * @param string $className Name of class to inspece
     * @return array of DI container identifiers
     */
    public function getDiRequirements($className)
    {
        if (!isset($this->classCache[$className])) {
            $reflectionClass = new \ReflectionClass($className);
            $injects = [];
            // get all defined properties
            $props = $reflectionClass->getProperties();
            foreach ($props as $prop) {
                $inject = $this->annotationReader->getPropertyAnnotation(
                    $prop,
                    'Slender\Core\DependencyInjector\Annotation\Inject'
                );
                if ($inject) {
                    // Get the DI identifier
                    $identifier = $inject->getIdentifier();
                    if (!$identifier) {
                        $identifier = Util::hyphenCase($prop->getName());
                    }
                    // Get the setter method to call
                    $name = $prop->getName();
                    $injects[$name] = array(
                        'identifier' => $identifier,
                        'useSetter' => !$prop->isPublic()
                    );
                }
            }
            $this->classCache[$className] = $injects;
        }
        return $this->classCache[$className];
    }


    /**
     * Scan a class instance for injectable dependencies,
     * and inject them if found, then return prepared instance.
     *
     * @param object $instance Class instance to prepare
     * @throws \RuntimeException
     */
    public function prepare(&$instance)
    {
        $requirements = $this->getDiRequirements(get_class($instance));


        dump($this->container['view']);
        foreach ($requirements as $property => $service) {

            $dependency = $service['identifier'];
            if ($service['useSetter']) {
                // Private or Protected property - use the setter method
                $method = Util::setterMethodName($property);

                if (!method_exists($instance, $method)) {
                    throw new \RuntimeException("Dependency Injection requires method " . get_class(
                            $instance
                        ) . "::$method to exist");
                }
                if (!isset($this->container[$dependency])) {
                    throw new \InvalidArgumentException("Unable to resolve dependency $dependency for injection");
                }

                $dep = $this->container[$dependency];
                call_user_func([$instance, $method], $dep);

            } else {
                $instance->$property = $this->container[$dependency];
            }

        }

    }

} 
