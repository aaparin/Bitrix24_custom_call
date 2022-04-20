<?php
namespace B1Integration\libs\helpers;

class Logs {
    static function getLogData($patch){
        return file_get_contents($patch);
    }
}
?>