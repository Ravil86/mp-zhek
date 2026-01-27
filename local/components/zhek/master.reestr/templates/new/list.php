<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Highloadblock as HL;

?>
<style>
    .list-group {
        /* --bs-list-group-color: var(--bs-body-color);
    --bs-list-group-bg: var(--bs-body-bg); */
        /* --bs-list-group-border-color: black; */
    }
</style>
<? if ($arResult['ACCESS']): ?>

    <div class="position-relative d-flex">
        <div class="position-absolute col-3 top-0! start-100 translate-middle" style="top:-20px">
            <form method="post" action="">
                <?= bitrix_sessid_post() ?>
                <input type="hidden" name="CABINET" value="Y">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" <?= $arResult['REESTR_SUBMIT'] ? 'checked' : '' ?> onChange="this.form.submit()" role="switch" id="switchCheckDefault" name="reestr">
                    <label class="form-check-label" for="switchCheckDefault">Скрыть поданные</label>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12">
        <!-- <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! col-3 mb-2">
            <input id="search" class="ui-ctl-element form-control" type="text" placeholder="найти организацию">
        </div> -->
        <div class="list-group list-group-flush list-group-numbered!">
            <?
            //gg($arResult['OBJECTS_COMPANY']);
            $i = 1;

            foreach ($arResult['OBJECTS_COMPANY'] as $rKey => $row) {
            ?>
                <?
                $setShow = false;
                if (in_array($rKey, $arResult['SET_METER_COMPANY'])) {
                    $setShow = true;
                }
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-0<?= $arResult['REESTR_SUBMIT'] && !$setShow ? ' d-none' : '' ?>" style="--bs-list-group-border-color: #216cb698; --bs-list-group-border-width: 2px">
                    <div class="row w-100 ms-2 gx-0">
                        <div class="col-3 py-2">
                            <div class="row gx-2">
                                <div class="col-auto"></div>
                                <div class="col ms-2! me-auto">
                                    <div class="fw-bold!">#<?= $row['ID']; ?> <?= $row['UF_NAME']; ?></div>
                                    <div class="mt-2">
                                        <a class="text-primary text-center small"
                                            href="/master/contracts/<?= $row["CONTRACT"]["ID"] ?>/" target="_blank">
                                            <i class="revicon-doc"></i><span><?= $row["CONTRACT"]["NUMBER"] ?></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="list-group list-group-flush h-100 d-flex">
                                <?
                                // gg($row['OBJECTS']);
                                ?>
                                <? foreach ($row['OBJECTS'] as $key => $object) { ?>
                                    <?
                                    if (!$object['COUNTER'])    //если нет ПУ
                                        continue;

                                    // if ($row['METERS'][$object['ID']] && !array_diff_key($object['COUNTER'], $row['METERS'][$object['ID']]))
                                    //     continue;
                                    ?>

                                    <? if ($object['ACTIVE']): ?>
                                        <?
                                        $setHide = false;
                                        if ($row['METERS'][$object['ID']]) {

                                            if (!array_keys(array_diff_key($object['COUNTER'], $row['METERS'][$object['ID']])))
                                                $setHide = true;
                                            //     continue;
                                        }
                                        ?>
                                        <div id="obj<?= $object['ID'] ?>" class="list-group-item py-0 h-100 ps-0<?= $arResult['REESTR_SUBMIT'] && $setHide ? ' d-none' : '' ?>" style="--bs-list-group-border-color: #a5a5a5ff;">
                                            <div class="row gx-0! align-items-center">
                                                <?
                                                //gg($object);
                                                //  gg($arResult['LAST_METER'][$object['ID']]);
                                                ?>
                                                <div class="col pe-0">
                                                    <table class="table table-striped table-striped-columns! table-borderless mb-0">
                                                        <? foreach ($object['COUNTER'] as $idPU => $counter) { ?>
                                                            <?
                                                            // if (!$counter['INFO']['UF_ACTIVE']) //ПУ неактивен
                                                            //     continue;
                                                            ?>
                                                            <tr id="counter<?= $idPU ?>"
                                                                class="list-group list-group-horizontal px-0 <?= $row['METERS'][$object['ID']][$idPU] ? ' d-none!' : '' ?>">
                                                                <td class="col-5 list-group-item
                                                                    <?= !$row['METERS'][$object['ID']][$idPU] ? ' text-bg-danger bg-gradient bg-opacity-25 text-black' : '' ?>
                                                                    <?= !$counter['INFO']['UF_ACTIVE'] ? ' bg-opacity-75' : '' ?>
                                                                    ">
                                                                    <div class="row gx-1 justify-content-between">
                                                                        <div class="col-2 small"> <?= $counter['ID']; ?></div>
                                                                        <div class="col-2 text-center"> <?= $counter['TYPE']; ?></div>
                                                                        <div class="col-6"><?= $counter['NUMBER']; ?></div>
                                                                        <div class="col-1"><?= !$counter['INFO']['UF_ACTIVE'] ? 'off' : '' ?></div>
                                                                    </div>
                                                                </td>
                                                                <td class="col list-group-item text-start"> <?= $row['METERS'][$object['ID']][$idPU]; ?></td>
                                                                <td class="col list-group-item text-start"> <?= $row['PREV_METERS'][$object['ID']][$idPU]; ?></td>
                                                                <td class="col-2 list-group-item text-start"> <?= $row['METER_RAZNOST'][$object['ID']][$idPU]; ?></td>
                                                            </tr>
                                                        <? } ?>
                                                        <? foreach ($object['RELATED'] as $idCounter => $related) { ?>
                                                            <?
                                                            //gg($related);
                                                            ?>
                                                            <tr id="relCounter<?= $related['COUNTER']['ID'] ?>" class="list-group list-group-horizontal px-0 <?= $row['METERS'][$object['ID']][$idPU] ? ' d-none!' : '' ?>">
                                                                <td
                                                                    class="col-5 list-group-item<?= !$counter['INFO']['UF_ACTIVE'] ? ' bg-opacity-75' : '' ?><?= $related['UF_MAIN'] ? ' bg-success bg-opacity-25' : '' ?>">
                                                                    <? //= !$row['METERS'][$object['ID']][$idPU] ? ' text-bg-danger bg-gradient bg-opacity-25 text-black' : ''
                                                                    ?>
                                                                    <? // gg($related['UF_MAIN']);
                                                                    ?>
                                                                    <div class="row gx-1 justify-content-between">
                                                                        <div class="col-2 small"><small>#_<?= $related['COUNTER']['ID']; ?></small></div>
                                                                        <div class="col-2 text-center"> <?= $related['COUNTER']['TYPE']['SM']; ?></div>
                                                                        <div class="col-6 small"><?= $related['COUNTER']['UF_NUMBER']; ?></div>
                                                                        <div class="col-1">
                                                                            <span role="button" class="ps-1 <?= $related['UF_MAIN'] ? 'text-success' : 'text-danger' ?>"
                                                                                data-bs-toggle="tooltip"
                                                                                data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - <?= $related['UF_PERCENT'] ?>%">
                                                                                <i class="bi bi-link-45deg fs-5"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="col list-group-item text-start"> <?= $related['LAST_METER']; ?></td>
                                                                <td class="col list-group-item text-start"> <?= $related['PREV_METER']; ?></td>
                                                                <td class="col-2 list-group-item text-start"> <?= $related['METER']; ?></td>
                                                            </tr>
                                                        <? } ?>
                                                    </table>
                                                </div>
                                                <div class="col-4 py-2">
                                                    #<?= $object['ID']; ?> <?= $object['NAME']; ?>
                                                </div>
                                                <div class="col-auto">
                                                    <a class="text-center" href="/master/counter/<?= $object['ID'] ?>" target="_blank"><i class="revicon-pencil-1"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    <? endif;
                                    ?>
                                <? } ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?
                $i++;
                //}
            }
            ?>
        </div>
    </div>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>
<script>
    $(document).ready(function() {

        // Поиск только по столбцу с именами
        /*
        $('#search').keyup(function() {
            // Текст для поиска
            var search = $(this).val();

            // Скрыть все строки tbody таблицы
            $('table tbody tr').hide();

            // Подсчитываем общее количество результатов поиска
            var len = $('table tbody tr:not(.notfound) td.name:contains("' + search + '")').length;

            if (len > 0) {
                // Поиск текста в столбцах и отображение соответствующей строки
                $('table tbody tr:not(.notfound) td.name:contains("' + search + '")').each(function() {
                    $(this).closest('tr').show();
                });
            } else {
                $('.notfound').show();
            }

        });

    });

    // Поиск без учета регистра (Примечание: удалите приведенный ниже скрипт для поиска с учетом регистра)
    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function(elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
*/

        /*$("#search").on("keyup", function() {
                var value = $(this).val();
                console.log(value);

                $("table tr").each(function(index) {
                    if (index != 0) {
                        $row = $(this);
                        var id = $row.find("td:first").text();
                        if (id.indexOf(value) != 0) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    }
                });
            });*/
    });
</script>