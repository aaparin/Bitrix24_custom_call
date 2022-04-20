<?php

/**
 * Блок подключения модулей
 */
use B1Integration\libs\crest\CRestPlus as CRP;
use B1Integration\libs\Sync\Categories;
require_once '../libs/autoloader.php';
?>
    <button onclick="history.back()">Go Back</button><Br/><Br/>
<?php

$items = CRP::call(
    'catalog.product.list',
    [
        'order' => [

        ],
        'filter' => [

        ],
        'start' => 1,
    ]
);
print_r($items);