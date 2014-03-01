<?php
namespace Slender\Core\ModuleResolver;

use Slender\Interfaces\ConfigFileParserInterface;
use Slender\Interfaces\ModuleResolverInterface;

class NamespaceResolver extends AbstractResolver
{

    /**
     * Return the path to Module $module, or false
     * if not found
     *
     * @param string $module Module name or Namespace
     * @return string|false
     */
    public function getPath($module)
    {
        // treat $module as a namespace
        $class = $module."\\SlenderModule";

        // Try to find class for path
        if(class_exists($class) ){
            $reflector = new \ReflectionClass($class);
            $interfaces = $reflector->getInterfaceNames();
            if(is_string($interfaces)){
                $interfaces = array($interfaces);
            }
            if(in_array('Slender\Interfaces\ModulePathProviderInterface',$interfaces)){
                return call_user_func(array($class,'getModulePath'));
            }
        }

        // Give up
        return false;
    }

}