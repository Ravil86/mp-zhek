<?define('NOT_MENU',true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Реестр показаний");
?>
<?$APPLICATION->IncludeComponent(
								"zhek:meter",
								".default",
								[
									// 'IBLOCK_CODES' => [
									// 	'REQUEST' => 'documents',
									// 	'CITY' => 'areas',
									// ],
									'SEF_FOLDER' => '/meter/',
									'SEF_URL_TEMPLATES' => [
										'list' => '',
										'detail' => '#DETAIL_ID#/',
									],
									'SEF_MODE' => 'Y',
									// 'GROUP_CODES' => [
									// 	'MODERATOR' => 'OPERATORS',
									// 	'ADMINISTRATOR' => 'ADMIN',
									// ]
								]
							);
							?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>