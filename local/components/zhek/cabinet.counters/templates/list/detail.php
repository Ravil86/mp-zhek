<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

\Bitrix\Main\UI\Extension::load("ui.forms");

if ($arResult['ACCESS']):
?>
    <?
    if ($arResult['WRONG']): ?>
        <div class="alert alert-danger mt-2 d-inline" role="alert">
            Ошибка доступа ввода показаний приборов учёта
        </div>
    <? else: ?>
        <hr>
        <div class="d-flex">
            <div class="col">
                <h3 class="h4"><?= ($arResult['SEND_ADMIN'] ? '#' . $arResult['DETAIL']['COMPANY_INFO']['ID'] . ' ' : '') ?><?= $arResult['DETAIL']['COMPANY_INFO']['UF_NAME'] ?></h3>
                <?= $arResult['DETAIL']['COMPANY_INFO']['UF_ADDRESS']; ?>
            </div>
            <div class="col-auto d-grid">
                <a class="ui-btn ui-btn-sm ui-btn-no-caps" href="<?= $arResult['FOLDER'] ?>">вернуться назад</a>
                <!-- <button class="ui-btn ui-btn-sm ui-btn-primary mt-2 ms-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseContracts" aria-expanded="false" aria-controls="collapseContracts">контракт
                </button> -->
            </div>
        </div>
        <br><br>
        <div class="container counter_form">
            <? // gg($arResult['COMPANY_OBJECTS']); 
            ?>
            <? foreach ($arResult['DETAIL']['COMPANY_OBJECTS'] as $key => $object) { ?>
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

                                            $prevMeter = null;
                                            $lastMeter = null;

                                            if (is_array($object['PREV_METERS'][$item['ID']]))
                                                $prevMeter = reset($object['PREV_METERS'][$item['ID']]);
                                            // $prevMeter = array_shift($object['PREV_METERS'][$item['ID']]);

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
                                                                        ?> py-3 d-flex align-items-center">
                                                            <?= $arResult['ADMIN'] ? '<span class="pe-1"><span class="badge position-relative text-bg-secondary">#' .
                                                                ($item['RELATED'] ? '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . $item['UF_COUNTER'] . '</span>' : $item['ID']) .
                                                                '</span></span>' : ''; ?>
                                                            <?= ($item['UF_NUMBER'] ? '<span class="text-nowrap">' . $item['UF_NUMBER'] . '</span> - <i class="small">' . $item['UF_NAME'] . '</i>' : $item['UF_NAME']) ?>
                                                            <? if ($item['MAIN_RELATED']): ?>
                                                                <span role="button" class="ps-1 text-danger"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - <?= $item['MAIN_RELATED']['UF_PERCENT'] ?>%">
                                                                    <i class="bi bi-link-45deg fs-5"></i></span>
                                                            <? endif; ?>
                                                        </div>
                                                        <div class="col-<?= !$arResult['SEND_ADMIN'] ? '2' : '1' ?> py-3 d-flex align-items-center justify-content-center type">
                                                            <?= !$item['RELATED'] ? $item['SERVICE'] : $item['COUNTER']['TYPE']['XL'] ?>
                                                        </div>
                                                        <div class="col py-3 d-flex align-items-center justify-content-center current_use"><?= !$item['RELATED'] ? $prevMeterFormat : ($item['PREV_METER'] ?: 0) ?></div>
                                                        <div class="col py-3 d-flex align-items-center justify-content-center"><?= !$item['RELATED'] ? $raznostFormat : ($item['DIFF_METER'] ?: 0) ?></div>
                                                        <div class="col py-3 d-flex align-items-center justify-content-center">
                                                            <?= !$item['RELATED'] ? ($lastMeter ? $lastMeterFormat : $prevMeterFormat) : ($item['LAST_METER'] ?: 0) ?></div>
                                                    </div>

                                                </div>
                                            </div>

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
                                            // gg($object['PREV_METERS'][$item['ID']]);
                                            // gg($item);
                                            $noteMeter = '';

                                            $prevMeter = null;
                                            $lastMeter = null;

                                            if (is_array($object['PREV_METERS'][$item['ID']]))
                                                $prevMeter = reset($object['PREV_METERS'][$item['ID']]);
                                            // $prevMeter = array_shift($object['PREV_METERS'][$item['ID']]);

                                            $lastMeter = $object['LAST_METERS'][$item['ID']];

                                            $prevMeterFormat = number_format($prevMeter, 3, '.', '');

                                            // gg($prevMeterFormat);

                                            $noteMeter = $object['NOTE_METERS'][$item['ID']];

                                            if ($lastMeter)
                                                $userSend = true;
                                            ?>
                                            <div class="row card bg-info-subtle border-start-0 rounded-0 rounded-end py-2 counter-item mb-1" id="counter<?= $item['ID'] ?>">
                                                <div class="card-body ps-2 pe-1 py-0">
                                                    <div class="row gx-2 align-items-center align-items-stretch!<?= $item['RELATED'] ? ' related' : '' ?>">
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
                                                                    min="<?= !$arResult['SEND_ADMIN'] ? ($lastMeter ?:  $prevMeter) : '' ?>" <?= $disable ? 'disabled' : '' ?> value="<?= $disable ? ($item['MAIN_RELATED'] ? $item['MAIN_RELATED']['METER'] : $lastMeter) : '' /*$prevMeter*/ ?>" data-current="<?= $prevMeterFormat ?>">
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
        <script>
            function sendData(id) {

                const form = document.getElementById("objectMeter" + id);
                let form_data = new FormData(form);
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

            }

            $('.meter').keypress(function(event) {

                if (event.which == 46) {
                    var value = $(this).val()
                    // console.log('this.val', value)
                    var dotted = value.indexOf('.');
                }

            }).on('paste', function(event) {
                event.preventDefault();
            });

            $('.meter').each(function() {

                var current = null

                // выводим разницу с предыдущими
                $(this).keyup($.debounce(function() {

                    let $this = $(this);
                    let diff_span = $this.closest('.counter-item').find('.changeDiff')
                    var last = $this.val()

                    current = $this.data('current');
                    var min = $this.attr('min');

                    var diff_val = subtractFloats(last, current);
                    // console.log('diff_span', diff_val);
                    diff_span.html(diff_val)
                }, 1000));

                // при отрицательной разнице очищаем введенные показатели
                $(this).keyup($.debounce(function() {
                    let $this = $(this);

                    let diff_span = $this.closest('.counter-item').find('.changeDiff')
                    var last = $this.val()

                    current = $this.data('current');
                    var min = $this.attr('min');

                    var diff_val = subtractFloats(last, current);

                    if (diff_val < 0) {

                        if (min.length > 0)
                            $this.val('');
                        // $this.val(min);

                        diff_val = subtractFloats(min, current);
                        diff_span.html(diff_val)
                    }
                }, 7000));
            })

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
<? else: ?>
    <font class="errortext">Ошибка доступа</font>
<? endif; ?>