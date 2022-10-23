<?php

namespace CallApplication\libs\helpers;

use CallApplication\libs\helpers\Helpers;


class UserSettings
{

    public static function saveAllData($conf, $post)
    {
        unset($post['type']);
        foreach ($post as $key => $value) {
            $conf->set($key, $value);
        }
        $conf->toFile(USER_SETTINGS);
    }
}

?>