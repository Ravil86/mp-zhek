<? define("NEED_AUTH", true);
define('LK', true);
define('WIDE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Реестр");
?>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="pills-not-tab" role="tab" href="/master/reestr/alert/">Показания не подали</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="pills-all-tab" role="tab" href="/master/reestr/all-counter/">Все показния</a>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="pills-org-tab" role="tab">Все организации</button>
	</li>
</ul>

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