<?php

/** Определение пространства имён */
namespace CallApplication\libs\debugger;

/**
 * Класс методов логирования
 */
class Debugger extends \Exception
{
  /**
   * Вывод ошибок и предупреждений
   * @param int $debug <p> [не обязательно]
   * true — включить отображение ошибок
   * </p>
   */
  public static function displayErrors($debug = 0)
  {
    if (!$debug) {
      ini_set('display_errors', 0);
      ini_set('display_startup_errors', 0);
      error_reporting(0);
      ini_set('log_errors', 0);
    } else {
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(-1);
      ini_set('log_errors', 1);
    }
  }

  /**
   * Функция логгирования
   * @param $data <p>
   * Данные для записи в файл
   * </p>
   * @param string $title <p> [не обязательно]
   * Заголовок записи
   * </p>
   * @param $path <p>
   * Путь создания файла
   * </p>
   * @return bool
   */
  public static function wtl($data, $title = 'WRITETOLOG', $path = 'log')
  {
    if (LOGGING) {
      if (gettype($data) == 'boolean') {
        $data = json_encode($data);
      }
      $log =
        "----------------------------------------------------------------\n";
      $log .= date('d.m.Y H:i:s');
      $log .= "\n--------------------------------\n";
      $log .= $title;
      $log .= "\n--------------------------------\n";
      $log .= print_r($data, 1);
      $log .=
        "\n----------------------------------------------------------------\n\n\n";
      file_put_contents($path . '.log', $log, FILE_APPEND);
    }
    return true;
  }

  /**
   * Функция вывода данных на экран
   * @param $data <p>
   * Данные для записи в файл
   * </p>
   * @param string $title <p>
   * Заголовок
   * </p>
   * @param bool $die <p> [не обязательно]
   * Принудительно остановить дальнейшее выполнение скрипта командой die()
   * </p>
   * @param string $dieMessage <p> [не обязательно]
   * Сообщение die();
   * </p>
   */
  public static function debug(
    $data,
    $title = 'DEBUG',
    $die = false,
    $dieMessage = 'использована команда die'
  ) {
    echo '----------------------------------------------------------------<br>';
    echo $title . '<br>';
    echo '----------------------------------------------------------------<br>';
    echo 'Тип данных: ' . gettype($data) . '<br>';
    echo '----------------------------------------------------------------';
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    echo '----------------------------------------------------------------<br><br>';
    if ($die) {
      die('Работа скрипта завершена принудительно: ' . $dieMessage);
    }
  }

  /**
   * Запись данных в PHP-файл
   * @param $data <p>
   * Переменная для записи в файл
   * </p>
   * @param $path <p>
   * Данные для записи в файл
   * </p>
   * @return bool <p> [не обязательно]
   * Путь записи файла
   * </p>
   */
  public static function saveParams($data, $path)
  {
    $config = "<?php\n";
    $config .= "\$appsConfig = " . var_export($data, true) . "\n";
    $config .= '?>';

    file_put_contents($path, $config);
    return true;
  }

  /**
   * Вывод информации об использовании памяти
   */
  public static function memoryTest()
  {
    $str =
      'Текущее использование памяти: <strong>' .
      memory_get_usage() .
      '</strong> байт' .
      "\n";
    $str .=
      'Пиковое значение объема памяти: <strong>' .
      memory_get_peak_usage() .
      '</strong> байт';
    self::debug($str);
  }
}
