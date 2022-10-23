<?php

/** Определение пространства имён */

namespace CallApplication\libs;

/**
 * Блок логирования ошибок PHP
 */
ini_set('display_errors', 0);
ini_set('log_errors', 'on');
//ini_set('error_log', __DIR__ . '/error.log');

/**
 * Основной класс приложения
 */
class Application extends BX24
{
  /**
   * Конструктор класса
   */
  function __construct()
  {
    $this->installCheck();

    if (empty($_REQUEST)) {
      $_REQUEST = $this->parseFetch();
    }

    /**
     * Получение массива с данными о пользователе
     */
    $userData = parent::getCurrentUserData();
    $this->IS_CURRENT_USER_ADMIN = $userData['IS_CURRENT_USER_ADMIN'];
    $this->CURRENT_UID = $userData['CURRENT_UID'];

  }

  /**
   * Проверка на статус установки приложения
   */
  function installCheck()
  {
    if (!file_exists(__DIR__ . '/crest/settings.json')) {
      require_once __DIR__ . '/crest/install.php';
    }
  }

}
