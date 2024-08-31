<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Обратная связь</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <!-- <div class="container"> -->
          <?/*<form class="contact-form" id="contact-form" method="post" action="contact_form/contact_form.php">
            <div class="row flex-box">
              <fieldset class="col col-12">
                <label>Имя</label>
                <input class="text-input" name="name" type="text" value="">
              </fieldset>
              <fieldset class="col col-12">
                <label>Email</label>
                <input class="text-input" name="email" type="text" value="">
              </fieldset>
              <fieldset class="col col-12">
                <label>Телефон</label>
                <input class="text-input" name="phone" type="text" value="">
              </fieldset>
              <fieldset class="col col-12">
                <label>Сообщение</label>
                <textarea name="message"></textarea>
              </fieldset>
            </div>
            <div class="row margin-top-30">
              <div class="col col-12">
                <input type="hidden" name="action" value="contact_form" />
                <div class="margin-top-15 padding-bottom-16 align-center">
                  <a class="more submit-contact-form" href="#" title="Send message">Отправить</a>
                </div>
              </div>
            </div>
          </form>
          */?>
          <!-- </div> -->


          <?$APPLICATION->IncludeComponent(
                                "zhek:form",
                                "feedback",
                                [
                                    "IBLOCK_ID" => 10,
                                    "EVENT_NAME"  => 'FEEDBACK'
                                ],
                                false
                            );?>



      </div>
      <!-- <div class="modal-footer"></div> -->
    </div>
  </div>
</div>