<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// $APPLICATION->AddHeadScript("//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js");
// $APPLICATION->AddHeadScript("//cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js");
// $APPLICATION->SetAdditionalCSS("//cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css", true);


use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Highloadblock as HL;

\Bitrix\Main\UI\Extension::load("ui");
// \Bitrix\Main\UI\Extension::load('ui.entity-selector');
\Bitrix\Main\UI\Extension::load("ui.select");
\Bitrix\Main\UI\Extension::load('ui.entity-selector');
\Bitrix\Main\UI\Extension::load("ui.alerts");

?>
<? if ($arResult['ACCESS']): ?>
    <div class="d-flex">
        <div class="col">
            <?
            $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
                'FILTER_ID' => 'filter_' . $arResult['GRID_ID'],
                'GRID_ID' => $arResult['GRID_ID'],
                // 'FILTER' => [],
                'FILTER' => $arResult['GRID']['FILTER'],
                'ENABLE_LIVE_SEARCH' => true,
                'ENABLE_LABEL' => true,
            ]);

            ?>
        </div>
        <div class="col-auto">
            <button class="ui-btn ui-btn-primary mt-2 ms-0" data-bs-toggle="modal" data-bs-target="#addContract">Добавить контракт</button>
        </div>
    </div>
    <div class="col-md-12">
        <?
        $grid_options = new CGridOptions($arResult["GRID_ID"]);

        //размер страницы в постраничке (передаем умолчания)
        $nav_params = $grid_options->GetNavParams();

        $curentYear = date('Y');
        $lastYear = date('Y', strtotime('-1 year'));

        $nav = new Bitrix\Main\UI\PageNavigation($arResult["GRID_ID"]);
        $nav->allowAllRecords(true)
            ->setRecordCount($arResult['GRID']['COUNT']) //Для работы кнопки "показать все"
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        /*$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $arResult['GRID_ID'],
            'GRID_ID' => $arResult['GRID_ID'],
            'FILTER' => $arResult['GRID']['FILTER'],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true,
            "FILTER_PRESETS" => [
                "CURRENT_YEAR" => [
                    "name" => 'Текущий год',
                    "default" => true, // если true - пресет по умолчанию
                    "fields" => [
                        "DATE_CREATE_datesel" => "YEAR",
                        "DATE_CREATE_year" => $curentYear,
                    ]
                ],
                "LAST_YEAR" => [
                    "name" => 'Прошлый год',
                    "default" => false,
                    "fields" => [
                        "DATE_CREATE_datesel" => "YEAR",
                        "DATE_CREATE_year" => $lastYear,
                    ]
                ]
            ]
        ]);*/
        $snippet = new Bitrix\Main\Grid\Panel\Snippet();
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getEditButton();
        if ($arResult['ADMIN'])
            $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getRemoveButton();

        $gridParams = [
            'GRID_ID' => $arResult['GRID_ID'],
            'COLUMNS' => $arResult['GRID']['COLUMNS'],
            'ROWS' => $arResult['GRID']['ROWS'],
            'FOOTER' => [
                'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
            ],
            'SHOW_ROW_CHECKBOXES' => $arResult['ADMIN'] || $arResult['MODERATOR'] ?: false,
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
            'AJAX_OPTION_JUMP' => 'Y',
            'SHOW_CHECK_ALL_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU' => false,
            'SHOW_GRID_SETTINGS_MENU' => true,
            'SHOW_NAVIGATION_PANEL' => true,
            'SHOW_PAGINATION' => true,
            'SHOW_SELECTED_COUNTER' => $arResult['ADMIN'] || $arResult['MODERATOR'] ?: false,
            'SHOW_TOTAL_COUNTER' => true,
            'SHOW_PAGESIZE' => true,
            'SHOW_ACTION_PANEL' => $arResult['ADMIN'] || $arResult['MODERATOR'] ?: false,
            'ALLOW_COLUMNS_SORT' => true,
            'ALLOW_COLUMNS_RESIZE' => true,
            'ALLOW_HORIZONTAL_SCROLL' => true,
            'ALLOW_SORT' => true,
            'ALLOW_PIN_HEADER' => true,
            'AJAX_OPTION_HISTORY' => 'N',
            'ACTION_PANEL' => $controlPanel,
            /*'ACTION_PANEL'              => [
        'GROUPS' => [
            'TYPE' => [
                'ITEMS' => [
                    [
                        'ID'    => 'set-type',
                        'TYPE'  => 'DROPDOWN',
                        'ITEMS' => [
                            ['VALUE' => '', 'NAME' => '- Выбрать -'],
                            ['VALUE' => 'plus', 'NAME' => 'Поступление'],
                            ['VALUE' => 'minus', 'NAME' => 'Списание']
                        ]
                    ],
                    [
                        'ID'       => 'edit',
                        'TYPE'     => 'BUTTON',
                        'TEXT'        => 'Редактировать',
                        'CLASS'        => 'icon edit',
                       // 'ONCHANGE' => $onchange->toArray()
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
        <div class="modal fade" id="addContract" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <? // gg($arResult);
                    ?>
                    <form class="contract_add needs-validation!" method="post" novalidate>
                        <div class="modal-header">
                            <h4 class="modal-title">Добавить контракт</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <? //dump($arResult['COMPANY_LIST']);
                            ?>
                            <? //dump($arResult['STATUS_LIST']);
                            ?>
                            <input class="ui-ctl-element" type="hidden" name="ADD_CONTRACT" value="Y">
                            <!-- <input type="hidden" id="UF_COMPANY" class="ui-ctl-element" name="FIELDS[UF_COMPANY]" required>
                            <input type="hidden" id="UF_SERVICE" class="ui-ctl-element" name="FIELDS[UF_SERVICE]" required> -->
                            <?= bitrix_sessid_post() ?>
                            <div class="row gx-2 gy-2">
                                <div class="col-12 col-md-6">
                                    <label>Организация</label>
                                    <select class="selectpicker"
                                        data-width="100%"
                                        data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border"
                                        name="FIELDS[UF_COMPANY]" required>
                                        <? // gg($arResult['COMPANY_JSON']);
                                        ?>
                                        <option value="">-</option>
                                        <? foreach ($arResult['COMPANY_JSON'] as $key => $value): ?>
                                            <option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>
                                        <? endforeach ?>
                                    </select>
                                    <!-- <select class="selectpicker" data-live-search="true">
                                        <? // foreach ($variable as $key => $value):
                                        ?>
                                        <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
                                        <? // endforeach;
                                        ?>
                                    </select> -->
                                    <!-- <div id="org"></div> -->

                                    <?/*
                                    <div data-name="UF_COMPANY" data-type="SELECT" class="main-ui-filter-wield-with-label main-ui-filter-date-group main-ui-control-field-group">
                                        <!-- <span class="main-ui-control-field-label">Организация</span> -->
                                        <div data-name="UF_COMPANY"
                                            data-items='<?= \Bitrix\Main\Web\Json::encode($arResult['COMPANY1_JSON']); ?>'
                                            data-params='<?= \Bitrix\Main\Web\Json::encode(['isMulti' => false]); ?>'
                                            id="select" class="main-ui-control main-ui-select">
                                            <span class="main-ui-select-name">Выберите</span>
                                            <span class="main-ui-square-search">
                                                <input type="text" tabindex="2" class="main-ui-square-search-item">
                                            </span>
                                        </div>
                                    </div>
                                    */ ?>

                                    <!-- <div class="ui-ctl ui-ctl-textarea ui-ctl-resize-y ui-ctl-w100">
                                        <textarea class="ui-ctl-element" name="FIELDS[UF_NAME]" placeholder="Наименование организации"></textarea>
                                    </div> -->
                                </div>
                                <div class="col-12 col-md-6">
                                    <label>Услуги</label>
                                    <select class="selectpicker" data-width="100%"
                                        data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border" multiple
                                        name="FIELDS[UF_SERVICE][]"
                                        required>
                                        <? foreach ($arResult['SERVICE_JSON'] as $key => $value): ?>
                                            <option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>
                                        <? endforeach ?>
                                    </select>
                                    <!-- <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element" type="text" name="FIELDS[UF_INN]" placeholder="ИНН">
                                        <div id="service"></div>
                                    </div> -->
                                    <?/*
                                    <div data-name="UF_SERVICE" data-type="SELECT" class="main-ui-filter-wield-with-label main-ui-filter-date-group main-ui-control-field-group">
                                        <!-- <span class="main-ui-control-field-label">Множественный выбор</span> -->
                                        <div data-name="UF_SERVICE"
                                            data-items='<?= \Bitrix\Main\Web\Json::encode($arResult['COMPANY1_JSON']); ?>'
                                            data-params='<?= \Bitrix\Main\Web\Json::encode(['isMulti' => true]); ?>'
                                            id="select2" class="main-ui-control main-ui-multi-select">

                                            <span class="main-ui-square-container"></span>
                                            <span class="main-ui-square-search"><input type="text" tabindex="2" class="main-ui-square-search-item"></span>
                                            <span class="main-ui-hide main-ui-control-value-delete"><span class="main-ui-control-value-delete-item"></span></span>
                                        </div>
                                    </div>
                                     */ ?>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label>Дата</label>
                                    <div class="ui-ctl ui-ctl-after-icon ui-ctl-date ui-ctl-w100">
                                        <div class="ui-ctl-after ui-ctl-icon-calendar"></div>
                                        <input type="text" id="" class="ui-ctl-element form-control" name="FIELDS[UF_DATE]" value="<?= date('d.m.Y'); ?>" required>
                                    </div>
                                    <!-- <div class="ui-ctl ui-ctl-textarea ui-ctl-lg! ui-ctl-w100">
                                        <textarea class="ui-ctl-element" name="FIELDS[UF_ADDRESS]" placeholder="Адрес"></textarea>
                                    </div> -->
                                </div>
                                <div class="col-12 col-md-3">
                                    <label>Номер</label>
                                    <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element form-control" type="number" name="FIELDS[UF_NUMBER]" placeholder="Номер" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label>Год</label>

                                    <select class="selectpicker"
                                        data-width="100%"
                                        data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border"
                                        name="FIELDS[UF_YEAR]" required>
                                        <? // gg($arResult['COMPANY_JSON']);
                                        ?>
                                        <? foreach ($arResult['YEAR_LIST'] as $key => $value): ?>

                                            <option value="<?= $key ?>" <?= $value['CODE'] == date('Y') ? 'selected="selected"' : '' ?>><?= $value['VALUE'] ?></option>
                                        <? endforeach ?>
                                    </select>
                                    <!-- <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                        <input class="ui-ctl-element form-control" type="text" name="FIELDS[UF_YEAR]" placeholder="Год" required>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-8">
                                <div class="col-auto">
                                    <div id="message" class="ui-alert ui-alert-danger d-none" style="display:none!">
                                    </div>
                                </div>
                            </div>
                            <div class="col"></div>
                            <div class="col-auto">
                                <button type="submit" class="ui-btn ui-btn-success">Сохранить</button>
                                <button type="button" class="ui-btn ui-btn-link" data-bs-dismiss="modal">Закрыть</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        (function() {

            /*const options = <? //= \Bitrix\Main\Web\Json::encode($arResult['COMPANY_JSON']);
                                ?>;
            // console.log('options', options);

            const selectOrg = new BX.Ui.Select({
                options,
                value: '',
                isSearchable: true,
                placeholder: 'Выберите организацию',
                // containerClassname: '',
            });
            selectOrg.subscribe('update', (e) => {
                // console.log('e', e);
                // console.log(this);

            });
            selectOrg.renderTo(document.getElementById('org'));

            const selectService = new BX.Ui.Select({
                options,
                value: '',
                isSearchable: true,
                multi: true,
                name: 'FIELDS[UF_COMPANY]',
                placeholder: 'Выберите организацию',
                containerClassname: '',
            });
            selectService.subscribe('update', (e) => {
                // console.log('e', e);
                // console.log(this);

            });
            selectService.renderTo(document.getElementById('service'));
            */

            const contractModal = document.getElementById('addContract')
            // console.log(exampleModal);
            var modalContract = new bootstrap.Modal(contractModal)
            //console.log('myModal', myModal);
            if (contractModal) {
                contractModal.addEventListener('show.bs.modal', e => {

                    const input = document.querySelector(`input[name="FIELDS[UF_DATE]"]`);
                    // console.log('input', input);
                    // console.log('closest', input.closest(".ui-ctl-date"));

                    const button = input.closest(".ui-ctl-date")
                    // const button = input.previousElementSibling;
                    // console.log("button", button);
                    // const button = input.nextElementSibling;
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


                    // (function() {
                    //         'use strict'
                    var form = document.querySelector('.contract_add')

                    var message = form.querySelector('#message')
                    // Array.prototype.slice.call(forms)
                    //     .forEach(function(form) {
                    // console.log(form);

                    form.addEventListener('submit', function(event) {

                        event.preventDefault()

                        if (!form.checkValidity()) {
                            event.stopPropagation()

                        } else {

                            //const form = document.getElementById("requestForm");
                            let form_data = new FormData(form);

                            BX.ajax.runComponentAction("zhek:master.contracts", 'addContract', {
                                mode: "class",
                                data: form_data,
                            }).then(function(response) {
                                console.log('response', response);
                                message.innerHTML = response.data
                                message.classList.add('ui-alert-success', 'd-block')
                                message.classList.remove('ui-alert-danger', 'd-none')

                                setTimeout(() => {
                                    form.reset()
                                    message.innerHTML = ''
                                    message.classList.add('d-none')
                                    form.classList.remove('was-validated')
                                    modalContract.hide()
                                }, 3000)

                                //$('#response').html('<span class="text-success">Ваше сообщение принято на рассмотрение</span>');

                            }).catch((response) => {
                                let errors = response.errors
                                message.innerHTML = errors[0].message
                                message.classList.add('ui-alert-danger', 'd-block')
                                message.classList.remove('ui-alert-success', 'd-none')
                                console.log('error', errors);
                            });
                        }
                        form.classList.add('was-validated')
                    }, false)



                    // })
                    // })()






                })
            }
        })();

        BX.ready(function() {
            // пример обработчика
            // изменение метро и района в филтре в realestate sections.php
            BX.addCustomEvent('UI::Select::change', function(obj) {
                var selectName = BX.data(obj.node, 'name');
                console.log('getDataValue', obj.getDataValue());

                var dataValue = obj.getDataValue()
                if (Array.isArray(dataValue)) {
                    var value = []

                    document.getElementById(selectName).value = dataValue;

                    dataValue.forEach(function(item) {
                        value.push(item.VALUE)
                    });
                    // console.log('value', value);
                    document.getElementById(selectName).value = value;

                } else {
                    document.getElementById(selectName).value = dataValue.VALUE;
                }


            });
        });

        /* BX.ready(function() {
             const button = document.getElementById('companySelectButtonNode');

             let dialog = new BX.UI.EntitySelector.Dialog({
                 targetNode: button,
                 context: 'MY_PAGE_CONTEXT',
                 enableSearch: true,
                 searchOptions: {
                     allowCreateItem: false
                 },
                 multiple: false,
                 entities: [{
                     id: 'company',
                     dynamicLoad: true,
                     dynamicSearch: true
                 }, ],
             });

             button.addEventListener('click', () => {
                 dialog.show();
             });
        });*/
    </script>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>