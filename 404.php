<?
include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404", "Y");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");
$APPLICATION->SetPageProperty("keywords", "Страница не найдена");
$APPLICATION->SetPageProperty("description", "Страница не найдена");
?>

<div class="container-error-page">
        <div class="error-page-big-text">
            Страница не найдена
        </div>
        
 </div>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>	