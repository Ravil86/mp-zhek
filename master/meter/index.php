<? define("NEED_AUTH", true);
define('LK', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Кабинет");
?>
<? $APPLICATION->IncludeComponent(
	"zhek:master.meter",
	".default",
	[
		'SEF_FOLDER' => '/master/meter/',
		'SEF_URL_TEMPLATES' => [
			'list' => '',
			'detail' => '#DETAIL_ID#/',
		],
		'SEF_MODE' => 'Y',
		'GROUP_CODES' => [
			'MODERATOR' => 'OPERATORS',
			'ADMINISTRATOR' => 'ADMIN',
		]
	]
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>