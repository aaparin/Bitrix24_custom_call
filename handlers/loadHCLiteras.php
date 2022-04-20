<?php

/**
 * Блок настройки пространства имён
 */

use actsGenerator\libs\crest\CRestPlus as CRP;
use actsGenerator\libs\debugger\Debugger as DEB;

/**
 * Блок логирования ошибок PHP
 */
ini_set('display_errors', 0);
ini_set('log_errors', 'on');
ini_set('error_log', __DIR__ . '/error.log');

/**
 * Блок определения констант
 */
define('LOGGING', false);

/**
 * Блок подключения модулей
 */
require_once '../libs/autoloader.php';

/**
 * Блок алгоритма работы
 */
$callHCLiteras = CRP::callBatchList('crm.productsection.list', [
  'filter' => [
    'SECTION_ID' => $_REQUEST['data'],
  ],
]);
DEB::wtl(
  $callHCLiteras,
  '$callHCLiteras' . ' ' . __LINE__ . ' строка (loadHCLiteras.php)',
  'loadHCLiteras_$callHCLiteras'
);

/**
 * Блок формирования HTML-кода для select с литерами ЖК
 */
ob_start();
?>
<div class="row justify-content-center mt-4 slideFromBot" id="literasSelect">
  <div class="col-auto">
    <label for="literasList" class="text-muted">Выберите литер:</label>
    <select name="literas" id="literasList" class="custom-select">
      <option value="empty" hidden>Литер не выбран</option>
      <?php foreach ($callHCLiteras['result']['result'] as $chlPackage): ?>
        <?php foreach ($chlPackage as $sectionsData): ?>
          <option value="<?= $sectionsData['ID'] ?>"><?= $sectionsData[
  'NAME'
] ?></option>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </select>
  </div>
</div>
<?php
$outputHTML = ob_get_clean();
DEB::wtl(
  $outputHTML,
  '$outputHTML' . ' ' . __LINE__ . ' строка (loadHCLiteras.php)',
  'loadHCLiteras_$outputHTML'
);
echo json_encode([
  'outputHTML' => $outputHTML,
]);

