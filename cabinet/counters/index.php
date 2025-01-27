<? define("NEED_AUTH", true);
define("LK", true);
//define('NOT_MENU',true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Показаний приборов учета");
?>
<? // dump(LKClass::myCompany());
?>
<? $APPLICATION->IncludeComponent(
	"zhek:cabinet.counters",
	".default",
	[
		'SEF_FOLDER' => '/cabinet/counters/',
		'SEF_URL_TEMPLATES' => [
			'list' => '',
			'detail' => '#DETAIL_ID#/',
		],
		'SEF_MODE' => 'Y',
		'GROUP_CODES' => [
			'ORGANIZATION' => 'ORG',
			'ADMINISTRATOR' => 'ADMIN',
		]
	]
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>