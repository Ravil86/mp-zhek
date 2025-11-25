<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

\Bitrix\Main\UI\Extension::load("ui.forms");
\Bitrix\Main\UI\Extension::load("ui.hint");

if ($arResult['ACCESS']): ?>

    <div class="d-flex">
        <div class="col">
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
                <!-- <a class="ui-btn ui-btn-no-caps ui-btn-sm" href="<?= $arResult['FOLDER'] ?>">вернуться назад</a> -->
                <button class="ui-btn ui-btn-success mt-2 ms-0" data-bs-toggle="modal" data-bs-target="#counterModal" onclick="setMain()">Добавить главный ПУ</button>
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

    // $controlPanel['GROUPS'][0]['ITEMS'][] = [
    //     'TYPE' => Bitrix\Main\Grid\Panel\Types::CUSTOM,
    //     'ID' => 'example-custom',
    //     'NAME' => 'EXAMPLE_CUSTOM',
    //     'CLASS' => 'custom-css-class',
    //     'VALUE' => '<button class="ui-btn ui-btn-sm ui-btn-secondary" data-bs-toggle="modal" data-bs-target="#counterModal">добавить счётчик</button>',
    // ];

    foreach ($arResult['ITEMS'] as $key => $related): ?>
        <? $typeItem = [];
        $typeRelate = '';
        ?>
        <?
        $counterItem = $arResult['COUNTERS'][$key];

        foreach ($counterItem['UF_TYPE'] as $type) {
            $typeItem[] = $arResult['SERVICES'][$type];
        }
        $typeRelate = implode('&nbsp;&nbsp;', $typeItem);
        ?>
        <div class="card my-2">
            <div class="card-body">
                <div class="row gx-1">
                    <div class="col-lg object-name d-flex align-items-center">
                        <h5 class="h5 card-title mb-0">#<?= $key; ?> / <?= $counterItem['UF_NUMBER'] ?></h5>
                        <i class="small ms-2"><?= $counterItem['UF_NAME'] ?></i>
                        <div class="card-subtitle ms-3 pt-1"><?= $typeRelate ?></div>
                    </div>
                    <div class="col-auto d-grid d-xl-flex">
                        <button class="ui-btn ui-btn-sm ui-btn-secondary" data-bs-toggle="modal" onclick="setObject(<?= $related['ID']; ?>)" data-bs-target="#counterModal">Добавить счётчик</button>
                    </div>
                </div>
                <div class="grid_form">
                    <?
                    $grid_options = new CGridOptions($arResult["GRID_DETAIL"]);

                    $gridParams = [
                        'GRID_ID' => $arResult['GRID_ID'] . '_' . $key,
                        'COLUMNS' => $arResult['COLUMNS'],
                        'ROWS' => $related['ROWS'],
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
                    ];
                    // gg($gridParams);
                    ?>
                </div>
                <? $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams); ?>
            </div>
        </div>
        <? $i++; ?>
    <? endforeach; ?>
    <div class="modal counter-modal fade" id="counterModal<? //= $related['ID'];
                                                            ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-id="<?= $related['ID']; ?>">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form class="counter_add needs-validation" method="post" novalidate>
                    <div class="modal-header">
                        <div class="modal-title">Добавить связаннный ПУ
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="ADD_RELATED" value="Y">
                        <!-- <input type="hidden" name="FIELDS[UF_ORG]" value="<?= $arResult['DETAIL']['ORG']['ID']; ?>"> -->
                        <!-- <input type="hidden" name="FIELDS[UF_OBJECT]" value="<?= $related['ID']; ?>"> -->
                        <?= bitrix_sessid_post() ?>
                        <div class="row gx-3 gy-2">
                            <div class="col-12 col-md-5">
                                <label for="">ПУ</label>
                                <div class="ui-ctl-dropdown! ui-ctl! ui-ctl-after-icon! ui-ctl-w100">
                                    <!-- <div class="ui-ctl-after ui-ctl-icon-angle"></div> -->
                                    <? // gg($arResult['COUNTERS']);
                                    ?>
                                    <select id="counter" class="select2! selectpicker!" data-width="100%" data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border" name="FIELDS[UF_COUNTER]" required>
                                        <? foreach ($arResult['COUNTERS'] as $key => $counter): ?>
                                            <option value="<?= $key ?>" <?= $key == $related['ID'] ? 'selected' : '' ?>>#<?= $counter['ID'] ?> / <?= $counter['UF_NUMBER'] ?> - <?= $counter['UF_NAME'] ?></option>
                                        <? endforeach ?>
                                    </select>
                                    <div class="invalid-feedback">Выберите Прибор учёта</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-7">
                                <label for="">Организация</label>
                                <select id="orgList" class="select selectpicker!" data-width="100%" data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border" name="FIELDS[UF_ORG]" required>
                                </select>
                                <div class="invalid-feedback">выберите Организацию</div>
                            </div>
                            <div class="col-12 col-md-7">
                                <label>Объект</label>
                                <select id="objectList" class="select select2! selectpicker!" data-width="100%" data-style="ui-btn ui-btn-no-caps ui-btn-dropdown ui-btn-light-border" name="FIELDS[UF_OBJECT]" required>
                                </select>
                                <div class="invalid-feedback">выберите Объект</div>
                            </div>
                            <div class="col-12 col-md-5">
                                <label>Процент занимаемого объема/площади, %</label>
                                <div class="ui-ctl ui-ctl-after-icon ui-ctl-date! ui-ctl-w100">
                                    <!-- <div class="ui-ctl-after ui-ctl-icon-calendar"></div> -->
                                    <input type="number" class="ui-ctl-element form-control" name="FIELDS[UF_PERCENT]" value="" min="0.1" max="100" step="0.01" required>
                                    <div class="invalid-feedback">укажите значение</div>
                                </div>
                            </div>
                            <div class="col-12 col-md col-md-2! d-flex">
                                <label class="ui-ctl ui-ctl-checkbox">
                                    <input id="objectMain" type="checkbox" class="ui-ctl-element" name="FIELDS[UF_MAIN]" value="Y">
                                    <div class="ui-ctl-label-text">Главный объект</div>
                                </label>
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


    <?/*
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
    */ ?>
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

        <?
        $jsonOrg = \Bitrix\Main\Web\Json::encode($arResult['COMPANY_JSON']);
        $jsonObjects = \Bitrix\Main\Web\Json::encode($arResult['OBJECTS_JSON']);
        ?>
        const parent = <?= $jsonOrg ?>;
        const child = <?= $jsonObjects ?>;
        // console.log('child', child);

        listArray.forEach(element => {
            // console.log(element.id);
            const objectsModal = document.getElementById(element.id)
            // console.log(exampleModal);
            if (objectsModal) {

                objectsModal.addEventListener('show.bs.modal', event => {

                     $('#counter')
                        .select2({
                            dropdownParent: $("#counterModal"),
                        })

                    // const selected = parent[1]
                    var selected = child[0]
                    // console.log('selected', selected)

                    $('#objectList')
                        .select2({
                            data: child,
                            dropdownParent: $("#counterModal"),
                            minimumResultsForSearch: Infinity
                            // val: null,
                        }).val(null).trigger("change")

                    $('#orgList')
                        .select2({
                            data: parent,
                            dropdownParent: $("#counterModal"),
                            // minimumResultsForSearch: Infinity
                            // val: null
                        })
                        .val(null)
                        .trigger("change")
                        .on("change", (e) => {

                            // console.log('selected', e.target.value)
                            selected = child[e.target.value]
                            // console.log('child', selected)

                            if (selected.children && selected.children.length > 0) {

                                // $('#objectList').empty().trigger("change")

                                var newOption = new Option('', '', false, false);
                                $('#objectList').empty().val(null).append(newOption).trigger('change');

                                $.each(selected.children, function(index, value) {
                                    var newOption = new Option(value.text, value.id, false, false);
                                    $('#objectList').append(newOption).trigger('change');
                                });
                            }

                            // var newOption = new Option(data.text, data.id, false, false);
                            // $('#objectList').append(child[e.target.value]).trigger('change');
                            // $('#objectList').select2({
                            //     data: selected
                            // }).trigger('change')

                            // console.log('child', child[e.target.value].children.length);

                            // $('#objectList').select2({
                            //     data: child[e.target.value]
                            // }).trigger("change")
                        });

                    // $("#orgList").on("select2:select", function(e) {
                    //     $('#objectList').select2({
                    //         data: child[selected.id]
                    //     }).trigger("change")
                    // });

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

                        //event.preventDefault()

                        if (!form.checkValidity()) {
                            event.preventDefault()
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

    function setObject(id) {
        const Modal = $('#counterModal');
        Modal.find('#counter').val(id).trigger('change')
        Modal.find('#objectMain').prop("checked",false);
        // $('.selectpicker').selectpicker('refresh')
    }

     function setMain() {
        const Modal = $('#counterModal');
        Modal.find('#counter').val(null).trigger("change")
        Modal.find('#objectMain').prop("checked","checked");
    }

    function saveLosses(id) {

        const form = document.getElementById("losses" + id);
        // console.log('form', form);
        let form_data = new FormData(form);


        form_data.append('object', id)
        console.log('form_data', form_data);

        var message = $('#losses' + id + ' #mess');

        // sendLosses
        BX.ajax.runComponentAction("zhek:master.objects", 'sendLosses', {
                mode: "class",
                data: form_data,
            }).then(function(response) {

                console.log('message', message);

                if (response.status === 'success') {

                    message.removeClass('d-none')
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .html('Изменения успешно сохранены');
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                } else {
                    message
                        .removeClass('d-none')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .html('<span class="text-danger">Произошла ошибка на сервере! Пожалуйста, попробуйте позже.</span>');
                }
            })
            .catch((response) => {
                // console.log('response_error', response);

                message.removeClass('d-none')
                    .removeClass('alert-success')
                    .addClass('alert-danger');

                // msgOk.html('').hide();
                $.each(response.errors, function() {
                    message.html(this.message + '<br>');
                });
            });

    }

    // function saveNorma(id) {

    //     const form = document.getElementById("norma" + id);
    //     // console.log('form', form);
    //     let form_data = new FormData(form);


    //     form_data.append('object', id)
    //     console.log('form_data', form_data);

    //     var message = $('#norma' + id + ' #mess');

    //     // sendLosses
    //     BX.ajax.runComponentAction("zhek:master.objects", 'sendNorma', {
    //             mode: "class",
    //             data: form_data,
    //         }).then(function(response) {

    //             console.log('response', response);

    //             console.log('message', message);

    //             if (response.status === 'success') {

    //                 message.removeClass('d-none')
    //                     .removeClass('alert-danger')
    //                     .addClass('alert-success')
    //                     .html('Изменения успешно сохранены');
    //                 setTimeout(function() {
    //                     location.reload();
    //                 }, 5000);
    //             } else {
    //                 message
    //                     .removeClass('d-none')
    //                     .removeClass('alert-success')
    //                     .addClass('alert-danger')
    //                     .html('<span class="text-danger">Произошла ошибка на сервере! Пожалуйста, попробуйте позже.</span>');
    //             }
    //         })
    //         .catch((response) => {
    //             // console.log('response_error', response);

    //             message.removeClass('d-none')
    //                 .removeClass('alert-success')
    //                 .addClass('alert-danger');

    //             // msgOk.html('').hide();
    //             $.each(response.errors, function() {
    //                 message.html(this.message + '<br>');
    //             });
    //         });

    // }

    function validate(element) {

        var value = element.value;
        // replace everything that's not a number or comma or decimal
        value = value.replace(/[^0-9,.]/g, "");
        // replace commas with decimal
        value = value.replace(/,/, ".");
        // set element value to new value
        element.value = value;
    }
</script>