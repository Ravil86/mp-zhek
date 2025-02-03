<? define("NEED_AUTH", true);
define('LK', true);
define('WIDE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Реестр");
?>
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
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>