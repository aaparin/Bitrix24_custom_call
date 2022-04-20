<?php

/** Определение пространства имён */

namespace B1Integration\libs;

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

    /**
     * Получение массива с ЖК и литреами
     */
    $this->HCS = parent::getHCsAndLiteras();

    parent::getEmployees();
  }

  function emptyCheck($data, $isDate = false)
  {
    if (!empty($data)) {
      if ($isDate) {
        return date('d.m.Y', strtotime($data));
      } else {
        return $data;
      }
    } else {
      return '';
    }
  }

  function parseDate($date)
  {
    return date('d.m.Y', strtotime($date));
  }

  function generateAlert(
    $color = 'danger',
    $text = '
    Произошла непредвиденная ошибка.
    Пожалуйста, повторите попытку позже, либо обратитесь к разработчику приложения
    '
  ) {
    return <<<ALERT_HTML
      <div class="alert alert-$color text-center" role="alert">
        $text
      </div>
ALERT_HTML;
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

  function insertSelect($data, $id)
  {
    ob_start(); ?>
    <select class="custom-select" id="<?= $id ?>">
    <option value="empty" hidden>ЖК не выбран</option>
      <?php foreach ($data as $key => $value): ?>
        <option value="<?= $key ?>"><?= $value ?></option>
      <?php endforeach; ?>
    </select>
<?php return ob_get_clean();
  }

  /**
   * Спарсить fetch из php://input
   * @return array $_REQUEST
   */
  function parseFetch()
  {
    return json_decode(file_get_contents('php://input'), 1);
  }
}
