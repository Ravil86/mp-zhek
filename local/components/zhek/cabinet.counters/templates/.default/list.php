<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

\Bitrix\Main\UI\Extension::load("ui.forms");

if ($arResult['ACCESS']):
?>
    <!-- <hr> -->
    <?
    if ($arResult['WRONG']): ?>
        <div class="alert alert-danger mt-2 d-inline" role="alert">
            Ошибка доступа ввода показаний приборов учёта
        </div>
    <? else: ?>
        <? if ($arResult['ADMIN'] || $arResult['MODERATOR']): ?>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Все</button>
                    <button class="nav-link" id="nav-list-tab" data-bs-toggle="tab" data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-list" aria-selected="false">Список</button>
                </div>
            </nav>
            <div class="tab-content mt-2" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                <? endif; ?>
                <div class="container counter_form">
                    <? foreach ($arResult['COUNTER_OBJECTS'] as $key => $object) { ?>
                        <?
                        if ($object['LIST']):
                        ?>
                            <div class="counter_item row">
                                <div class="d-flex align-items-end text-center mt-2 px-0 px-1! gx-2 gy-1">
                                    <div class="col">
                                        <div class="row! d-flex align-items-center">
                                            <h3 class="col h6 mb-0 text-start"><?= /*($arResult['SEND_ADMIN'] ?*/ '#' . $object['ID'] . ' '/* : '')*/ ?><?= $object['NAME'] ?></h3>
                                            <div class="col-5 ms-3! pe-3 text-end small"><?= $object['ADDRESS']; ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="fs-6 text-start"><?= $arResult['COMPANY'][$object['ORG']]['UF_NAME'] ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="py-2 small col-3 text-start<? //= !$arResult['SEND_ADMIN'] ? '3' : '2'
                                                                                    ?>">ПУ - примечание</div>
                                            <div class="py-2 small col-2"><?= !$arResult['SEND_ADMIN'] ? 'Участвует в расчете услуг' : '' ?></div>
                                            <div class="py-2 small col"><?= !$arResult['SEND_ADMIN'] ? 'Показания<br> на ' : '' ?>начало месяца</div>
                                            <div class="py-2 small col"><?= !$arResult['SEND_ADMIN'] ? 'Разность за текущий месяц' : 'разность' ?></div>
                                            <div class="py-2 small col"><?= !$arResult['SEND_ADMIN'] ? 'Конечные показания' : 'конечные' ?></div>
                                        </div>
                                    </div>

                                    <div class="py-2 col-12 col-lg-5<? //= !$arResult['SEND_ADMIN'] ? '4' : '5'
                                                                    ?> text-center bg-info-subtle rounded-top">
                                        <div class="fs-5 mt-1">Ввод показаний</div>
                                        <div class="row mx-1! gx-2 mt-3">
                                            <? if (!$arResult['SEND_ADMIN']): ?><div class="col-3 small">Нулевой расход</div><? else: ?><div class="col-auto"></div><? endif; ?>
                                            <div class="<?= !$arResult['SEND_ADMIN'] ? 'col' : 'col-4' ?> small">Текущие показания</div>
                                            <div class="<?= !$arResult['SEND_ADMIN'] ? 'col-4' : 'col-3' ?> small">Разность</div>
                                            <? if ($arResult['SEND_ADMIN']): ?> <div class="col-4 small">Комментарий</div><? endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <form id="objectMeter<?= $object['ID'] ?>">
                                        <input type="hidden" name="OBJECT" value="<?= $object['ID'] ?>">
                                        <input type="hidden" name="MONTH" value="<?= $arResult['SAVE_MONTH']['ID'] ?>">
                                        <div class="row">
                                            <div class="col-7">
                                                <?
                                                $userSend = false;

                                                foreach ($object['LIST'] as $key => $item): ?>
                                                    <?

                                                    $noteMeter = '';
                                                    $raznost = 0;

                                                    if (is_array($object['PREV_METERS'][$item['ID']]))
                                                        $prevMeter = array_shift($object['PREV_METERS'][$item['ID']]);
                                                    //$prevMeter = $arResult['DETAIL']['PREV_METERS'][$item['ID']];

                                                    $lastMeter = $object['LAST_METERS'][$item['ID']];

                                                    if ($lastMeter && $prevMeter)
                                                        $raznost = $lastMeter - $prevMeter;

                                                    $prevMeterFormat = number_format($prevMeter, 3, '.', '');
                                                    $lastMeterFormat = number_format($lastMeter, 3, '.', '');
                                                    $raznostFormat = number_format($raznost, 3, '.', '');

                                                    $noteMeter = $object['NOTE_METERS'][$item['ID']];

                                                    if ($lastMeter)
                                                        $userSend = true;

                                                    // gg($item['UF_MAIN']);
                                                    ?>
                                                    <div class="row card border-end-0 rounded-0 rounded-start counter-item mb-1" id="counter<?= $item['ID'] ?>">
                                                        <div class="card-body ps-2 pe-1 py-0">
                                                            <div class="row gx-2 align-items-stretch">
                                                                <div class="col-<?= !$arResult['SEND_ADMIN'] ? '3 ps-1' : '5 small'
                                                                                ?> py-3 d-flex align-items-center"><?= $arResult['ADMIN'] ? '<small class="pe-1">#' . $item['ID'] . '</small>' : ''; ?>
                                                                    <?= ($item['UF_NUMBER'] ? '<span class="text-nowrap">' . $item['UF_NUMBER'] . '</span> - <i class="small">' . $item['UF_NAME'] . '</i>' : $item['UF_NAME']) ?>
                                                                    <? if ($item['MAIN_RELATED']): ?>
                                                                        <span role="button" class="ps-1 text-danger"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - <?= $item['MAIN_RELATED']['UF_PERCENT'] ?>%">
                                                                            <i class="bi bi-link-45deg fs-5"></i></span>
                                                                    <? endif; ?>
                                                                </div>
                                                                <div class="col-<?= !$arResult['SEND_ADMIN'] ? '2' : '1' ?> py-3 d-flex align-items-center justify-content-center"><?= $item['SERVICE'] ?></div>
                                                                <div class="col py-3 d-flex align-items-center justify-content-center current_use"><?= !$item['RELATED'] ? $prevMeterFormat : ($item['PREV_METER'] ?: 0) ?></div>
                                                                <div class="col py-3 d-flex align-items-center justify-content-center"><?= !$item['RELATED'] ? $raznostFormat : ($item['DIFF_METER'] ?: 0) ?></div>
                                                                <div class="col py-3 d-flex align-items-center justify-content-center">
                                                                    <?= !$item['RELATED'] ? ($lastMeter ? $lastMeterFormat : $prevMeterFormat) : ($item['LAST_METER'] ?: 0) ?></div>
                                                                <?/*
                                        <div class="col-12 col-lg-5<? //= !$arResult['SEND_ADMIN'] ? '4' : '5'
                                                                    ?> bg-info-subtle">
                                            <div class="d-flex align-items-center h-100 py-2!">
                                                <? if ($item['RELATED']): ?>
                                                    <div class="col-3 text-center"><?= $item['UF_PERCENT'] ?>%</div>
                                                    <div class="col-5 text-center">
                                                        <?= $item['METER'] ?>
                                                    </div>
                                                    <div class="col text-center">
                                                        <span class="fw-bold"><?= $item['METER'] ?: 0 ?></span><small class="ps-1"><?= $item['UNIT'] ?></small>
                                                    </div>
                                                <? else: ?>
                                                    <? if (!$arResult['SEND_ADMIN']): ?>
                                                        <div class="col-3 d-flex justify-content-center">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input null-meter" type="checkbox" role="button" data-switch-id="<?= $item['ID'] ?>"
                                                                    <?= $arResult['SEND_FORM'] && $userSend || !$arResult['SEND_FORM'] && !$arResult['SEND_ADMIN']  ? 'disabled' : '' ?>
                                                                    title="Оставить показания без изменений">
                                                            </div>
                                                        </div>
                                                    <? else: ?>
                                                        <!-- <div class="col">&nbsp;</div> -->
                                                    <? endif; ?>
                                                    <div class="col-auto! col">
                                                        <?
                                                        $disable = $arResult['SEND_FORM'] && $userSend || !$arResult['SEND_FORM'] && !$arResult['SEND_ADMIN'] ? true : false;
                                                        ?>
                                                        <input id="inputMeter<?= $item['ID'] ?>" type="text" name="METER[<?= $item['ID'] ?>]" class="meter form-control w-100! ms-2" onkeyup="validate(this)" onclick="moveCaretToStart(this)"
                                                            min="<?= $lastMeter ?:  $prevMeter ?>" <?= $disable ? 'disabled' : '' ?> value="<?= $disable ? ($item['MAIN_RELATED'] ? $item['MAIN_RELATED']['METER'] : $lastMeter) : '' ?>" data-current="<?= $prevMeterFormat ?>">
                                                    </div>
                                                    <div class="col-<?= !$arResult['SEND_ADMIN'] ? '4' : '3' ?> d-flex justify-content-center align-items-end">
                                                        <span class="fw-bold changeDiff"><?= $disable ? ($item['MAIN_RELATED'] ? $item['MAIN_RELATED']['METER'] : $lastMeter) : 0 ?></span><small class="ps-1"><?= $item['UNIT'] ?></small>
                                                    </div>
                                                    <? if ($arResult['SEND_ADMIN']): ?>
                                                        <div class="col-auto! col-4">
                                                            <div class="ui-ctl ui-ctl-textarea ui-ctl-xs ui-ctl-resize-x">
                                                                <textarea class="ui-ctl-element" name="NOTE[<?= $item['ID'] ?>]" <?= $noteMeter ? 'readonly' : '' ?>><?= $noteMeter ?></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col"></div> -->
                                                    <? endif; ?>

                                                <? endif; ?>
                                            </div>
                                        </div>*/ ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <?/*
                            <div class="row">
                                <div class="col-<?= !$arResult['SEND_ADMIN'] ? '3' : '2' ?>"></div>
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col-5<? //= !$arResult['SEND_ADMIN'] ? '4' : '5'
                                                    ?> px-0 py-1 bg-info-subtle"></div>
                            </div>*/ ?>
                                                <? endforeach; ?>
                                                <div class="row">
                                                    <div class="col-10<? //= !$arResult['SEND_ADMIN'] ? '6' : '5'
                                                                        ?> mx-auto text-center">
                                                        <div id="mess" class="message alert d-none" role="alert"></div>
                                                    </div>
                                                </div>
                                            </div><!--col-8-->
                                            <div class="col-5 bg-info-subtle">
                                                <?
                                                $userSend = false;

                                                foreach ($object['LIST'] as $key => $item): ?>
                                                    <?

                                                    $noteMeter = '';
                                                    // $raznost = 0;

                                                    if (is_array($object['PREV_METERS'][$item['ID']]))
                                                        $prevMeter = array_shift($object['PREV_METERS'][$item['ID']]);

                                                    $lastMeter = $object['LAST_METERS'][$item['ID']];

                                                    // if ($lastMeter && $prevMeter)
                                                    //     $raznost = $lastMeter - $prevMeter;
                                                    // $prevMeterFormat = number_format($prevMeter, 3, '.', '');
                                                    // $lastMeterFormat = number_format($lastMeter, 3, '.', '');
                                                    // $raznostFormat = number_format($raznost, 3, '.', '');

                                                    $noteMeter = $object['NOTE_METERS'][$item['ID']];

                                                    if ($lastMeter)
                                                        $userSend = true;
                                                    ?>
                                                    <div class="row card bg-info-subtle border-start-0 rounded-0 rounded-end py-2 counter-item mb-1" id="counter<?= $item['ID'] ?>">
                                                        <div class="card-body ps-2 pe-1 py-0">
                                                            <div class="row gx-2 align-items-center align-items-stretch!<?= $item['RELATED'] ? ' related' : '' ?>">
                                                                <?/*
                                            <div class="col-2<? //= !$arResult['SEND_ADMIN'] ? '2' : '3'
                                                                ?> py-3 d-flex align-items-center"><?= $arResult['ADMIN'] ? '<small>#' . $item['ID'] . '</small>&nbsp;' : ''; ?>
                                                <?= ($item['UF_NUMBER'] ? $item['UF_NUMBER'] . '&nbsp;-&nbsp;<i class="small">' . $item['UF_NAME'] . '</i>' : $item['UF_NAME']) ?>
                                                <? if ($item['MAIN_RELATED']): ?>
                                                    <span role="button" class="ps-1 text-danger"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - <?= $item['MAIN_RELATED']['UF_PERCENT'] ?>%">
                                                        <i class="bi bi-link-45deg fs-5"></i></span>
                                                <? endif; ?>
                                            </div>
                                            <div class="col-1 py-3 d-flex align-items-center justify-content-center"><?= $item['SERVICE'] ?></div>
                                            <div class="col py-3 d-flex align-items-center justify-content-center current_use"><?= !$item['RELATED'] ? $prevMeterFormat : ($item['PREV_METER'] ?: 0) ?></div>
                                            <div class="col py-3 d-flex align-items-center justify-content-center"><?= !$item['RELATED'] ? $raznostFormat : ($item['DIFF_METER'] ?: 0) ?></div>
                                            <div class="col py-3 d-flex align-items-center justify-content-center">
                                                <?= !$item['RELATED'] ? ($lastMeter ? $lastMeterFormat : $prevMeterFormat) : ($item['LAST_METER'] ?: 0) ?></div>
                                                */ ?>

                                                                <? if ($item['RELATED']): ?>
                                                                    <div class="col-3 text-center pt-1"><?= $item['UF_PERCENT'] ?>%</div>
                                                                    <div class="col-5 text-center pt-1">
                                                                        <?= $item['METER'] ?>
                                                                    </div>
                                                                    <div class="col text-center pt-1">
                                                                        <span class="fw-bold"><?= $item['METER'] ?: 0 ?></span><small class="ps-1"><?= $item['UNIT'] ?></small>
                                                                    </div>
                                                                <? else: ?>
                                                                    <? if (!$arResult['SEND_ADMIN']): ?>
                                                                        <div class="col-3 d-flex justify-content-center">
                                                                            <div class="form-check form-switch">
                                                                                <input class="form-check-input null-meter" type="checkbox" role="button" data-switch-id="<?= $item['ID'] ?>"
                                                                                    <?= $arResult['SEND_FORM'] && $userSend || !$arResult['SEND_FORM'] && !$arResult['SEND_ADMIN']  ? 'disabled' : '' ?>
                                                                                    title="Оставить показания без изменений">
                                                                            </div>
                                                                        </div>
                                                                    <? else: ?>
                                                                        <!-- <div class="col">&nbsp;</div> -->
                                                                    <? endif; ?>
                                                                    <div class="col-auto! col">
                                                                        <?
                                                                        $disable = $arResult['SEND_FORM'] && $userSend || !$arResult['SEND_FORM'] && !$arResult['SEND_ADMIN'] ? true : false;
                                                                        ?>
                                                                        <input id="inputMeter<?= $item['ID'] ?>" type="text" name="METER[<?= $item['ID'] ?>]" class="meter form-control w-100! ms-2" onkeyup="validate(this)" onclick="moveCaretToStart(this)"
                                                                            min="<?= $lastMeter ?:  $prevMeter ?>" <?= $disable ? 'disabled' : '' ?> value="<?= $disable ? ($item['MAIN_RELATED'] ? $item['MAIN_RELATED']['METER'] : $lastMeter) : '' /*$prevMeter*/ ?>" data-current="<?= $prevMeterFormat ?>">
                                                                    </div>
                                                                    <div class="col-<?= !$arResult['SEND_ADMIN'] ? '4' : '3' ?> d-flex justify-content-center align-items-end">
                                                                        <span class="fw-bold changeDiff"><?= $disable ? ($item['MAIN_RELATED'] ? $item['MAIN_RELATED']['METER'] : $lastMeter) : 0 ?></span><small class="ps-1"><?= $item['UNIT'] ?></small>
                                                                    </div>
                                                                    <? if ($arResult['SEND_ADMIN']): ?>
                                                                        <div class="col-auto! col-4">
                                                                            <div class="ui-ctl! ui-ctl-textarea! ui-ctl-md ui-ctl-resize-y ui-ctl-resize-x!">
                                                                                <textarea class="ui-ctl-element w-100" name="NOTE[<?= $item['ID'] ?>]" <?= $noteMeter ? 'readonly' : '' ?>><?= $noteMeter ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <!-- <div class="col"></div> -->
                                                                    <? endif; ?>

                                                                <? endif; ?>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <?/*
                            <div class="row">
                                <div class="col-<?= !$arResult['SEND_ADMIN'] ? '3' : '2' ?>"></div>
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col-5<? //= !$arResult['SEND_ADMIN'] ? '4' : '5'
                                                    ?> px-0 py-1 bg-info-subtle"></div>
                            </div>
                            */ ?>
                                                <? endforeach; ?>
                                                <div class="row">
                                                    <div class="col-<?= !$arResult['SEND_ADMIN'] ? '6' : '8'
                                                                    ?> text-center bg-info-subtle rounded-bottom pb-2 d-flex justify-content-center mx-auto">

                                                        <? if ($arResult['SEND_ADMIN']): ?>
                                                            <button type="button" class="ui-btn ui-btn-lg ui-btn-primary-dark w-100" onclick="sendData(<?= $object['ID'] ?>)" <? //= !$userSend && $arResult['MODERATOR'] ? 'disabled' : ''
                                                                                                                                                                                ?>>корректировка показаниий</button>
                                                        <? else: ?>
                                                            <button type="button" class="ui-btn ui-btn-lg ui-btn-primary-dark w-100 me-3" onclick="sendData(<?= $object['ID'] ?>)" <?= $userSend || !$arResult['SEND_FORM'] ? 'disabled' : '' ?>>Внести показания</button>
                                                        <? endif ?>
                                                    </div>
                                                    <!-- <div class="col-auto"></div> -->
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <hr>
                            </div>
                        <? endif; ?>

                    <? } ?>
                </div>
                <? if ($arResult['ADMIN'] || $arResult['MODERATOR']): ?>
                </div>
                <div class="tab-pane fade" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab" tabindex="0">
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

                    // $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
                    //     'FILTER_ID' => $arResult['GRID_ID'],
                    //     'GRID_ID' => $arResult['GRID_ID'],
                    //     'FILTER' => $arResult['GRID']['FILTER'],
                    //     'ENABLE_LIVE_SEARCH' => true,
                    //     'ENABLE_LABEL' => true,
                    // ]);

                    $gridParams = [
                        'GRID_ID' => $arResult['GRID_ID'],
                        'COLUMNS' => $arResult['GRID']['COLUMNS'],
                        'ROWS' => $arResult['GRID']['ROWS'],
                        'FOOTER' => [
                            'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
                        ],
                        'SHOW_ROW_CHECKBOXES' => false,
                        'NAV_OBJECT' => $nav,
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
                        'AJAX_OPTION_JUMP' => 'Y',
                        'SHOW_CHECK_ALL_CHECKBOXES' => false,
                        'SHOW_ROW_ACTIONS_MENU' => false,
                        'SHOW_GRID_SETTINGS_MENU' => true,
                        'SHOW_NAVIGATION_PANEL' => true,
                        'SHOW_PAGINATION' => true,
                        'SHOW_SELECTED_COUNTER' => true,
                        'SHOW_TOTAL_COUNTER' => true,
                        'SHOW_PAGESIZE' => true,
                        'SHOW_ACTION_PANEL' => false,
                        'ALLOW_COLUMNS_SORT' => true,
                        'ALLOW_COLUMNS_RESIZE' => true,
                        'ALLOW_HORIZONTAL_SCROLL' => true,
                        'ALLOW_SORT' => true,
                        'ALLOW_PIN_HEADER' => true,
                        'AJAX_OPTION_HISTORY' => 'N',
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

                </div>
            </div>
            </div>
        <? endif; ?>
        <script>
            function sendData(id) {

                const form = document.getElementById("objectMeter" + id);
                // console.log('form', form);
                let form_data = new FormData(form);
                // console.log(form_data);

                var message = $("#objectMeter" + id + ' .message');

                // sendMeter
                BX.ajax.runComponentAction("zhek:cabinet.counters", 'sendMeter', {
                        mode: "class",
                        data: form_data,
                    }).then(function(response) {
                        // console.log('message', message);

                        if (response.status === 'success') {

                            message.removeClass('d-none')
                                .removeClass('alert-danger')
                                .addClass('alert-success')
                                .html('Изменения успешно сохранены');
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
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

            function validate(element) {

                var value = element.value;
                // replace everything that's not a number or comma or decimal
                value = value.replace(/[^0-9,.]/g, "");
                // replace commas with decimal
                value = value.replace(/,/, ".");
                // set element value to new value
                element.value = value;


                // var dotted = value.indexOf('.');
                // console.log('dotted', dotted)
                // console.log('substring', value.substring(dotted, dotted + 1))

                // console.log('substring 1', value.substring(dotted, value.lenght))

            }

            $('.meter').keypress(function(event) {
                // console.log(event.which);


                if (event.which == 46) {

                    var value = $(this).val()
                    // console.log('this.val', value)

                    var dotted = value.indexOf('.');

                    // console.log('value', value)
                    // console.log('dotted', dotted)
                    // console.log('substring', value.substring(dotted, dotted + 1))

                    // var value = $(this).val()

                    // console.log('replace', value.replace($(this).val().substring(dotted, dotted + 1), '_'))

                    //value.slice(dotted, dotted + 1)



                    // console.log('dote', value.indexOf('.'))

                }

                // console.log('which', event.which)

                //  console.log('dote', $(this).val().indexOf('.'))

                // if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
                //         $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                //     event.preventDefault();
                // }
            }).on('paste', function(event) {
                event.preventDefault();
            });

            $('.meter').each(function() {

                $(this).keyup($.debounce(function() {

                    let $this = $(this);
                    // console.log('$this', $(this));

                    let diff_span = $this.closest('.counter-item').find('.changeDiff')

                    var last = $this.val()

                    let current = $this.data('current');

                    var min = $this.attr('min');

                    var diff_val = subtractFloats(last, current);
                    // console.log('diff_span', diff_val);

                    // var diff_val = Math.round(+last - +current )
                    // var diff_val = (+last - +current).toFixed(3)
                    // var diff_val = clear(last - current)

                    if (diff_val < 0) {

                        $this.val(min);
                        diff_val = subtractFloats(min, current);
                        diff_span.html(diff_val)
                    }
                    diff_span.html(diff_val)

                }, 1000));
            })

            /*$(".meter").focusout(function(e) {
                var $this = $(this);
                var val = $this.val();
                // var max = $this.attr("max");
                var min = $this.attr("min");

                if (max > 0 && val > max) {
                    e.preventDefault();
                    $this.val(max);
                } else if (min > 0 && val < min) {
                    e.preventDefault();
                    $this.val(min);
                }
            });*/

            function subtractFloats(float1, float2) {
                // Преобразуем числа в строки
                const str1 = float1.toString();
                const str2 = float2.toString();

                // Находим количество знаков после запятой
                const decimalPlaces1 = str1.includes('.') ? str1.split('.')[1].length : 0;
                const decimalPlaces2 = str2.includes('.') ? str2.split('.')[1].length : 0;

                // Определяем максимальное количество знаков после запятой
                const maxDecimalPlaces = Math.max(decimalPlaces1, decimalPlaces2);

                // Умножаем на 10 в степени maxDecimalPlaces, чтобы получить целые числа
                const factor = Math.pow(10, maxDecimalPlaces);
                const int1 = Math.round(float1 * factor);
                const int2 = Math.round(float2 * factor);

                // Выполняем вычитание
                const resultInt = int1 - int2;

                // Возвращаем результат, делим на factor
                return resultInt / factor;
            }


            $('.null-meter').change(function() {
                //console.log($(this).data('switch-id'));

                let switchID = $(this).data('switch-id'),
                    inputID = $('#inputMeter' + switchID)

                inputID.val(inputID.data('current'))

                $(this).closest('.counter-item').find('.changeDiff').html(0)

                if ($(this).is(':checked')) {
                    inputID.prop('readonly', true).addClass('bg-secondary-subtle')
                    // inputID.prop('disabled', true)
                } else {
                    inputID.prop('readonly', false).removeClass('bg-secondary-subtle')
                    // inputID.prop('disabled', false)
                }
            });

            function moveCaretToStart(inputObject) {
                // inputObject.setSelectionRange(0, 0)
            }
            // $(".meter").mask("9.9?99");
        </script>
    <? endif; ?>
    <?

    // $request = Application::getInstance()->getContext()->getRequest();
    // $uriString = $request->getRequestUri();
    // $uri = new Uri($uriString);
    // $isArhive = $request->getQuery('arhive');
    // $uri->addParams(array("arhive"=>"Y"));
    // $arhiveUrl = $uri->getUri();

    // dump($arResult['DETAIL']);
    /*
    ?>
    <div id='moderation' class="content">
        <div class="row align-items-center pb-3 mb-2">
            <div class="col-5 mb-1 h4"><?= $arResult['DETAIL']['USERNAME'] ?>
                <? //=TruncateText($arResult['DETAIL']['USERNAME'], 50)
                ?>
            </div>
            <div class="col-7 mb-4! card d-flex! flex-row justify-content-between align-items-center px-3 py-2">
                <div class="d-flex flex-column align-items-center">
                    <div class="col-12 h6 my-0 text-uppercase text-blue"><?= TruncateText($arResult['DETAIL']['COURSE'], 28) ?></div>
                    <div class="col-12 h6 my-0 text-secondary"><?= $arResult['DETAIL']['STREAM']['NAME'] . ' - ' . $arResult['DETAIL']['STREAM']['TEXT'] ?></div>
                    <div class="col-12 small fst-italic text-muted">
                        <small>Дата изменения: <?= $arResult['DETAIL']['DATE_UPDATE'] ?></small>

                    </div>
                </div>
                <div class="text-end! text-center col-3! badge! small px-4 py-2 rounded-pill text-bg-light! text-bg-<?= $arResult['DETAIL']['STATUS']['VALUE'] ?> lh-sm">
                    <i class="small"><?= $arResult['DETAIL']['STATUS']['TEXT'] ?></i>
                </div>
            </div>
        </div>
    </div>
    */ ?>
<? else: ?>
    <font class="errortext">Ошибка доступа</font>
<? endif; ?>
<?
/*function templateItems($docVal, $useCheck, $admin = false){
	$Format=ToLower(substr($docVal['FILE_NAME'], strrpos($docVal['FILE_NAME'], '.') + 1));
	// dump($docVal[DESC]);
	$result = '<div class="col-12 col-sm-6 col-md-4 col-lg mt-4 mt-md-0">
		<div class="row"><div class="col doc_title small!">'.($docVal['DESC']?:TruncateText($docVal['NAME'], 28)).'</div></div>';
		if(!$docVal['STATUS']){
			$result .= '<div class="row">
				<span class="col">не загружен</span>
			</div>';
		}
		else{
			$docStatus = $docVal['STATUS'];
			$result .= '<div class="row g-2" >
				<div class="col-auto">';
					$result .= '<a class="mb-2 pt-2 btn btn-sm icon_file border-'.$docStatus['UF_CODE'].' text-'.$docStatus['UF_CODE'].'" data-fancybox '.($Format=='pdf'? 'data-type="iframe" data-options=\'{"iframe\" : {\"preload\" : true, \"css\" : {\"height\" : \"80%\"}}}\'':'').' data-src="'.$docVal['SRC'].'" href="javascript:;">
							<i class="bi bi bi-file-earmark-'.$docStatus['UF_XML_ID'].' image text-'.$docStatus['UF_CODE'].'"></i><div class="small"><small><small>'.TruncateText($docStatus['UF_NAME'], 8).'</small></small></div>
						</a>
					</div>
					<div class="col-8" id="'.$docVal['ID'].'">';

							$result .= '<div class="mt-1 mb-2 text-muted">';
							$result .= '<div class=""><span>'.TruncateText($docVal['FILE_NAME'], 35).'</span></div>';
							//$result .= '<div class="text-'.$docStatus['UF_CODE'].'"><i class="bi bi-file-earmark-'.$docStatus['UF_XML_ID'].'" style="font-size: 1.3rem;"></i><span>'.$docStatus['UF_NAME'].'</span></div>';
							$result .= '<div class="info_status_inner info_item_date mt-1 pl-0"><span class="">'.$docVal['DATE'].'</span></div>';
										if($docStatus['ID']==3 && $docVal['INFO']):
											$result .= '<div class="mt-1 ms-1 info_status_note"><div class="ps-3">'.$docVal['INFO'].'</div></div>';
										endif;
									$result .= '</div>

					</div>
			</div>';
				if($admin && $docVal['ID'] || $useCheck && $docStatus['ID']==1):?>
    <?
					$textCheck = !$admin?'Одобрить':'load';
					$textRefuse = !$admin?'Отказать':'deny';
					$result .= '<div class="btn_status">
								<a class="btn_yes btn button-outline mb-1'.($admin?' w-auto':'').'" data-id="'.$docVal['ID'].'">
								<i class="bi bi-check2'.(!$admin?' fs-6':'').'"></i>
									'.$textCheck.'</a>
								<a class="btn_no open_popup btn button-outline mb-1'.($admin?' w-auto':'').'" data-id="'.$docVal['ID'].'">
									<i class="bi bi-x'.(!$admin?' fs-6':'').'"></i>
									'.$textRefuse.'</a>';
						if($admin){
							$result .= '<a class="btn_load btn button-outline ms-1 mb-1'.($admin?' w-auto':'').'" data-id="'.$docVal['ID'].'">
							<i class="bi bi-arrow-down"></i></a>';
						}
								//<!--button class="btn_no open_popup" data-id="'.$docVal['VALUE'].'">Отказать</!--button-->
						$result .= '</div><!--btn-status-->';
				endif;
		}
	$result .= '</div>';
	return $result;
}*/
?>