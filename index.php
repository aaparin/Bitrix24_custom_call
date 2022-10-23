<?php

use CallApplication\libs\crest\CRestPlus as CRP;
use CallApplication\libs\helpers\Helpers;
use CallApplication\libs\debugger\Debugger;
use Noodlehaus\Config;
use CallApplication\libs\helpers\UserSettings;
use CallApplication\libs\helpers\BitrixApiHelper;

/**
 * Блок проверки ID портала
 */
//if ($_REQUEST['member_id'] != '8cf76f3d5a60a64a0604b7bdacb6db4e') {
//  http_response_code(403);
//  die();
//}


/** Подключение автолоадера */
require_once __DIR__ . '/libs/autoloader.php';

/**
 * Блок определения констант
 */
define('DOMAIN', $_REQUEST['DOMAIN']); // Используется в JavaScript
define(
    'PORTAL_URL',
    ($_REQUEST['PROTOCOL'] == '1' ? 'https://' : 'http://') . DOMAIN
);
define('APP_NAME', 'Bitrix24 - Настройки карточки телефонии');
define('USER_SETTINGS', __DIR__ . '/userconfig.json');


/**
 * Блок определения триггера установки приложения
 */
$installedTrigger = false; // Триггер "Приложение успешно установлено"
if (
    isset($_REQUEST['PLACEMENT_OPTIONS']) &&
    $_REQUEST['PLACEMENT_OPTIONS'] == '{"install_finished":"Y"}'
) {
    $installedTrigger = true;
}

//Config init
$conf = new Config(USER_SETTINGS);

if (isset($_POST['type'])) {
    switch ($_POST['type']) {
        case 'settings':
            UserSettings::saveAllData($conf, $_POST);
            break;
    }
}

require_once __DIR__ . '/view/index.php';
