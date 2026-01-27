<?
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bendersay.exportimport/admin/ImportCSV.php")) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bendersay.exportimport/admin/ImportCSV.php");
} else {
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/bendersay.exportimport/admin/ImportCSV.php");
}