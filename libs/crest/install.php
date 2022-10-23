<?php
/**
 * Определение пространства имён
 */
namespace CallApplication\libs\crest;

/**
 * Настройка пространства имён
 */
use CallApplication\libs\crest\CRestPlus as CRP;

require_once __DIR__ . '/CRestPlus.php';
CRP::installApp();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Установка приложения CRest</title>
	<script src="//api.bitrix24.com/api/v1/"></script>
	<script>
		BX24.init(function() {
			BX24.installFinish();
		});
	</script>
</head>

<body>
	Установка приложения CRest
</body>

</html>