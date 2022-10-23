<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Пользовательский CSS -->
    <link rel="stylesheet" href="view/css/customizeBS.css?<?= time() ?>">
    <link rel="stylesheet" href="view/css/anims.css?<?= time() ?>">
    <link rel="stylesheet" href="view/css/main.css?<?= time() ?>">

    <meta http-Equiv="Cache-Control" Content="no-cache"/>
    <meta http-Equiv="Pragma" Content="no-cache"/>
    <meta http-Equiv="Expires" Content="0"/>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title><?= APP_NAME ?></title>

    <!-- JavaScript-библиотека Битрикс24 -->
    <script src="//api.bitrix24.com/api/v1/"></script>

    <!-- Перенос значений переменных PHP в JavaScript -->
    <script>
        /**
         * Блок информации о текущем пользователе
         */
            // const CURRENT_UID = <?= $App->CURRENT_UID ?>;
        const IS_CURRENT_USER_ADMIN = <?= $App->IS_CURRENT_USER_ADMIN
                ? 'true'
                : 'false' ?>;

        /**
         * Блок информации о приложении
         */
        const INSTALLED_TRIGGER = <?= $App->INSTALLED_TRIGGER ? 'true' : 'false' ?>;
    </script>
</head>

<body class="bg-light">

<!-- Toast -->
<div id="toasts">
    <!-- Сюда будут вставляться toast -->
</div>

<!-- Навигационная панель -->
<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container">
        <span class="navbar-brand"><em class="bi bi-file-earmark-word pr-2 text-darstroy"></em><?= APP_NAME ?></span>
    </div>
</nav>

<!-- Алерты -->
<div id="alerts" class="mt-4 mx-auto w-75"></div>


<div class="container mt-4 p-4 shadow-sm rounded bg-white" id="mainData">
    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab"
                   aria-controls="v-pills-settings" aria-selected="true">Настройки</a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <h3>Настройки</h3>

                    <form id="setting_form" method="post">
                        <div class="form-group">
                            <label for="APIkey">ID смарт процесса обращений</label>
                            <input type="text" name="id_smart" class="form-control" id="APIkey" value="<?=$conf->get('id_smart')?>">
                        </div>
                        <div class="form-group">
                            <label for="APIpass">ID воронки</label>
                            <input type="text" name="id_cat" class="form-control" id="APIpass" value="<?=$conf->get('id_cat')?>">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="type" value="settings"/>
                            <button id="saveButton" type="submit" class="btn btn-info">Сохранить</button>
                        </div>
                    </form>





                </div>
            </div>
        </div>
    </div>
</div>


<!-- Загрузка нескольких файлов -->
<!--  <script src="view/js/browser.js"></script>-->


<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
        crossorigin="anonymous"></script>
<!-- Пользовательский JavaScript -->
<script src="view/js/main.js?<? //= time() ?>"></script>

</body>

</html>