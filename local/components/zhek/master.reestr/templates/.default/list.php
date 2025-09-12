<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Highloadblock as HL;

?>
<? if ($arResult['ACCESS']): ?>
    <div class="d-flex">
        <div class="col">
            <?
            // $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            //     'FILTER_ID' => 'filter_' . $arResult['GRID_ID'],
            //     'GRID_ID' => $arResult['GRID_ID'],
            //     // 'FILTER' => [],
            //     'FILTER' => $arResult['GRID']['FILTER'],
            //     'ENABLE_LIVE_SEARCH' => true,
            //     'ENABLE_LABEL' => true,
            // ]);
            ?>
        </div>
        <div class="col-auto">
            <!-- <button class="ui-btn ui-btn-primary mt-2 ms-0" data-bs-toggle="modal" data-bs-target="#addCompany">Добавить организацию</button> -->
            <div id="button"></div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! col-3 mb-2">
            <input id="search" class="ui-ctl-element form-control" type="text" placeholder="найти организацию">
        </div> -->
        <div class="table-responsive">
            <table id="table-reestr" class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <?
                        foreach ($arResult['GRID']['COLUMNS'] as $key => $head): ?>
                            <?
                            if (isset($head['colspan']) && $head['colspan'] == 0)
                                continue;
                            ?>
                            <th scope='col' <?= $head['width'] ? 'width="' . $head['width'] . '"' : '' ?> <?= $head['colspan'] ? 'colspan="' . $head['colspan'] . '"' : 'rowspan="2"' ?>><?= $head['text'] ?? $head['name'] ?></th>
                            <?
                            //endif;
                            //echo "<th scope='col'>{$head['name']}</th>";
                            ?>
                        <? endforeach
                        ?>
                    </tr>
                    <tr>
                        <?
                        foreach ($arResult['GRID']['COLUMNS'] as $key => $head): ?>
                            <?
                            if (!isset($head['colspan']))
                                continue;
                            ?>
                            <th scope='col'><?= $head['name'] ?></th>
                            <? //echo "<th scope='col'>{$head['name']}</th>";
                            ?>
                        <? endforeach
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?
                    foreach ($arResult['GRID']['ROWS'] as $rKey => $row) {

                        // if (empty($row['columns']['COUNTER']))
                        //     continue;
                        // gg($arResult['GRID']['ROW_LAYOUT'][$rKey]);
                        // gg($arResult['GRID']['ROW_LAYOUT'][$rKey]);
                        echo '<tr>';

                        foreach ($arResult['GRID']['COLUMNS'] as $cKey => $col) : ?>
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
                        <? endforeach ?>
                    <?
                        echo '</tr>';
                    }
                    ?>
                    <!-- Отобразить этот <tr>, если при поиске не найдено ни одной записи -->
                    <tr class='notfound'>
                        <td colspan='4'>Запись не найдена</td>
                    </tr>
                </tbody>
            </table>
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