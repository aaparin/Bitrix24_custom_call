<?php

namespace CallApplication\libs\crest;

require_once 'CRest.php';

/**
 * @version 1.00
 */
class CRestPlus extends CRest
{
  /**
   * Функция посчитывает количество необходимых сущностей на портале и создает массив с параметрами получения
   * всего списка этих сущностей
   *
   * @var str method - метод rest
   * @var arr params - массив, параметры для списочных методов (filter, select)
   * @return arr - массив с для batch запроса, разделенный по 50 пакетов
   */
  protected static function iteration($method, $params)
  {
    $tmp = parent::call($method, $params);
    $iteration = intval($tmp['total'] / 50) + 1;
    if ($tmp['total'] % 50 == 0) {
      $iteration -= 1;
    }
    for ($i = 0; $i < $iteration; $i++) {
      $start = $i * 50;
      $data[$i]['method'] = $method;
      $data[$i]['params'] = ['start' => $start];
      $data[$i]['params'] += $params;
    }
    if (isset($data)) {
      if (count($data) > 50) {
        $data = array_chunk($data, 50);
      } else {
        $data = [$data];
      }
    } else {
      $data = false;
    }
    return $data;
  }

  /**
   * Функция для списочных методов, получает весь список сущностей, соблюдая условия фильтра
   *
   * @var str method - списочный метод rest
   * @var arr params - параметры для списочных методов (filter, select)
   * @return arr - результат метода callbatch (список сущностей) или error
   */
  public static function callBatchList($method, $params)
  {
    $tmp = self::iteration($method, $params);
    if (!empty($tmp)) {
      for ($i = 0, $s = count($tmp); $i < $s; $i++) {
        $result = parent::callBatch($tmp[$i]);
      }
    } else {
      $result = false;
    }
    return $result ?: 'error';
  }

  /**
   * Функция для получения данных пользователей, принимает простой массив id ('1','2','3','n'),
   * подходит для случаев, когда нужно получить данные пользователей связанных с определенными событиями(лиды, сделки),
   * для получения пользователей (или пользователя) с определенными фильтрами или ограничениями лучше использовать callbatch
   *
   * @var arr params - массив id ('1','2','3','n')
   * @return arr - результат метода callbacth (данные пользователей)
   */
  public static function callBatchUsers($params)
  {
    $return = false;
    foreach ($params as $v) {
      $data[] = ['method' => 'user.get', 'params' => ['ID' => $v]];
    }
    $data = array_chunk($data, 50);
    for ($i = 0, $s = count($data); $i < $s; $i++) {
      $return = parent::callBatch($data[$i]);
    }
    return $return['result'];
  }

  /**
   * Can overridden this method to change the data storage location.
   * Метод переопределен, логическое значения для подмены токена пользователя, который обратился к приложению
   *
   * @return array setting for getAppSettings()
   */
  protected static function getSettingData($wannaCurrentUser = false)
  {
    $return = [];
    if (file_exists(__DIR__ . '/settings.json')) {
      $return = parent::expandData(
        file_get_contents(__DIR__ . '/settings.json')
      );
      if (defined('C_REST_CLIENT_ID') && !empty(C_REST_CLIENT_ID)) {
        $return['C_REST_CLIENT_ID'] = C_REST_CLIENT_ID;
      }
      if (defined('C_REST_CLIENT_SECRET') && !empty(C_REST_CLIENT_SECRET)) {
        $return['C_REST_CLIENT_SECRET'] = C_REST_CLIENT_SECRET;
      }
      if ($wannaCurrentUser) {
        $return['access_token'] = htmlspecialchars($_REQUEST['AUTH_ID']);
        $return['domain'] = htmlspecialchars($_REQUEST['DOMAIN']);
        $retunr['client_endpoint'] =
          'https://' . htmlspecialchars($_REQUEST['DOMAIN']) . '/rest/';
      }
    }
    return $return;
  }

  /**
   * Метод на данный момент создан подручным для метода callCurrentUser
   *
   * @var $arParams array
   * $arParams = [
   *      'method'    => 'some rest method',
   *      'params'    => []//array params of method
   * ];
   * @return mixed array|string|boolean curl-return or error
   *
   */
  private static function lightCurl($arParams, $wannaCurrentUser = true)
  {
    $arSettings = static::getSettingData($wannaCurrentUser);
    if ($arSettings !== false) {
      $url =
        $arSettings['client_endpoint'] .
        $arParams['method'] .
        '.' .
        parent::TYPE_TRANSPORT;
      if (
        empty($arSettings['is_web_hook']) ||
        $arSettings['is_web_hook'] != 'Y'
      ) {
        $arParams['params']['auth'] = $arSettings['access_token'];
      }

      $sPostFields = http_build_query($arParams['params']);
      try {
        $obCurl = curl_init();
        curl_setopt($obCurl, CURLOPT_URL, $url);
        curl_setopt($obCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($obCurl, CURLOPT_POST, true);
        curl_setopt($obCurl, CURLOPT_POSTFIELDS, $sPostFields);
        curl_setopt(
          $obCurl,
          CURLOPT_FOLLOWLOCATION,
          isset($arParams['followlocation']) ? $arParams['followlocation'] : 1
        );
        curl_setopt($obCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($obCurl, CURLOPT_SSL_VERIFYHOST, false);
        $out = curl_exec($obCurl);
        $info = curl_getinfo($obCurl);

        if (curl_errno($obCurl)) {
          $info['curl_error'] = curl_error($obCurl);
        }
        $result = parent::expandData($out);
        curl_close($obCurl);

        if (!empty($result['error'])) {
          $result['error_information'] = 'ERROR';
        }
        if (!empty($info['curl_error'])) {
          $result['error'] = 'curl_error';
          $result['error_information'] = $info['curl_error'];
        }
        return $result;
      } catch (\Exception $e) {
        return [
          'error' => 'exception',
          'error_information' => $e->getMessage(),
        ];
      }
    }
    return [
      'error' => 'no_install_app',
      'error_information' =>
        'error install app, pls install local application ',
    ];
  }

  /**
   * Метод для получения информации о пользователе и его админских правах, токен которого приходит во фрейм
   *
   * @return arr - массив array('admin' => 'информация об админских правах', 'user' => 'данные о пользователе')
   */
  public static function callCurrentUser()
  {
    $arPostUser = ['method' => 'user.current', 'params' => []];
    if (defined('C_REST_CURRENT_ENCODING')) {
      $arPostUser['params'] = parent::changeEncoding($arPostUser['params']);
    }
    $user = static::lightCurl($arPostUser);

    $arPostAdmin = ['method' => 'user.admin', 'params' => []];
    if (defined('C_REST_CURRENT_ENCODING')) {
      $arPostAdmin['params'] = parent::changeEncoding($arPostAdmin['params']);
    }
    $admin = static::lightCurl($arPostAdmin);

    $result = ['admin' => $admin['result'], 'user' => $user['result']];
    return $result;
  }

  /**
   * Метод выводит системную информацию о сущности битрикса Дело
   *
   * @return arr
   */
  public static function aboutActivity()
  {
    $fields = parent::call('crm.activity.fields', [])['result'];
    $direction = parent::call('crm.enum.activitydirection', [])['result'];
    $ownerType = parent::call('crm.enum.ownertype', [])['result'];
    $status = parent::call('crm.enum.activitystatus', [])['result'];
    $type = parent::call('crm.enum.activitytype', [])['result'];

    $return = [
      'description' => 'Информация о сущности дело',
      'fields' => ['desc' => 'Поля', 'value' => $fields],
      'direction' => ['desc' => 'Направления', 'value' => $direction],
      'ownerType' => [
        'desc' => 'Тип владельца(сущности)',
        'value' => $ownerType,
      ],
      'status' => ['desc' => 'Статус', 'value' => $status],
      'type' => ['desc' => 'Тип дела', 'value' => $type],
    ];
    return $return;
  }
}
