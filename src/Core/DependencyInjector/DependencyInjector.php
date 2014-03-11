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
use Slender\Interfaces\FactoryInterface;

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

    public function create($class)
    {
        $instance = new $class;

        if ($instance instanceof FactoryInterface) {
            $instance = $instance->create($this->container);
        }

        $this->prepare($instance);

        return $instance;
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

                    $data = array(
                        'identifier' => $identifier,
                        'isPublic' => $prop->isPublic(),
                        'setter' => false
                    );

                    // Is there a setter method (cos its still faster andrew...)
                    if (!$prop->isPublic()) {
                        $setter = Util::setterMethodName($prop->getName());
                        if (method_exists($className, $setter)) {
                            $data['hasSetter'] = $setter;
                        };
                    };

                    $injects[$prop->getName()] = $data;
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
     * @throws \InvalidArgumentException
     */
    public function prepare(&$instance)
    {
        $requirements = $this->getDiRequirements(get_class($instance));

        foreach ($requirements as $propertyName => $property) {

            $dependencyIdentifier = $property['identifier'];
            $dependency = $this->container[$dependencyIdentifier];

            if (!isset($this->container[$dependencyIdentifier])) {
                throw new \InvalidArgumentException("Unable to resolve dependency $dependencyIdentifier for injection");
            }

            if ($property['isPublic']) {
                // If its public, just set it!
                $instance->$propertyName = $dependency;
            } else {
                if ($property['setter']) {
                    // If there is a setter method, use that
                    call_user_func($instance, $property['setter'], $dependency);
                } else {
                    // Otherwise set by brute force
                    $refl = new \ReflectionClass($instance);
                    $prop = $refl->getProperty($propertyName);
                    $prop->setAccessible(true);
                    $prop->setValue($instance, $this->container[$dependencyIdentifier]);
                    $prop->setAccessible(false);
                }
            }

        }

    }

} 
