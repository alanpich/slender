<?php
namespace Slender\Module\DependencyInjector;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Slender\Core\Util\Util;

//@TODO DIRTY HACK EWWWW!!!!!
require dirname(__FILE__).'/Annotation/Inject.php';

class DependencyInjector
{
    /** @var \Pimple  */
    protected $container;
    /** @var  array */
    protected $classCache;
    /** @var  AnnotationReader */
    protected $annotationReader;

    public function __construct(\Pimple $diContainer)
    {
        $this->annotationReader = new AnnotationReader();
        $this->container = $diContainer;
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
        if(!isset($this->classCache[$className])){
            $reflectionClass = new \ReflectionClass($className);
            $injects = [];
            // get all defined properties
            $props = $reflectionClass->getProperties();
            foreach($props as $prop){
                $inject = $this->annotationReader->getPropertyAnnotation($prop,'Slender\Module\DependencyInjector\Annotation\Inject');
                if($inject){
                    // Get the DI identifier
                    $identifier = $inject->getIdentifier();
                    if(!$identifier){
                        $identifier = Util::hyphenCase($prop->getName());
                    }
                    // Get the setter method to call
                    $name = $prop->getName();
                    $method = Util::setterMethodName($name);
                    $injects[$method] = $identifier;
                }
            }
            $this->classCache[$className] = $injects;
        }
        return $this->classCache[$className];
    }


    public function prepare(&$instance)
    {
        $requirements = $this->getDiRequirements(get_class($instance));

        foreach($requirements as $method => $argument)
        {
            call_user_func([$instance,$method],$this->container[$argument]);
        }
    }

} 
