<? define("NEED_AUTH", true);
define('LK', true);
define('WIDE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Реестр");
?>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="pills-not-tab" data-bs-toggle="pill" data-bs-target="#pills-not" type="button" role="tab" aria-controls="pills-not" aria-selected="true">Показания не подали</button>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="pills-all-tab" role="tab" href="/master/reestr/all-counter/">Все показния</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="pills-org-tab" role="tab" href="/master/reestr/all-org/">Все организации</a>
	</li>
</ul>

<h3 class="h6">Реестр объектов, организации которых не подали показания в текущем периоде</h3>
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
		'CLEAR_DATA'	=> "Y"
	]
);
?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>