<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {die();}?>
<form id="requestForm" class="container feedback-form px-2 needs-validation" novalidate>
    <div id="response"></div>
    <input type="hidden" name="PARAMS[IBLOCK_ID]" value="<?=$arParams['IBLOCK_ID']?>" />
    <input type="hidden" name="PARAMS[EVENT_NAME]" value="<?=$arParams['EVENT_NAME']?>" />
    <input type="hidden" name="DATA[DOG]" value="<?=$arParams['DOG']?>" />
    <!-- <input type="hidden" name="action" value="contact_form" /> -->
    <div class="row">
        <div class="col-12 row flex-box gx-4 gy-2 mt-2">

            <fieldset class="col col-12">
                <label>Ваше имя*</label>
                <input class="text-input form-control" name="DATA[NAME]" type="text" value="" placeholder="" required>
                <div class="invalid-feedback">
                    Введите имя
                </div>
            </fieldset>

            <fieldset class="col col-12">
                <label>E-mail*</label>
                <input name="DATA[EMAIL]" class="text-input form-control" type="email" placeholder="" required>
                <div class="invalid-feedback">
                    Введите Email
                </div>
            </fieldset>

            <fieldset class="col col-12">
                <label>Телефон*</label>
               <input name="DATA[PHONE]" class="text-input form-control phone" type="text" placeholder="" required>
               <div class="invalid-feedback">
                    Введите Телефон
                </div>
            </fieldset>

        </div>
        <?/*<div class="col-12">
           <div class="row mt-3">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheck1" required>
                        <label class="form-check-label u-text-small" for="flexCheck1">
                            Подтверждаю корректность введенных данных и даю
                            <a data-fancybox
                               href="/upload/condition_pdn_n.pdf">
                                согласие на
                                обработку моих персональных данных</a>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pe-0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheck2" required>
                        <label class="form-check-label u-text-small" for="flexCheck2">
                            Я ознакомлен <a data-fancybox href="/upload/condition_pdn_n.pdf" class="u-requests__link">с
                                положением об обработке персональных данных</a>
                        </label>
                    </div>
                </div>
            </div>
        </div>*/?>
    </div>

    <div class="row gx-4 gy-2 mt-2 justify-content-center ">
            <div class="margin-top-15 padding-bottom-16 align-center">
                <button class="more submit-contact-form w-75" type="submit">Отправить</button>
                  <!-- <a class="more submit-contact-form" href="#" title="Send message">Отправить</a> -->
            </div>
        <!--  -->
    </div>

</form>

<script>
    const iblockID = <?=$arParams['IBLOCK_ID']?>;

    $(document).ready(function () {
        $('.phone').mask('+7(999)999-99-99');
        // $("#validationPhone").mask("+7(999)999-99-99");
    });

    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    event.preventDefault()

                    if (!form.checkValidity()) {
                        event.stopPropagation()
                    } else {
                        console.log('response');
                        // var selectedCheckBoxes = document.querySelectorAll('input.checkbox:checked');

                        // var checkedValues = Array.from(selectedCheckBoxes).map(cb => cb.value);

                        const form = document.getElementById("requestForm");
                        let form_data = new FormData(form);
                        // form_data.append('IBLOCK_ID', iblockID);
                        // for (var i = 0; i < checkedValues.length; i++) {
                        //     form_data.append('arr[]', checkedValues[i]);
                        // }

                        BX.ajax.runComponentAction("zhek:form", 'request', {
                            mode: "class",
                            data: form_data,
                        }).then(function (response) {
                            console.log('response',response);
                            $('#response').html('<span class="text-success">Ваше сообщение принято на рассмотрение</span>');
                            // form.reset()
                        })
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

</script>
