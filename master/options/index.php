<?
define("NEED_AUTH", true);
define('LK', true);
define('WIDE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Настройки");
CJSCore::Init(array('date'));
?>
<?
$APPLICATION->IncludeComponent(
	"zhek:master.options",
	"",
	[
		'SEF_MODE' => 'N',
		'GROUP_CODES' => [
			'MODERATOR' => 'OPERATORS',
			'ADMINISTRATOR' => 'MASTER',
		]
	]
);
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>