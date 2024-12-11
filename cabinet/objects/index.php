<? define("NEED_AUTH", true);
define('LK', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Кабинет");
?>
<!-- <h3>Объекты предприятия</h3> -->
<? $APPLICATION->IncludeComponent(
	"zhek:cabinet.objects",
	".default",
	[
		'SEF_FOLDER' => '/cabinet/objects/',
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