<? define("NEED_AUTH", true);
define('LK', true);
define('WIDE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Реестр");
?>
<h3 class="h6">Реестр всех организаций</h3>
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
		],
		'ALL_ORG' => 'Y'
	]
);
?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>