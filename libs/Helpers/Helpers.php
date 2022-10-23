<?php

namespace CallApplication\libs\Helpers;

class Helpers {
    public static function Text2Bool(string $textBool){
        return $textBool=='Y' ? true : false;
    }

    public static function nameToCode(string $name){
        return mb_strtolower(preg_replace('/\s+/', '-', $name));
    }

    public static function toJson(array $Array){
        return json_encode($Array);
    }

    public static function arraySearch($val,$array,$column){
        return array_search($val, array_column($array, $column));
    }

}
?>