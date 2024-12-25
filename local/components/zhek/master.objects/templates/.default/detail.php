<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

\Bitrix\Main\UI\Extension::load("ui.forms");

if ($arResult['ACCESS']): ?>

    <div class="d-flex">
        <div class="col">
            <div class="card col-10">
                <div class="card-header">
                    <h2 class="h5 mb-0">
                        <?= $arResult['DETAIL']['ORG']['NAME'] ?></h2>
                </div>
                <div class="card-body py-2 lh-sm">
                    <div class="row">
                        <div class="col-4 fs-6 lead text-body-secondary"><em class="small">ИНН:</em> <?= $arResult['DETAIL']['ORG']['INN'] ?></div>
                        <div class="col-8 fs-6 lead text-body-secondary"><em class="small">Адрес:</em> <?= $arResult['DETAIL']['ORG']['ADRES'] ?></div>

                    </div>

                </div>
            </div>
            <? ?>

            <?
            /*$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $arResult['GRID_DETAIL'],
            'GRID_ID' => $arResult['GRID_DETAIL'],
            // 'FILTER' => $arResult['GRID']['FILTER'],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true,
            // "FILTER_PRESETS" => [
            // "CURRENT_YEAR" => [
            // 	"name" => 'Текущий год',
            // 	"default" => true, // если true - пресет по умолчанию
            // 	"fields" => [
            //         "DATE_CREATE_datesel" => "YEAR",
            //         "DATE_CREATE_year" => $curentYear,
            // 	    ]
            // ],
            // "LAST_YEAR" => [
            //     "name" => 'Прошлый год',
            //     "default" => false,
            //     "fields" => [
            //         "DATE_CREATE_datesel" => "YEAR",
            //         "DATE_CREATE_year" => $lastYear,
            //     ]
            // ]
            // ]
        ]);*/
            ?>
        </div>
        <div class="col-auto">
            <div class="d-grid">
                <a class="ui-btn ui-btn-no-caps ui-btn-sm" href="<?= $arResult['FOLDER'] ?>">вернуться назад</a>
                <button class="ui-btn ui-btn-success mt-2 ms-0" data-bs-toggle="modal" data-bs-target="#addObject">добавить объект</button>
            </div>

        </div>

    </div>
    <?
    foreach ($arResult['ITEMS'] as $key => $value): ?>

        <div class="card my-2">

            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title"> #<?= $value['ID']; ?> <?= $value['NAME']; ?></h5>
                        <div class="h6 card-subtitle mb-2 text-body-secondary"><?= $value['ADDRESS']; ?></div>
                    </div>
                    <div class="col-auto">
                        <button class="ui-btn ui-btn-sm ui-btn-secondary" data-bs-toggle="modal" data-bs-target="#counterModal<?= $value['ID']; ?>">добавить счётчик</button>
                    </div>
                </div>
                <?
                $grid_options = new CGridOptions($arResult["GRID_DETAIL"]);


                $snippet = new Bitrix\Main\Grid\Panel\Snippet();
                $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getEditButton();
                $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getRemoveButton();
                //$controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getForAllCheckbox();

                // $controlPanel['GROUPS'][1]['ITEMS'][] = [
                //     'ID'       => 'edit',
                //     'TYPE'     => 'BUTTON',
                //     'TEXT'        => 'добавить',
                //     'CLASS'        => 'icon edit',
                //     'ONCHANGE' => ''
                // ];

                $gridParams = [
                    'GRID_ID' => $arResult['DETAIL']['GRID'] . '_' . $value['ID'],
                    'COLUMNS' => $arResult['DETAIL']['COLUMNS'],
                    'ROWS' => $value['ROWS'],
                    // 'FOOTER' => [
                    //     'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
                    // ],

                    'NAV_OBJECT' => $nav,
                    'AJAX_MODE' => 'Y',
                    //'AJAX_ID' => 'AJAX_'.$arResult['GRID_ID'],
                    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
                    'PAGE_SIZES' => [
                        ['NAME' => "5", 'VALUE' => '5'],
                        ['NAME' => '10', 'VALUE' => '10'],
                        ['NAME' => '20', 'VALUE' => '20'],
                        ['NAME' => '50', 'VALUE' => '50'],
                        ['NAME' => '100', 'VALUE' => '100']
                    ],
                    'SHOW_ROW_CHECKBOXES'       => true,    //Разрешает отображение чекбоксов для строк.
                    'AJAX_OPTION_JUMP'          => 'Y',
                    'SHOW_CHECK_ALL_CHECKBOXES' => false,    //Разрешает отображение чекбоксов "Выбрать все"
                    'SHOW_ROW_ACTIONS_MENU'     => true,    //Разрешает отображение меню действий строки
                    'SHOW_GRID_SETTINGS_MENU'   => true,    //Разрешает отображение меню настройки грида (кнопка с шестеренкой)
                    'SHOW_NAVIGATION_PANEL'     => false,    //Разрешает отображение кнопки панели навигации. (Постраничка, размер страницы и т. д.)
                    'SHOW_PAGINATION'           => false,    //Разрешает отображение постраничной навигации
                    'SHOW_SELECTED_COUNTER'     => false,   //Разрешает отображение счетчика выделенных строк
                    'SHOW_TOTAL_COUNTER'        => false,    //Разрешает отображение счетчика общего количества строк на всех страницах
                    'SHOW_PAGESIZE'             => false,    //Разрешает отображение выпадающего списка с выбором размера страницы
                    'SHOW_ACTION_PANEL'         => true,    //Разрешает отображение панели групповых действий
                    'ALLOW_COLUMNS_SORT'        => true,    //Разрешает сортировку колонок перетаскиванием
                    'ALLOW_COLUMNS_RESIZE'      => true,    //Разрешает изменение размера колонок
                    'ALLOW_HORIZONTAL_SCROLL'   => true,    //Разрешает горизонтальную прокрутку, если грид не помещается по ширине
                    'ALLOW_SORT'                => true,    //Разрешает сортировку по клику на заголовок колонки
                    'ALLOW_PIN_HEADER'          => true,    //Разрешает закрепление шапки грида к верху окна браузера при прокрутке
                    'AJAX_OPTION_HISTORY'       => 'Y',
                    'SHOW_GROUP_EDIT_BUTTON'    => true,    //Разрешает вывод стандартной кнопки "Редактировать" в панель групповых действий
                    'ALLOW_INLINE_EDIT'         => true,    //Разрешает инлайн-редактирование строк
                    'ALLOW_CONTEXT_MENU'        => true,    //Разрешает вывод контекстного меню по клику правой кнопкой на строку
                    'ACTION_PANEL'              => $controlPanel,
                    /*'ACTION_PANEL'              => [
                        'GROUPS' => [
                            'TYPE' => [
                                'ITEMS' => [
                                    [
                                        'ID'       => 'edit',
                                        'TYPE'     => 'BUTTON',
                                        'TEXT'        => 'Редактировать',
                                        'CLASS'        => 'icon edit',
                                        'ONCHANGE' => ''
                                    ],
                                    [
                                        'ID'       => 'delete',
                                        'TYPE'     => 'BUTTON',
                                        'TEXT'     => 'Удалить',
                                        'CLASS'    => 'icon remove',
                                        //'ONCHANGE' => $onchange->toArray()
                                    ],
                                ],
                            ]
                        ],
                    ],*/
                ];
                $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);
                ?>
            </div>
        </div>
        <? // dump($arResult);
        ?>
        <div class="modal fade" id="counterModal<?= $value['ID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <div class="modal-title">Добавить счётчик для
                                <h4 class="modal-title"><?= $value['NAME']; ?></h4>
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <input class="ui-ctl-element" type="hidden" name="ADD" value="Y">
                            <input class="ui-ctl-element" type="hidden" name="FIELDS[UF_ORG]" value="<?= $arResult['DETAIL']['ORG']['ID']; ?>">
                            <input class="ui-ctl-element" type="hidden" name="FIELDS[UF_OBJECT]" value="<?= $value['ID']; ?>">
                            <?= bitrix_sessid_post() ?>
                            <div class="row gx-2">
                                <div class="col-12 col-md">
                                    <label>Наименование cчетчика</label>
                                    <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element" type="text" name="FIELDS[UF_NAME]" placeholder="Наименование cчетчика">
                                    </div>
                                </div>
                                <div class="col-12 col-md">
                                    <label>Номер cчетчика</label>
                                    <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element" type="text" name="FIELDS[UF_NUMBER]" placeholder="Номер cчетчика">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-2 mt-3">
                                <div class="col-12 col-md-6">
                                    <label>Тип счетчика</label>
                                    <? // dump($arResult['SERVICE_LIST'])
                                    ?>
                                    <div class="ui-ctl-dropdown! ui-ctl! ui-ctl-after-icon! ui-ctl-w100">
                                        <!-- <div class="ui-ctl-after ui-ctl-icon-angle"></div> -->
                                        <select class="selectpicker" data-width="100%" data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border" multiple name="FIELDS[UF_TYPE][]">
                                            <? foreach ($arResult['SERVICE_LIST'] as $key => $value): ?>
                                                <option value="<?= $key ?>"><?= $value['NAME'] ?></option>
                                            <? endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md">
                                    <label>Дата счетчика</label>
                                    <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element" type="date" name="FIELDS[UF_DATE]" placeholder="Дата счетчика">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="ui-btn ui-btn-success">Сохранить</button>
                            <button type="button" class="ui-btn ui-btn-link" data-bs-dismiss="modal">Закрыть</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    <? endforeach; ?>
    <div class="modal fade" id="addObject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <div class="modal-title">Добавить объект
                            <h4 class="modal-title"><?= $value['NAME']; ?></h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="ui-ctl-element" type="hidden" name="ADD_OBJECT" value="Y">
                        <input class="ui-ctl-element" type="hidden" name="FIELDS[UF_ORG]" value="<?= $arResult['DETAIL']['ORG']['ID']; ?>">
                        <?= bitrix_sessid_post() ?>
                        <div class="row gx-2">
                            <div class="col-12 col-md">
                                <label>Наименование объекта</label>
                                <div class="ui-ctl ui-ctl-textarea ui-ctl-resize-y ui-ctl-w100">
                                    <textarea class="ui-ctl-element" name="FIELDS[UF_NAME]" placeholder="Наименование объекта"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-2 mt-3">
                            <div class="col-12 col-md">
                                <label>Адрес</label>
                                <div class="ui-ctl ui-ctl-textarea ui-ctl-lg! ui-ctl-w100">
                                    <textarea class="ui-ctl-element" name="FIELDS[UF_ADRES]" placeholder="Адрес"></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md">
                                <label>Договор</label>
                                <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                    <input class="ui-ctl-element" type="text" name="FIELDS[UF_DOGOVOR]" placeholder="Договор">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="ui-btn ui-btn-success">Сохранить</button>
                        <button type="button" class="ui-btn ui-btn-link" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">

        </div>
    </div>

<? else: ?>
    <font class="errortext">Ошибка доступа</font>
<? endif; ?>

<script>
    // var reloadParams = {
    //     apply_filter: 'Y',
    //     clear_nav: 'Y'
    // };
    // var gridObject = BX.Main.gridManager.getById('zhek_master_objects_detail_1'); // Идентификатор грида

    // if (gridObject.hasOwnProperty('instance')) {
    //     gridObject.instance.reloadTable('POST', reloadParams);
    // }
</script>