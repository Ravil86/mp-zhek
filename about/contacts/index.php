<?
define("NOT_CONTAINER", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Конакты");
?>
<div class="container-fluid row! gray! full-width!">
    <div class="row align-items-center">
        <div class="col-12 col-md-6 order-2 order-md-1">
            <iframe src="https://yandex.ru/map-widget/v1/?um=mymaps%3Ak2AaaDuDiiz5N4zv4iWqWFYQqAuEYCef&amp;source=constructor" width="100%" height="550" frameborder="0"></iframe>
        </div>
        <div class="col-12 col-md-6 order-1 order-md-2 my-4 my-md-0 padding-bottom-70! page-margin-top-section!">
            <div class="padding-left-right-100">
                <ul class="row features-list page-margin-top! padding-top-30! clearfix">
                    <li class="col-12 col-sm-7">
                        <div class="icon features-map"></div>
                        <h4>МП «ЖЭК-3»</h4>
                        <div class="mt-2">
                            <p>628011, Тюменская область, ХМАО-Югра,
                                <?$APPLICATION->IncludeFile(SITE_DIR."includes/inc_adress.php", Array(), Array("MODE"=> "text","NAME"=> '"Адрес"'));?>
                            </p>
                        </div>
                    </li>
                    <li class="col-12 col-sm-5">
                        <div class="icon features-phone"></div>
                        <h4>Телефон</h4>
                        <div class="mt-2">
                            <p>Приёмная:</p>
                            <?$APPLICATION->IncludeFile(SITE_DIR."includes/inc_phone_1.php", Array(), Array("MODE"=> "html","NAME"=> '"Телефон"'));?>
                            <?/*<br>Mobile: <a href="tel:2507257152">250 725 7152</a>*/?>
                        </div>
                    </li>
                </ul>
                <ul class="row features-list page-margin-top clearfix">
                    <li class="col-12 col-sm-7">
                        <div class="icon features-clock"></div>
                        <h4>Время работы</h4>
                        <div class="mt-2">
                            <?$APPLICATION->IncludeFile(SITE_DIR."includes/inc_work_time_full.php", Array(), Array("MODE"=> "html","NAME"=> '"Время работы"'));?>
                        </div>
                    </li>
                    <li class="col-12 col-sm-5">
                        <div class="icon features-email"></div>
                        <h4>EMAIL</h4>
                        <div class="mt-2">
                            <?$APPLICATION->IncludeFile(SITE_DIR."includes/inc_email.php", Array(), Array("MODE"=> "html","NAME"=> '"Эл. адрес"'));?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>