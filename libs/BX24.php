<?php

/** Определение пространства имён */

namespace CallApplication\libs;

/** Настройка пространства имён */

use CallApplication\libs\crest\CRestPlus as CRP;

/**
 * Блок логирования ошибок PHP
 */
ini_set('display_errors', 1);
ini_set('log_errors', 'on');
//ini_set('error_log', __DIR__ . '/error.log');

/**
 * Класс для работы с Битрикс24
 */
class BX24
{
  /**
   * Блок определения констант
   */
  const TESTTING_MODE = true;

  /**
   * Конструктор класса
   */
  function __construct()
  {
    $this->getCurrentUserData();
  }

  /**
   * Получение информации по текущему пользователю
   */
  function getCurrentUserData()
  {
    $callCurrentUser = CRP::callCurrentUser();
    return [
      'IS_CURRENT_USER_ADMIN' => $callCurrentUser['admin'],
      'CURRENT_UID' => $callCurrentUser['user']['ID'],
    ];
  }
}
