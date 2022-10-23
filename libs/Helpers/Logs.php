<?php
namespace CallApplication\libs\helpers;

class Logs {
    static function getLogData($patch){
        return file_get_contents($patch);
    }
}
?>