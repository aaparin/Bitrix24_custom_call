<?php

/**
 * Блок логирования ошибок PHP
 */
ini_set('display_errors', 1);
//ini_set('log_errors', 'on');
//ini_set('error_log', __DIR__ . '/error.log');

/**
 * Блок определения констант
 */
define('CLASSES', [
  'Application',
  'BX24',
  'debugger/Debugger',
  'crest/CRestPlus',
  'Helpers/UserSettings',
  'Helpers/Logs',
  'Helpers/Helpers',
  'Helpers/BitrixApiHelper',
  'Helpers/UserSettings',
]);

// Composer
require __DIR__.'/../vendor/autoload.php';

/**
 * Автозагрузчик классов
 * @param string $class Название класса
 * @return void
 */
spl_autoload_register(function () {
  foreach (CLASSES as $cClassPath) {
    require_once $cClassPath . '.php';
  }
});

$App = new CallApplication\libs\Application();
$BX24 = new CallApplication\libs\BX24();