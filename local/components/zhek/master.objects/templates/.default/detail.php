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
                        <?= $arResult['DETAIL']['ORG']['UF_NAME'] ?></h2>
                </div>
                <div class="card-body py-2 lh-sm">
                    <div class="row">

                        <div class="col-4 fs-6 lead text-body-secondary">
                            <? if ($arResult['DETAIL']['ORG']['UF_INN']): ?>
                                <em class="small">ИНН:</em> <?= $arResult['DETAIL']['ORG']['UF_INN'] ?>
                            <? endif; ?>
                        </div>
                        <div class="col-8 fs-6 lead text-body-secondary">
                            <? if ($arResult['DETAIL']['ORG']['UF_ADDRESS']): ?>
                                <em class="small">Адрес:</em> <?= $arResult['DETAIL']['ORG']['UF_ADDRESS'] ?>
                            <? endif; ?>
                        </div>
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
    <script>
        // var reloadParams = {
        //     apply_filter: 'Y',
        //     clear_nav: 'Y'
        // };
    </script>
    <?
    $i = 1;

    $snippet = new Bitrix\Main\Grid\Panel\Snippet();
    $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getEditButton();

    if ($arResult['ADMIN'])
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getRemoveButton();

    foreach ($arResult['ITEMS'] as $key => $value): ?>

        <div class="card my-2">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title"><?= $i ?>. <?= $value['NAME']; ?> (#<?= $value['ID']; ?>)</h5>
                        <div class="h6 card-subtitle mb-2 text-body-secondary"><?= $value['ADDRESS']; ?></div>
                    </div>
                    <div class="col-auto">
                        <button class="ui-btn ui-btn-sm ui-btn-secondary" data-bs-toggle="modal" data-bs-target="#counterModal<?= $value['ID']; ?>">добавить счётчик</button>
                    </div>
                </div>
                <div class="grid_form">
                    <?
                    $grid_options = new CGridOptions($arResult["GRID_DETAIL"]);

                    // dump($controlPanel);
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
                        'AJAX_MODE' => 'N',     //не обновляет
                        'AJAX_ID' => 'AJAX_' . $arResult['GRID_ID'],
                        //'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
                        'PAGE_SIZES' => [
                            ['NAME' => "5", 'VALUE' => '5'],
                            ['NAME' => '10', 'VALUE' => '10'],
                            ['NAME' => '20', 'VALUE' => '20'],
                            ['NAME' => '50', 'VALUE' => '50'],
                            ['NAME' => '100', 'VALUE' => '100']
                        ],
                        'SHOW_ROW_CHECKBOXES'       => true,    //Разрешает отображение чекбоксов для строк.
                        'AJAX_OPTION_JUMP'          => 'N',
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
                        'AJAX_OPTION_HISTORY'       => 'N',
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
        </div>
        <? // dump($arResult);
        ?>
        <div class="modal counter-modal fade" id="counterModal<?= $value['ID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-id="<?= $value['ID']; ?>">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form class="counter_add needs-validation" method="post" novalidate>
                        <div class="modal-header">
                            <div class="modal-title">Добавить счётчик для
                                <h4 class="modal-title"><?= $value['NAME']; ?></h4>
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <input class="ui-ctl-element" type="hidden" name="ADD_COUNTER" value="Y">
                            <input class="ui-ctl-element" type="hidden" name="FIELDS[UF_ORG]" value="<?= $arResult['DETAIL']['ORG']['ID']; ?>">
                            <input class="ui-ctl-element" type="hidden" name="FIELDS[UF_OBJECT]" value="<?= $value['ID']; ?>">
                            <?= bitrix_sessid_post() ?>
                            <div class="row gx-2">
                                <div class="col-12 col-md">
                                    <label>Наименование cчетчика</label>
                                    <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element form-control" type="text" name="FIELDS[UF_NAME]" placeholder="Наименование cчетчика">
                                    </div>
                                </div>
                                <div class="col-12 col-md">
                                    <label>Номер cчетчика</label>
                                    <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element form-control" type="text" name="FIELDS[UF_NUMBER]" placeholder="Номер cчетчика" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-2 mt-3">
                                <div class="col-12 col-md-4">
                                    <label>Тип счетчика</label>
                                    <? // dump($arResult['SERVICE_LIST'])
                                    ?>
                                    <div class="ui-ctl-dropdown! ui-ctl! ui-ctl-after-icon! ui-ctl-w100">
                                        <!-- <div class="ui-ctl-after ui-ctl-icon-angle"></div> -->
                                        <select class="selectpicker" data-width="100%" data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border" multiple name="FIELDS[UF_TYPE][]" required>
                                            <? foreach ($arResult['SERVICE_LIST'] as $key => $value): ?>
                                                <option value="<?= $key ?>"><?= $value['NAME'] ?></option>
                                            <? endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label>Дата установки</label>
                                    <div class="ui-ctl ui-ctl-after-icon ui-ctl-date ui-ctl-w100">
                                        <div class="ui-ctl-after ui-ctl-icon-calendar"></div>
                                        <input type="text" class="ui-ctl-element form-control" name="FIELDS[UF_DATE]" value="<?= date('d.m.Y'); ?>" required>
                                    </div>
                                    <!-- <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element form-control" type="date" name="FIELDS[UF_CHECK]" placeholder="Сл. дата поверки" required>
                                    </div> -->
                                </div>
                                <div class="col-12 col-md-4">
                                    <label>Дата очередной поверки</label>
                                    <div class="ui-ctl ui-ctl-after-icon ui-ctl-date ui-ctl-w100">
                                        <div class="ui-ctl-after ui-ctl-icon-calendar"></div>
                                        <input type="text" class="ui-ctl-element form-control" name="FIELDS[UF_CHECK]" placeholder="Сл. дата поверки" required>
                                    </div>
                                    <!-- <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element form-control" type="date" name="FIELDS[UF_CHECK]" placeholder="Сл. дата поверки" required>
                                    </div> -->
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
        <? $i++; ?>
    <? endforeach; ?>
    <div class="modal fade" id="addObject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form class="objects_add needs-validation" method="post" novalidate>
                    <div class="modal-header">
                        <div class="modal-title">Добавить объект для
                            <h4 class="modal-title"><?= $arResult['DETAIL']['ORG']['UF_NAME']; ?></h4>
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
                                    <textarea class="ui-ctl-element form-control" name="FIELDS[UF_NAME]" placeholder="Наименование объекта" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-2 mt-3">
                            <div class="col-12 col-md">
                                <label>Адрес</label>
                                <div class="ui-ctl ui-ctl-textarea ui-ctl-lg! ui-ctl-w100">
                                    <textarea class="ui-ctl-element form-control" name="FIELDS[UF_ADRES]" placeholder="Адрес" required></textarea>
                                </div>
                            </div>
                            <!-- <div class="col-12 col-md">
                                <label>Договор</label>
                                <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                    <input class="ui-ctl-element" type="text" name="FIELDS[UF_DOGOVOR]" placeholder="Договор">
                                </div>
                            </div> -->
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
    <font class="errortext">нет доступа</font>
<? endif; ?>

<script>
    (function() {
        let list = document.querySelectorAll('.counter-modal');
        var listArray = [...list];

        listArray.forEach(element => {
            // console.log(element.id);
            const objectsModal = document.getElementById(element.id)
            // console.log(exampleModal);
            if (objectsModal) {
                // console.log('objectsModal', objectsModal);

                objectsModal.addEventListener('show.bs.modal', event => {

                    // console.log('event', event.target);

                    var div_list = event.target.querySelectorAll('.ui-ctl-date'); // returns NodeList

                    var div_array = [...div_list]; // converts NodeList to Array
                    div_array.forEach(div => {
                        // do something awesome with each div

                        const input = div.querySelector('input');
                        // console.log('input', input);

                        const button = input.closest(".ui-ctl-date")
                        // console.log('button', button);

                        let picker = null;
                        const getPicker = () => {
                            if (picker === null) {
                                picker = new BX.UI.DatePicker.DatePicker({
                                    targetNode: input,
                                    inputField: input,
                                    enableTime: false,
                                    useInputEvents: false,
                                });
                            }

                            return picker;
                        };

                        BX.Event.bind(button, "click", () => getPicker().show());
                    });


                    var id = objectsModal.getAttribute('data-id')
                    var form = objectsModal.querySelector('form')

                    form.addEventListener('submit', function(event) {

                        event.preventDefault()

                        if (!form.checkValidity()) {
                            event.stopPropagation()
                        } else {
                            // var gridObject = BX.Main.gridManager.getById('<?= $arResult['DETAIL']['GRID'] ?>_' + id); // Идентификатор грида
                            // console.log('gridObject', gridObject);
                            // if (gridObject.hasOwnProperty('instance')) {
                            //     gridObject.instance.reloadTable('POST', reloadParams);
                            // }
                            //
                        }
                        form.classList.add('was-validated')
                    }, false)

                })
            }

        })



        // var forms = document.querySelectorAll('.objects_add')
        // Array.prototype.slice.call(forms)
        //     .forEach(function(form) {

        //         form.addEventListener('submit', function(event) {

        //             event.preventDefault()

        //             if (!form.checkValidity()) {
        //                 event.stopPropagation()
        //             }
        //             form.classList.add('was-validated')
        //         }, false)
        //     })


    })()
</script>