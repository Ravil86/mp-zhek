<?
define("NEED_AUTH", true);
define("LK", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Кабинет");
?>
<?
if (LKClass::isMaster())
	LocalRedirect("/master/");
else
	LocalRedirect("/cabinet/counters/");
?>
<?/* $APPLICATION->IncludeComponent(
	"zhek:cabinet.company",
	".default",
	[
		// 'IBLOCK_CODES' => [
		// 	'REQUEST' => 'documents',
		// 	'CITY' => 'areas',
		// ],
		'SEF_FOLDER' => '/cabinet/',
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
);*/
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>