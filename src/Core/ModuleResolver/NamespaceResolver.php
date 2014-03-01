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
            if(in_array($reflector->getInterfaceNames(),'\Slender\Core\Module\ModulePathProviderInterface')){
                return call_user_func($class,'getModulePath');
            }
        }

        // Give up
        return false;
    }

}