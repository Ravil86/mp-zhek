<?
include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404", "Y");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");
$APPLICATION->SetPageProperty("keywords", "Страница не найдена");
$APPLICATION->SetPageProperty("description", "Страница не найдена");
?>

<div class="container theme-page padding-bottom-115">
	<div class="clearfix page-404 top-border">
		<div class="row page-margin-top-section">
			<div class="col-12 column">
				<h1>404</h1>
				<h3 class="margin-top-37">Страница не найдена</h3>
				<p class="description align-center">Извините, страница, которую вы ищете, не найдена. Вернитесь на <a href="/" title="Вернуться на главную">главную</a></p>
			</div>
		</div>
	</div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>	