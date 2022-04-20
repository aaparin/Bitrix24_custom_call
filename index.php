<?php

use B1Integration\libs\crest\CRestPlus as CRP;
use B1Integration\libs\B1Class\B1;
use B1Integration\libs\helpers\B1Helper;
use B1Integration\libs\helpers\Helpers;
use B1Integration\libs\debugger\Debugger;
use Noodlehaus\Config;
use B1Integration\libs\helpers\UserSettings;
use B1Integration\libs\helpers\BitrixApiHelper;
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
define('APP_NAME', 'Bitrix24 - B1 integration');
define('USER_SETTINGS', __DIR__.'/libs/userconfig.json');


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
$b1 = new B1(['apiKey' => $conf->get('b1ApiKey'), 'privateKey' => $conf->get('b1ApiPass')]);

if(isset($_POST['type'])){
    switch ($_POST['type']){

    }
}

require_once __DIR__ . '/view/index.php';
