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
	"objects",
	[
		'SEF_FOLDER' => '/master/counter/objects/',
		'SEF_URL_TEMPLATES' => [
			'list' => '',
			'detail' => '#DETAIL_ID#/',
		],
		'SEF_MODE' => 'Y',
		'TYPE' => 'objects',
		'PAGE_SIZE' => 20,
		'GROUP_CODES' => [
			'ADMINISTRATOR' => 'MASTER',
			'ORGANIZATION' => 'ORG',
			// 'ADMINISTRATOR' => 'ADMIN',
		]
	]
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>