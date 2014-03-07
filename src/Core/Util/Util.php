<?php
namespace Slender\Core\Util;

class Util
{

    public function implementsInterface($subjectClass,$interfaceName)
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
        return strtolower(preg_replace( '/([a-z0-9])([A-Z])/', "$1-$2", $str ));
    }


    public static function setterMethodName($propertyName)
    {
        return 'set' . ucfirst($propertyName);
    }

}
