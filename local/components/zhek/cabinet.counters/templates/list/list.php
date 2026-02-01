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

        $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $arResult['GRID_ID'],
            'GRID_ID' => $arResult['GRID_ID'],
            'FILTER' => $arResult['GRID']['FILTER'],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true,
        ]);

        $gridParams = [
            'GRID_ID' => $arResult['GRID_ID'],
            'COLUMNS' => $arResult['GRID'][$arParams['TYPE']]['COLUMNS'],
            'ROWS' => $arResult['GRID'][$arParams['TYPE']]['ROWS'],
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
        ];
        $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);
        ?>
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