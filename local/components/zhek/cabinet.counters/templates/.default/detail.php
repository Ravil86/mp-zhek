<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

\Bitrix\Main\UI\Extension::load("ui.forms");

if ($arResult['ACCESS']):
?>
    <hr>
    <div class="d-flex">
        <div class="col">
            <h3 class="h4"><?= ($arResult['SEND_ADMIN'] ? '#' . $arResult['DETAIL']['OBJECT']['ID'] . ' ' : '') ?><?= $arResult['DETAIL']['OBJECT']['NAME'] ?></h3>
            <?= $arResult['DETAIL']['OBJECT']['ADDRESS']; ?>
        </div>
        <div class="col-auto">
            <a class="ui-btn ui-btn-sm ui-btn-no-caps" href="<?= $arResult['FOLDER'] ?>">вернуться назад</a>
        </div>
    </div>
    <? // dump($arResult['DETAIL'])
    if ($arResult['WRONG']): ?>
        <div class="alert alert-danger mt-2 d-inline" role="alert">
            Ошибка доступа ввода показаний приборов учёта
        </div>
    <? else: ?>
        <?
        ?>
        <div class="row align-items-end text-center mt-2 px-1 gx-2 gy-3">
            <div class="py-2 small col-<?= !$arResult['SEND_ADMIN'] ? '3' : '2' ?>">ПУ - примечание</div>
            <div class="py-2 small col"><?= !$arResult['SEND_ADMIN'] ? 'Участвует в расчете услуг' : '' ?></div>
            <div class="py-2 small col"><?= !$arResult['SEND_ADMIN'] ? 'Показания на ' : '' ?>начало месяца</div>
            <div class="py-2 small col"><?= !$arResult['SEND_ADMIN'] ? 'Разность за текущий месяц' : 'разность' ?></div>
            <div class="py-2 small col"><?= !$arResult['SEND_ADMIN'] ? 'Конечные показания' : 'конечные' ?></div>
            <div class="py-2 col-12 col-lg-<?= !$arResult['SEND_ADMIN'] ? '4' : '5' ?> text-center bg-info-subtle rounded-top">
                <div class="fs-5 mt-1">Ввод показаний</div>
                <div class="row d-flex! mx-1 gx-2 mt-3">
                    <? if (!$arResult['SEND_ADMIN']): ?><div class="col-3 small">Нулевой расход</div><? else: ?><div class="col-auto"></div><? endif; ?>
                    <div class="<?= !$arResult['SEND_ADMIN'] ? 'col' : 'col-4' ?> small">Текущие показания</div>
                    <div class="<?= !$arResult['SEND_ADMIN'] ? 'col-4' : 'col-3' ?> small">Разность</div>
                    <? if ($arResult['SEND_ADMIN']): ?> <div class="col-4 small">Комментарий</div><? endif; ?>
                </div>
            </div>
        </div>
        <div class="container row!">
            <form id="objectMeter">
                <input type="hidden" name="OBJECT" value="<?= $arResult['OBJECT_ID'] ?>">
                <input type="hidden" name="MONTH" value="<?= $arResult['SAVE_MONTH']['ID'] ?>">
                <?
                $userSend = false;

                // $related = $this->arResult['RELATED'][$objectID];
                // $prevRelated = LKClass::meters(false, 0, '', '', 47);
                // gg($prevRelated);
                foreach ($arResult['DETAIL']['LIST'] as $key => $item): ?>
                    <?

                    $noteMeter = '';
                    $raznost = 0;

                    if (is_array($arResult['DETAIL']['PREV_METERS'][$item['ID']]))
                        $prevMeter = array_shift($arResult['DETAIL']['PREV_METERS'][$item['ID']]);

                    //$prevMeter = $arResult['DETAIL']['PREV_METERS'][$item['ID']];
                    $lastMeter = $arResult['DETAIL']['LAST_METERS'][$item['ID']];

                    // gg($prevMeter);


                    if ($lastMeter && $prevMeter)
                        $raznost = $lastMeter - $prevMeter;

                    $prevMeterFormat = number_format($prevMeter, 3, '.', '');
                    $lastMeterFormat = number_format($lastMeter, 3, '.', '');
                    $raznostFormat = number_format($raznost, 3, '.', '');

                    $noteMeter = $arResult['DETAIL']['NOTE_METERS'][$item['ID']];

                    if ($lastMeter)
                        $userSend = true;

                    // gg($item['MAIN_RELATED']);
                    ?>
                    <div class="row card counter-item" id="counter<?= $item['ID'] ?>">
                        <div class="card-body ps-2 pe-1 py-0">
                            <div class="row gx-2 align-items-stretch align-items-center!">
                                <div class="col-3<? //= !$arResult['SEND_ADMIN'] ? '3' : '2';
                                                    ?> py-3 d-flex align-items-center">
                                    <?= $arResult['SEND_ADMIN'] ? '# ' . ($item['RELATED'] ? $item['UF_COUNTER'] . '-' : '') . $item['ID'] : '' ?> <?= $item['UF_NUMBER']; ?>&nbsp;-&nbsp;<i class="small"><?= $item['UF_NAME'] ?></i>
                                    <? if ($item['MAIN_RELATED']): ?>
                                        <span role="button" class="ps-1 text-danger"
                                            data-bs-toggle="tooltip"
                                            data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - <?= $item['MAIN_RELATED']['UF_PERCENT'] ?>%">
                                            <i class="bi bi-link-45deg fs-5"></i></span>
                                    <? endif; ?>
                                </div>
                                <div class="<?= !$arResult['SEND_ADMIN'] ? 'col' : 'col-auto'; ?> py-3 d-flex align-items-center justify-content-center"><?= $item['SERVICE'] ?></div>
                                <div class="col py-3 d-flex align-items-center justify-content-center current_use">
                                    <?= !$item['RELATED'] ? $prevMeterFormat : $item['PREV_METER'] ?></div>
                                <div class="col py-3 d-flex align-items-center justify-content-center">
                                    <?= !$item['RELATED'] ? $raznostFormat : $item['DIFF_METER'] ?></div>
                                <div class="col py-3 d-flex align-items-center justify-content-center">
                                    <?= !$item['RELATED'] ? ($lastMeter ? $lastMeterFormat : $prevMeterFormat) : $item['LAST_METER'] ?></div>
                                <div class="col-12 col-lg-<?= !$arResult['SEND_ADMIN'] ? '4' : '5' ?> bg-info-subtle">
                                    <div class="d-flex align-items-center h-100 py-2!">
                                        <? if (!$arResult['SEND_ADMIN'] && !$item['RELATED']): ?>
                                            <div class="col-3 d-flex justify-content-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input null-meter" type="checkbox" role="button" data-switch-id="<?= $item['ID'] ?>"
                                                        <?= $arResult['SEND_FORM'] && $userSend || !$arResult['SEND_FORM'] && !$arResult['SEND_ADMIN']  ? 'disabled' : '' ?>
                                                        title="Оставить показания без изменений">
                                                </div>
                                            </div>
                                        <? else: ?>
                                            <div class="col-3 text-center"><? if ($item['RELATED']): ?><?= $item['UF_PERCENT'] ?>%<? endif; ?></div>
                                        <? endif; ?>
                                        <? if (!$item['RELATED']): ?>
                                            <?
                                            $disabled = $arResult['SEND_FORM'] && $userSend || !$arResult['SEND_FORM'] && !$arResult['SEND_ADMIN'] ? true : false;

                                            // gg($item['MAIN_RELATED']['METER']);
                                            ?>
                                            <div class="col-auto">
                                                <input id="inputMeter<?= $item['ID'] ?>" type="text" name="METER[<?= $item['ID'] ?>]" class="meter form-control" onkeyup="validate(this)" onclick="moveCaretToStart(this)"
                                                    min="<?= $lastMeter ?:  $prevMeter ?>" <?= $disabled ? 'disabled' : '' ?> value="<?= $disabled ? ($item['MAIN_RELATED'] ? $item['MAIN_RELATED']['METER'] : $lastMeter) : '' /*$prevMeter*/ ?>" data-current="<?= $prevMeterFormat ?>">
                                            </div>
                                            <div class="col-<?= !$arResult['SEND_ADMIN'] ? '4' : '3' ?> d-flex justify-content-center align-items-end"><span class="fw-bold changeDiff"><?= $disabled ? ($item['MAIN_RELATED'] ? $item['MAIN_RELATED']['METER'] : $lastMeter) : 0 ?></span><small class="ps-1"><?= $item['UNIT'] ?></small></div>
                                        <? else: ?>
                                            <div class="col-5 text-center">
                                                <?= $item['METER'] ?>
                                            </div>
                                            <div class="col text-center">
                                                <span class="fw-bold"><?= $item['METER'] ?></span><small class="ps-1"><?= $item['UNIT'] ?></small>
                                            </div>
                                        <? endif; ?>
                                        <? if ($arResult['SEND_ADMIN'] && !$item['RELATED']): ?>
                                            <div class="col-auto">
                                                <div class="ui-ctl ui-ctl-textarea ui-ctl-xs ui-ctl-resize-x">
                                                    <textarea class="ui-ctl-element" name="NOTE[<?= $item['ID'] ?>]" <?= $noteMeter ? 'readonly' : '' ?>><?= $noteMeter ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col"></div>
                                        <? endif; ?>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-<?= !$arResult['SEND_ADMIN'] ? '3' : '2' ?>"></div>
                        <div class="col"></div>
                        <div class="col"></div>
                        <div class="col"></div>
                        <div class="col"></div>
                        <div class="col-<?= !$arResult['SEND_ADMIN'] ? '4' : '5' ?> px-0 py-1 bg-info-subtle"></div>
                    </div>
                <? endforeach; ?>
                <div class="row">
                    <div class="col-<?= !$arResult['SEND_ADMIN'] ? '6' : '5' ?> mx-auto text-center">
                        <div id="mess" class="alert d-none" role="alert"></div>
                    </div>
                    <div class="col-<?= !$arResult['SEND_ADMIN'] ? '4' : '5' ?> text-center bg-info-subtle rounded-bottom pb-2">
                        <? if ($arResult['SEND_ADMIN']): ?>
                            <button type="button" class="ui-btn ui-btn-lg ui-btn-primary-dark" onclick="sendData()" <? //= !$userSend && $arResult['MODERATOR'] ? 'disabled' : ''
                                                                                                                    ?>>корректировка показаниий</button>
                        <? else: ?>
                            <button type="button" class="ui-btn ui-btn-lg ui-btn-primary-dark" onclick="sendData()" <?= $userSend || !$arResult['SEND_FORM'] ? 'disabled' : '' ?>>Внести показания</button>
                        <? endif ?>
                    </div>
                </div>
            </form>

        </div>

        <script>
            function sendData() {

                const form = document.getElementById("objectMeter");
                // console.log('form', form);
                let form_data = new FormData(form);
                // console.log(form_data);

                var message = $('#mess');


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
<? else: ?>
    <font class="errortext">Ошибка доступа</font>
<? endif; ?>