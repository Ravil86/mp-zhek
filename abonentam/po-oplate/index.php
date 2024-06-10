<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Инструкция по оплате");
?><h3>Уважаемые жители п. Горноправдинск, п. Бобровский!</h3>
<p>
	 Обращаем Ваше внимание на изменение системы оплаты коммунальных услуг, поставщиком которых является Муниципальное предприятие «ЖЭК-3». С 01.07.2016г. произвести оплату можно будет следующими доступными способами:
</p>
<p>
	 — По терминалу (пластиковой карточке) в абонентском отделе МП «ЖЭК-3» (на первом этаже в здании МП «Комплекс-Плюс»);
</p>
<p>
	 — В отделении почты России;
</p>
<p>
	 — В кассе Сбербанка России;
</p>
<p>
	 — В банкоматах Ханты-Мансийского банка, Сбербанка России;
</p>
<p>
 <b>По всем вопросам Вы можете обратиться в абонентский отдел МП «ЖЭК-3» по адресу: п. Горноправдинск, ул. Геологов, д. 5, здание МП «Комплекс-Плюс», 1 этаж, или по телефону: 8 (3467) 374-708.</b>
</p>
<p>
</p>
<h3>Инструкция по оплате в банкоматах ХМБ (Открытие)</h3>
<ol>
	<li>Вставьте пластиковую карточку в банкомат;</li>
	<li>Ведите ПИН-КОД;</li>
	<li>Выбираете пункт меню «Платежи»;</li>
	<li>Выбираете пункт меню «Единая система платежей»;</li>
	<li>Далее необходимо ввести «код получателя платежа», для МП «ЖЭК-3» он установлен&nbsp;<b>010213</b>.&nbsp;Уточнить коды получателя платежа можно нажав на кнопку «Перечень участников». Для продолжения нажимаем «Продолжить»;</li>
	<li>Далее вводим номер лицевого счета. Номер лицевого счета можно уточнить в абонентском отделе МП «ЖЭК-3». Для продолжения нажимаем «Продолжить»;</li>
	<li>Далее необходимо ввести сумму к оплате. Для продолжения нажимаем «Продолжить»;</li>
	<li>Далее подтвердите платеж и устройство выдаст вам чек об оплате.</li>
</ol>
<br>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"projects", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "projects",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "7",
		"IBLOCK_TYPE" => "info",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "2",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "FILE",
			2 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>