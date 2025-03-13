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
		<button class="nav-link" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="false">Все показния</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="pills-org-tab" data-bs-toggle="pill" data-bs-target="#pills-org" type="button" role="tab" aria-controls="pills-org" aria-selected="false">Все организации</button>
	</li>
</ul>
<div class="tab-content" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-not" role="tabpanel" aria-labelledby="pills-not-tab" tabindex="0">
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
	<div class="tab-pane fade" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab" tabindex="0">
		<h3 class="h6">Реестр объектов всех показаний</h3>
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
				]
			]
		);
		?>
	</div>
	<div class="tab-pane fade" id="pills-org" role="tabpanel" aria-labelledby="pills-org-tab" tabindex="0">
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
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>