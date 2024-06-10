<?
define("NOT_CONTAINER", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Конакты");
?>
<div class="container-fluid row! gray! full-width!">
		<div class="row">
			<div class="col col-6">
				<iframe src="https://yandex.ru/map-widget/v1/?um=mymaps%3Ak2AaaDuDiiz5N4zv4iWqWFYQqAuEYCef&amp;source=constructor" width="100%" height="500" frameborder="0"></iframe>
			</div>
			<div class="col col-6 padding-bottom-70 page-margin-top-section">
				<div class="padding-left-right-100">
					<ul class="row features-list page-margin-top! padding-top-30! clearfix">
						<li class="col col-7">
							<div class="icon features-map"></div>
							<h4>МП «ЖЭК-3»</h4>
							<p>628011, Тюменская область, ХМАО-Югра,<br>г. Ханты-Мансийск, ул.Боровая, 9</p>
						</li>
						<li class="col col-5">
							<div class="icon features-phone"></div>
							<h4>Телефон</h4>
							<p>Приёмная: <a href="tel:+73467958008">+7 (3467) 958-008</a><?/*<br>Mobile: <a href="tel:2507257152">250 725 7152</a>*/?></p>
						</li>
					</ul>
					<ul class="row features-list page-margin-top clearfix">
						<li class="col col-7">
							<div class="icon features-clock"></div>
							<h4>Время работы</h4>
							<p>Пн—пт: 8.00 - 17.00<br>Обед: 12:00 - 13:00</p>
						</li>
						<li class="col col-5">
							<div class="icon features-email"></div>
							<h4>EMAIL</h4>
							<p><a href="mailto:mp-zhehk-3@yandex.ru">mp-zhehk-3@yandex.ru</a></p>
						</li>
					</ul>
				</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>