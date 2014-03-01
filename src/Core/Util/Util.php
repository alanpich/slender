<?php
namespace Slender\Util;

class Util
{

    public static function merge($target,$new)
    {
        foreach($new as $key => $value){
            if(!isset($target[$key])){
                $target[$key] = $value;
                continue;
            }
            if(is_array($target[$key])){
                $target[$key] = self::merge($target[$key],$value);
                continue;
            }
            $target[$key] = $key;
        }
        return $target;
    }

} 