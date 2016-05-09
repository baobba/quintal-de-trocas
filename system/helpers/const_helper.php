<?php 

class ConstHelper {
    
    const YES = 1;
    const NO = 0;
    
    /**
     * @return ArrayObject
     */
    public static function getYesNoHeavyDescription()
    {
        $array = new ArrayObject();
        $array->offsetSet(self::YES, 'Sim');
        $array->offsetSet(self::NO,  'N&atilde;o');
    
        return $array;
    }
    
    public static function getYesNoAsString($key, $default = '')
    {
        return self::getAsString(self::getYesNoHeavyDescription(), $key, $default);
    }
    
    public static function getAsString(ArrayAccess $haystack, $needle, $default)
    {
        $needle = $needle === null ? '' : $needle;
        return $haystack->offsetExists($needle) ? $haystack->offsetGet($needle) : $default;
    }
}