<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Highloadblock as HL;

?>
<? if ($arResult['ACCESS']): ?>
    <div class="col-md-12">
        <!-- <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! col-3 mb-2">
            <input id="search" class="ui-ctl-element form-control" type="text" placeholder="найти организацию">
        </div> -->

        <div class="list-group list-group-numbered">
            <?
            //gg($arResult['OBJECTS_COMPANY']);
            foreach ($arResult['OBJECTS_COMPANY'] as $rKey => $row) {
            ?>
                <div class="list-group-item d-flex justify-content-between align-items-start pe-0">
                    <div class="row w-100 ms-2 gx-0">
                        <div class="col-3">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold!"><?= $row['UF_NAME']; ?> #<?= $row['ID']; ?></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="list-group list-group-flush">
                                <? // gg($row['METERS']);
                                ?>
                                <? foreach ($row['OBJECTS'] as $key => $object) { ?>
                                    <?

                                    if ($object['ACTIVE'] == null || $object['ACTIVE']):
                                        // continue;
                                    ?>
                                        <div class="list-group-item py-0!">
                                            <div class="row gx-0!">
                                                <div class="col-4">
                                                    #<?= $object['ID']; ?> <?= $object['NAME']; ?>
                                                </div>
                                                <?
                                                //gg($object);
                                                //  gg($arResult['LAST_METER'][$object['ID']]);
                                                ?>
                                                <div class="col pe-0">
                                                    <div class="list-group list-group-flush">
                                                        <? foreach ($object['COUNTER'] as $idPU => $counter) { ?>
                                                            <? // gg($arResult['LAST_METER'][$object['ID']][$idPU]);
                                                            // gg($idPU);
                                                            ?>
                                                            <div class="list-group-item px-0">
                                                                <?= $counter;
                                                                ?>
                                                            </div>
                                                        <? } ?>
                                                    </div>
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

                <? /*foreach ($arResult['GRID']['COLUMNS'] as $cKey => $col) : ?>
                    <?
                    $layout =  $arResult['GRID']['ROW_LAYOUT'][$rKey][$cKey];

                    if ($layout['column'] == $col['id']):
                    ?>
                        <? $valueTD = $row['columns'][$col['id']];
                        // gg($layout);
                        ?>
                        <td scope="row" <?= $layout['rowspan'] ? 'rowspan="' . $layout['rowspan'] . '"' : '' ?>
                            class="<?= $layout['column'] == 'UF_NAME' ? 'name' : '' ?><?= is_array($valueTD) ? 'p-0 border-bottom-0 align-baseline!' : '' ?><?= isset($col['colspan']) || $col['center'] ? ' text-center' : '' ?>">
                            <? if (is_array($valueTD)): ?>
                                <div class="table mb-0 d-flex flex-column gy-1 h-100">
                                    <? foreach ($valueTD as $key => $value): ?>
                                        <div class="table-row w-100! px-2 border-bottom<?= $row['columns']['ALERT'][$key] && $arParams['CLEAR_DATA'] == 'Y' ? ' text-bg-danger' : '' ?>"><?= $value ?></div>
                                    <? endforeach ?>
                                </div>
                            <? else: ?>
                                <?
                                // Редактирование только если значения введены Организацией
                                // if ($col['id'] == 'EDIT' && $row['columns']['ALERT'][$key])
                                //     continue;
                                ?>
                                <?= $valueTD;
                                ?>
                            <? endif; ?>
                        </td>
                    <? endif; ?>
                <? endforeach*/ ?>
            <?

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