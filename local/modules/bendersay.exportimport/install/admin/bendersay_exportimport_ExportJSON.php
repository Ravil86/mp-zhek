<?
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bendersay.exportimport/admin/ExportJSON.php")) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bendersay.exportimport/admin/ExportJSON.php");
} else {
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/bendersay.exportimport/admin/ExportJSON.php");
}