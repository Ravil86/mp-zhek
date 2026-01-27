<? define("NEED_AUTH", true);
define('LK', true);
define('WIDE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Реестр");
?>
<h3 class="h6 mb-3">Реестр объектов, организации которых не подали показания в текущем периоде</h3>
<? $APPLICATION->IncludeComponent(
	"zhek:master.reestr",
	"new",
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
		// 'CLEAR_DATA'	=> "Y"
	]
);
?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>