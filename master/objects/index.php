<?
define("NEED_AUTH", true);
define('LK', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Кабинет");
?>
<?
$APPLICATION->IncludeComponent(
	"zhek:master.objects",
	".default",
	[
		'SEF_FOLDER' => '/admin/objects/',
		'SEF_URL_TEMPLATES' => [
			'list' => '',
			'detail' => '#OBJECT_ID#/',
		],
		'SEF_MODE' => 'Y',
		'GROUP_CODES' => [
			'MODERATOR' => 'OPERATORS',
			'ADMINISTRATOR' => 'ADMIN',
		]
	]
);

// $APPLICATION->IncludeComponent(
// 	"zhek:master.objects",
// 	".default",
// 	array(
// 		"SEF_FOLDER" => "/master/objects/",
// 		"SEF_MODE" => "Y",
// 		"SEF_URL_TEMPLATES" => ['list' => '', 'detail' => '#DETAIL_ID#/',]
// 	)
// );
?>
<!-- <h3>Объекты предприятия</h3> -->
<?
/*$APPLICATION->IncludeComponent(
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
			'ORGANIZATION' => 'MASTER',
			'ADMINISTRATOR' => 'ADMIN',
		]
	]
);*/
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>