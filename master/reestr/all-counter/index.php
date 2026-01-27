<? define("NEED_AUTH", true);
define('LK', true);
define('WIDE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Реестр");
?>
<h3 class="h6">Реестр объектов всех показаний</h3>
<? $APPLICATION->IncludeComponent(
	"zhek:master.reestr",
	".default",
	[
		'SEF_FOLDER' => '/master/contracts/',
		'SEF_URL_TEMPLATES' => [
			'list' => '',
			'detail' => '#DETAIL_ID#/',
		],
		'SEF_MODE' => 'Y',
		'GROUP_CODES' => [
			// 'OPERATOR' => 'OPERATORS',
			'ADMINISTRATOR' => 'MASTER',
		]
	]
);
?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>