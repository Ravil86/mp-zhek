<? define("NEED_AUTH", true);
define('LK', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контракты (справки)");
?>
<? $APPLICATION->IncludeComponent(
	"zhek:master.contracts",
	".default",
	[
		'SEF_FOLDER' => '/cabinet/contracts/',
		'SEF_URL_TEMPLATES' => [
			'list' => '',
			'detail' => '#DETAIL_ID#/',
		],
		'SEF_MODE' => 'Y',
		'GROUP_CODES' => [
			'OPERATOR' => 'ORG',
			'ADMINISTRATOR' => 'MASTER',
		]
	]
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>