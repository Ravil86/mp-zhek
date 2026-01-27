<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Highloadblock as HL;

?>
<? if ($arResult['ACCESS']): ?>
    <form id="cabinetOption" action="">
        <div class="row">
            <div class="col-12 col-lg-auto">
                <h3 class="h5">Дата начала подачи</h3>
                <div class="date-datepicker">
                    <div class="datepicker" id="dateStart"></div>
                    <input type="hidden" id="start" name="DATA[start]" value="<?= $arResult['DATA_START']; ?>" data-step="0" data-min="<?= $arResult['EDIT_END']; ?>">
                </div>
            </div>
            <div class="col-12 col-lg-auto">
                <h3 class="h5">Дата окончания подачи</h3>
                <div class="date-datepicker">
                    <div class="datepicker" id="dateEnd"></div>
                    <input type="hidden" id="end" name="DATA[end]" value="<?= $arResult['DATA_END']; ?>" data-min="<?= $arResult['DATA_START']; ?>" data-step="0">
                </div>
            </div>

            <div class="col-12 col-lg-auto">
                <h3 class="h5">Окончание редактирования</h3>
                <div class="date-datepicker">
                    <div class="datepicker" id="editEnd"></div>
                    <input type="hidden" id="edir" data-step="0" value="<?= $arResult['EDIT_END']; ?>" data-min="<?= $arResult['EDIT_START']; ?>" data-max="<?= $arResult['EDIT_START']; ?>">
                </div>
            </div>

        </div>
        <div class="d-flex">
            <button class="ui-btn ui-btn-lg ui-btn-primary px-4 mt-2 ms-5" type="submit">Сохранить</button>
        </div>
    </form>
    <script>
        $(function() {
            $(".date-datepicker").each(function() {
                var datePicker = $(this).find('.datepicker'),
                    pickerValue = $(this).find(":hidden")
                // console.log('this', pickerValue.attr("data-min"));

                console.log('last', new Date(new Date().getFullYear(), new Date().getMonth() + 1, 1));

                datePicker.datepicker({
                    dateFormat: "dd.mm.yy",
                    stepMonths: pickerValue.attr("data-step"), //только текущий месяц
                    nextText: "",
                    prevText: "",
                    minDate: pickerValue.attr("data-min"),
                    maxDate: pickerValue.attr("data-max") ? pickerValue.attr("data-max") : new Date(new Date().getFullYear(), new Date().getMonth() + 1, 3),
                    // minDate: new Date(2025, 1 - 1, 1),
                    onSelect: function(date) {
                        pickerValue.val(date)
                    },
                });
                datePicker.datepicker("setDate", pickerValue.val());
            });


            // $("#datepicker").datepicker({
            // 	stepMonths: 0, //только текущий месяц
            // 	nextText: "",
            // 	prevText: "",
            // 	hideIfNoPrevNext: true,
            // 	// changeYear: true,
            // 	// changeMonth: true,
            // 	// showMonthAfterYear: true,
            // 	onSelect: function(date) {
            // 		$('#datepicker_value').val(date)
            // 	},
            // });
            // $("#datepicker").datepicker("setDate", $('#datepicker_value').val());
        });


        //const iblockID = <?= $arParams['IBLOCK_ID'] ?>;

        (function() {
            'use strict'

            var forms = document.querySelectorAll('#cabinetOption')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault()
                        if (!form.checkValidity()) {
                            event.stopPropagation()
                        } else {

                            // var selectedCheckBoxes = document.querySelectorAll('input.checkbox:checked');

                            // var checkedValues = Array.from(selectedCheckBoxes).map(cb => cb.value);

                            // const form = document.getElementById("requestForm");
                            let form_data = new FormData(form);
                            // form_data.append('IBLOCK_ID', iblockID);
                            // for (var i = 0; i < checkedValues.length; i++) {
                            //     form_data.append('arr[]', checkedValues[i]);
                            // }

                            BX.ajax.runComponentAction("zhek:master.options", 'request', {
                                mode: "class",
                                data: form_data,
                            }).then(function(response) {
                                console.log('response', response);
                                /*if (response.data === 'captcha_error') {
                                     document.getElementById("liveToast").classList.remove('text-bg-success');
                                     document.getElementById("liveToast").classList.add('text-bg-danger');
                                     $("#notificationResponse .toast-body").html('Докажите что вы не робот и повторите отправку формы.');
                                     const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLive);
                                     toastBootstrap.show();
                                 } else if (response.data === 'success') {
                                     document.getElementById("liveToast").classList.remove('text-bg-danger');
                                     document.getElementById("liveToast").classList.add('text-bg-success');
                                     $("#notificationResponse .toast-body").html('Ваш вопрос принят на рассмотрение. Спасибо!');
                                     const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLive);
                                     toastBootstrap.show();
                                     form.reset();
                                     form.classList.remove('was-validated');
                                     window.smartCaptcha.reset();
                                 }*/
                            })
                        }
                    }, false)
                })
        })()
    </script>
    <?/* ?>
<div class="container">
	<div class="col-sm-6" style="height:130px;">
		<div class="form-group">
			<div class='input-group date' id='datetimepicker9'>
				<input type='text' class="form-control" />
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar">
					</span>
				</span>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			$('#datetimepicker9').datetimepicker({
				// format: 'L',
				viewMode: 'years'
			});
		});
	</script>
</div>
<?*/ ?>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>