<?php
namespace Slender\Core\Util;

class Util
{




    public function implementsInterface($subjectClass,$interfaceName)
    {
        if(!is_string($subjectClass)){
            $subjectClass = get_class($subjectClass);
        }

        $reflector = new \ReflectionClass($subjectClass);
        return $reflector->implementsInterface($interfaceName);
    }

} 