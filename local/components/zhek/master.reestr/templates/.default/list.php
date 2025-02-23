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
        <?
        $grid_options = new CGridOptions($arResult["GRID_ID"]);
        $nav_params = $grid_options->GetNavParams(array("nPageSize" => $arResult['PAGE_SIZE']));

        $nav = new Bitrix\Main\UI\PageNavigation($arResult["GRID_ID"]);
        $nav->allowAllRecords(true)
            ->setRecordCount($arResult['GRID']['COUNT']) //Для работы кнопки "показать все"
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $snippet = new Bitrix\Main\Grid\Panel\Snippet();
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getEditButton();
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getRemoveButton();

        // dump($arResult['GRID']['ROWS']);

        $gridParams = [
            'GRID_ID' => $arResult['GRID_ID'],
            'COLUMNS' => $arResult['GRID']['COLUMNS'],
            'ROWS' => $arResult['GRID']['ROWS'],
            'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
            'SHOW_ROW_CHECKBOXES' => false,
            'DEFAULT_PAGE_SIZE' => $arResult['PAGE_SIZE'],
            'NAV_OBJECT' => $nav,
            // 'CURRENT_PAGE' => $nav->getCurrentPage(),
            'AJAX_MODE' => 'Y',
            'AJAX_ID' => 'AJAX_' . $arResult['GRID_ID'],
            // 'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
            'PAGE_SIZES' => [
                ['NAME' => "5", 'VALUE' => '5'],
                ['NAME' => '10', 'VALUE' => '10'],
                ['NAME' => '20', 'VALUE' => '20'],
                ['NAME' => '50', 'VALUE' => '50'],
                ['NAME' => '100', 'VALUE' => '100']
            ],
            'SHOW_CHECK_ALL_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU' => false,
            'SHOW_GRID_SETTINGS_MENU' => true,
            'SHOW_NAVIGATION_PANEL' => true,
            'SHOW_PAGINATION' => true,
            'SHOW_SELECTED_COUNTER' => false,
            'SHOW_TOTAL_COUNTER' => false,
            'SHOW_PAGESIZE' => true,
            'SHOW_ACTION_PANEL' => false,
            'ALLOW_COLUMNS_SORT' => false,  //Разрешает сортировку колонок перетаскиванием.
            'ALLOW_COLUMNS_RESIZE' => false, //Разрешает изменение размера колонок.
            'ALLOW_HORIZONTAL_SCROLL' => true,
            'ALLOW_SORT' => false,
            'ALLOW_PIN_HEADER' => false,
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_HISTORY' => 'N',
            'ENABLE_COLLAPSIBLE_ROWS' => false,  //групировка строк с разворачиванием
            'ALLOW_STICKED_COLUMNS' => false, //Разрешает закрепление колонок с параметром sticked при горизонтальной прокрутке
            'ROW_LAYOUT' => $arResult['GRID']['ROW_LAYOUT'],
            /*'ROW_LAYOUT' => [
                [
                    // ['column' => 'UF_NAME', 'rowspan' => 2],
                    // ['column' => 'UF_ADDRESS'],
                    // ['column' => 'col_2'],
                    // ['column' => 'col_3']
                ],
                [
                    ['data' => 'data_field_5', 'colspan' => 3],
                ],
            ],*/
            // 'ACTION_PANEL' => $controlPanel,
            // 'SHOW_MORE_BUTTON' => true, //передавать false, если CURRENT_PAGE = последняя страница
            // 'ENABLE_NEXT_PAGE' => true,
            // 'NAV_PARAM_NAME' => 'SHOW_MORE', //параметр приходит в $_REQUEST, нужно передать в свой компонент и обработать для выборки данных
            // 'CURRENT_PAGE' => $nav->getCurrentPage(),
        ];
        //$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);

        // gg($arResult['GRID']['ROW_LAYOUT']);
        ?>
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
                            <th scope='col' <?= $head['colspan'] ? 'colspan="' . $head['colspan'] . '"' : 'rowspan="2"' ?>><?= $head['text'] ?? $head['name'] ?></th>
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
                        // gg($arResult['GRID']['ROW_LAYOUT'][$rKey]);
                        echo '<tr>';
                        // gg($row);
                        foreach ($arResult['GRID']['COLUMNS'] as $cKey => $col) : ?>
                            <?
                            $layout =  $arResult['GRID']['ROW_LAYOUT'][$rKey][$cKey];

                            if ($layout['column'] == $col['id']):
                            ?>
                                <? $valueTD = $row['columns'][$col['id']]; ?>
                                <td scope="row" <?= $layout['rowspan'] ? 'rowspan="' . $layout['rowspan'] . '"' : '' ?>
                                    class="<?= is_array($valueTD) ? 'p-0 border-bottom-0 align-baseline!' : '' ?><?= isset($col['colspan']) ? ' text-center' : '' ?>">
                                    <? if (is_array($valueTD)): ?>
                                        <div class="table mb-0 d-flex flex-column gy-1 h-100">
                                            <? foreach ($valueTD as $key => $value): ?>
                                                <div class="table-row align-items-center! px-2 border-bottom"><?= $value ?></div>
                                            <? endforeach ?>
                                        </div>
                                    <? else: ?>

                                        <?= $valueTD; ?>
                                    <? endif; ?>
                                </td>
                            <? endif; ?>
                        <? endforeach ?>
                    <?
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>
<script>
    const addCompany = new bootstrap.Modal('#addCompany')
    const addUser = new bootstrap.Modal('#addUser')

    var splitButton = new BX.UI.SplitButton({
        text: "Добавить организацию",
        color: BX.UI.Button.Color.PRIMARY,
        // size: BX.UI.Button.Size.LARGE,
        // icon: BX.UI.Button.Icon.BUSINESS,
        menu: {
            items: [{
                    text: "Добавить пользователя",
                    onclick: function(button, event) {
                        addUser.show()
                    },
                },
                // {
                //     delimiter: true
                // },
                // {
                //     text: "Закрыть",
                //     onclick: function(event, item) {
                //         item.getMenuWindow().close();
                //     }
                // }
            ],
        },
        mainButton: {
            onclick: function(button, event) {
                addCompany.show()
            },
            // props: {
            //     href: "/"
            // },
            // tag: BX.UI.Button.Tag.LINK
        },
        menuButton: {
            onclick: function(button, event) {
                button.setActive(!button.isActive());
            },
            props: {
                "data-abc": "123"
            },
            events: {
                mouseenter: function(button, event) {
                    console.log("menu button mouseenter", button, event);
                }
            },
        },
    });

    (function() {
        var container = document.getElementById("button");
        //splitButton.renderTo(container);
    })();
</script>