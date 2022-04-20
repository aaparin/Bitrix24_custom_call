<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Пользовательский CSS -->
    <link rel="stylesheet" href="view/css/customizeBS.css?<?= time() ?>">
    <link rel="stylesheet" href="view/css/anims.css?<?= time() ?>">
    <link rel="stylesheet" href="view/css/main.css?<?= time() ?>">

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
                   aria-controls="v-pills-settings" aria-selected="true">Settings</a>

                <a class="nav-link" id="v-pills-mapping-tab" data-toggle="pill" href="#v-pills-mapping" role="tab"
                   aria-controls="v-pills-mapping" aria-selected="false">Fields mapping</a>

                <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"
                   aria-controls="v-pills-home" aria-selected="false">Exchange</a>
                <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab"
                   aria-controls="v-pills-profile" aria-selected="false">Logs exchange</a>
                <!--                <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Warehouse settings</a>-->
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <h3>Settings</h3>

                    <form id="setting_form" method="post">
                        <div class="form-group">
                            <label for="APIkey">B1 Api key</label>
                            <input type="text" name="b1ApiKey" class="form-control" id="APIkey" value="<?=$conf->get('b1ApiKey')?>">
                        </div>
                        <div class="form-group">
                            <label for="APIpass">B1 Api password</label>
                            <input type="text" name="b1ApiPass" class="form-control" id="APIpass" value="<?=$conf->get('b1ApiPass')?>">
                        </div>
                        <div class="form-group">
                            <label for="bxIblockID">Bitrix Iblock ID</label>
                            <input type="text" name="bxIblockID" class="form-control" id="bxIblockID" value="<?=$conf->get('bxIblockID')?>">
                        </div>
                        <div class="form-group">
                            <label for="b1WarehouseID">B1 warehouse ID</label>
                            <input type="text" name="b1WarehouseID" class="form-control" id="b1WarehouseID" value="<?=$conf->get('b1WarehouseID')?>">
                        </div>
                        <div class="form-group">
                            <label for="updateCronTime">Update frequency (sec.)</label>
                            <input type="text" name="updateCronTime" class="form-control" id="updateCronTime" value="<?=$conf->get('updateCronTime')?>">
                        </div>
                        <div class="form-group">
                            <label for="updateCronTime">Select company type</label>
                            <select  name="entity_type_id" class="form-control" id="entity_type_id">
                                <?foreach ($ownerTypes as $oType){
                                    if($conf->get('entity_type_id')==$oType['ID']){
                                        ?>
                                        <option selected value="<?=$oType['ID']?>"><?=$oType['NAME']?></option>
                                        <?
                                    }else{?>
                                        <option value="<?=$oType['ID']?>"><?=$oType['NAME']?></option>
                                    <?}?>
                                <?}?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="type" value="settings"/>
                            <button id="saveButton" type="submit" class="btn btn-info">Save</button>
                        </div>
                    </form>

                    <h3>Measures matching</h3>
                    <??>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>B1</th>
                            <th>Bitrix</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($measuresMatch as $measure){?>
                        <tr>
                            <td><?=$b1Measures[B1Integration\libs\helpers\Helpers::arraySearch($measure->b1,$b1Measures,'id')]['name']?></td>
                            <td><?=$bitrixMeasures[B1Integration\libs\helpers\Helpers::arraySearch($measure->bitrix,$bitrixMeasures,'id')]['symbolIntl']?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="uid" value="<?=$measure->uid?>"/>
                                    <input type="hidden" name="type" value="measure_del"/>
                                    <button class="btn btn-info" type="submit">x</button>
                                </form>
                            </td>
                        </tr>
                        <?}?>
                        </tbody>
                    </table>

                    <div class="container" style="border: 1px dotted #cccccc">
                        <form id="measure_add_form" method="post">
                            <div class="row">
                            <div class="col-sm"><br/>
                                <strong>Add new match</strong>
                            </div>
                            <div class="col-sm">
                                B1:&nbsp;
                                <select name="b1m" class="form-control">
                                    <?foreach ($b1Measures as $b1m){
                                       ?>
                                        <option value="<?=$b1m['id']?>"><?=$b1m['name']?></option>
                                        <?
                                    }?>
                                </select>
                            </div>
                            <div class="col-sm">
                                Bitrix:&nbsp;
                                <select name="bitrixm" class="form-control">
                                    <?foreach ($bitrixMeasures as $bim){
                                        ?>
                                        <option value="<?=$bim['id']?>"><?=$bim['symbolIntl']?></option>
                                        <?
                                    }?>
                                </select>
                            </div>
                            <div class="col-sm"><br/>
                                <input type="hidden" name="type" value="measure_add"/>
                                <button type="submit" id="addMeasureMatch" class="btn btn-info">Add</button>
                            </div>
                        </div>
                        </form>
                    </div>

                    <br/><br/>
                    <h3>Warehouses matching</h3>
                    <??>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>B1</th>
                            <th>Bitrix</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($warehousesMatch as $warehouse){?>
                            <tr>
                                <td><?=$b1Warehouses[B1Integration\libs\helpers\Helpers::arraySearch($warehouse->b1,$b1Warehouses,'id')]['name']?></td>
                                <td><?=$bitrixWarehouses[B1Integration\libs\helpers\Helpers::arraySearch($warehouse->bitrix,$bitrixWarehouses,'id')]['title']?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="uid" value="<?=$warehouse->uid?>"/>
                                        <input type="hidden" name="type" value="warehouse_del"/>
                                        <button class="btn btn-info" type="submit">x</button>
                                    </form>
                                </td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>

                    <div class="container" style="border: 1px dotted #cccccc">
                        <form id="warehouse_add_form" method="post">
                            <div class="row">
                                <div class="col-sm"><br/>
                                    <strong>Add new match</strong>
                                </div>
                                <div class="col-sm">
                                    B1:&nbsp;
                                    <select name="b1w" class="form-control">
                                        <?foreach ($b1Warehouses as $b1w){
                                            ?>
                                            <option value="<?=$b1w['id']?>"><?=$b1w['name']?></option>
                                            <?
                                        }?>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    Bitrix:&nbsp;
                                    <select name="bitrixw" class="form-control">
                                        <?foreach ($bitrixWarehouses as $biw){
                                            ?>
                                            <option value="<?=$biw['id']?>"><?=$biw['title']?></option>
                                            <?
                                        }?>
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <br/>
                                    <input type="hidden" name="type" value="warehouse_add"/>
                                    <button type="submit" id="addWarehouseMatch" class="btn btn-info">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>



                </div>
                <div class="tab-pane fade" id="v-pills-home" role="tabpanel"
                     aria-labelledby="v-pills-home-tab">
                    <h4>Synchronization</h4>
                    <a href="/handlers/sincData.php" class="btn btn-info btn-lg">Groups items full synchronization</a>
                    <button type="button" class="btn btn-primary btn-lg">Items full synchronization</button>
                    <button type="button" class="btn btn-primary btn-lg">Clients full synchronization</button>
                    <button type="button" class="btn btn-secondary btn-lg">Warehouses full synchronization</button>

                    <div class="log_window" id="log_sync">


                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-mapping" role="tabpanel"
                     aria-labelledby="v-pills-home-tab">
                    <h4>Company</h4>

                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <h4>Logs</h4>
                    <div class="log_window" id="log_work">
                        <?=nl2br(B1Integration\libs\helpers\Logs::getLogData(__DIR__.'/../logs/log.log'))?>
                    </div>
                </div>
                <!--                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>-->
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