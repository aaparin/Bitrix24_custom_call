<?php

/** Определение пространства имён */

namespace B1Integration\libs;

/** Настройка пространства имён */

use B1Integration\libs\crest\CRestPlus as CRP;

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

  /**
   * Получение списка разделов товаров
   */
  function getSections()
  {
    $this->SECTIONS_LIST = CRP::callBatchList('crm.productsection.list', []);
  }

  function getEmployees()
  {
    $this->EMPLOYEES_LIST = CRP::callBatchList('user.get', [
      'sort' => 'LAST_NAME',
      'order' => 'ASC',
      'filter' => [
        'ACTIVE' => 'Y',
        'USER_TYPE' => 'employee',
      ],
    ]);

    foreach ($this->EMPLOYEES_LIST['result']['result'] as $ePackage) {
      foreach ($ePackage as $eData) {
        if (empty($eData['LAST_NAME']) && empty($eData['NAME'])) {
          $FIO = 'ID ' . $eData['ID'];
        } else {
          $FIO = $eData['LAST_NAME'] . ' ' . $eData['NAME'];
        }
        /** Список пользователей */
        $this->EMPLOYEES[$eData['ID']] = $FIO;
      }
    }
  }

  function getHCLiteras($HCs)
  {
    $this->getSections();

    foreach ($this->SECTIONS_LIST['result']['result'] as $chlPackage) {
      foreach ($chlPackage as $HCData) {
        $allSecs[] = $HCData;
      }
    }

    foreach ($HCs as $HCId) {
      foreach ($allSecs as $saData) {
        if ($saData['SECTION_ID'] == $HCId) {
          $this->CHILD_LITERAS[] = $saData['ID'];
        }
      }
    }

    foreach ($allSecs as $asData) {
      foreach ($allSecs as $asDataNd) {
        if ($asData['SECTION_ID'] == $asDataNd['ID']) {
          $this->SaHC[$asData['ID']] = $asDataNd['NAME'];
          $this->SaHCi[$asData['ID']] = $asData['SECTION_ID'];
        } else {
          if (is_null($asData['SECTION_ID'])) {
            $this->SaHC[$asDataNd['ID']] = $asDataNd['NAME'];
          } elseif (!is_null($asData['SECTION_ID'])) {
            $this->SaHCLitera[$asData['ID']] = $asData['NAME'];
          }
        }
        if (
          empty($asData['SECTION_ID']) &&
          !empty($asDataNd['SECTION_ID']) &&
          $asData['ID'] == $asDataNd['SECTION_ID']
        ) {
          $this->HCLiterasNd[$asData['ID']][] = $asDataNd['ID'];
        }
      }
    }

    foreach ($this->HCLiterasNd as $HCId => $literas) {
      $this->HCLiterasCount[$HCId] = count($literas);
    }
  }

  /**
   * Получение списка ЖК
   */
  function getHCsAndLiteras()
  {
    $this->getSections();

    /**
     * Блок структуризации
     */
    foreach ($this->SECTIONS_LIST['result']['result'] as $chlPackage) {
      foreach ($chlPackage as $HCData) {
        if (self::TESTTING_MODE) {
          if (empty($HCData['SECTION_ID'])) {
            /** Массив ЖК */
            $HCLiteras[$HCData['ID']] = $HCData['NAME'];
          }
        } else {
          if (empty($HCData['SECTION_ID']) && $HCData['ID'] != 265) {
            /** Массив ЖК */
            $HCLiteras[$HCData['ID']] = $HCData['NAME'];
          }
        }
      }
    }

    return $HCLiteras;
  }
}
