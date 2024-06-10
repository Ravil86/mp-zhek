<?
define('NOT_MENU',true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Документы");
?><div class="row">
	<div class="col-lg-4 col-md-5">
		 <?$APPLICATION->IncludeComponent(
	"zhek:catalog.section.list",
	"vertical-menu",
	Array(
		"ADDITIONAL_COUNT_ELEMENTS_FILTER" => "",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "360000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "vertical-menu",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_ELEMENTS" => "N",
		"COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
		"FILTER_NAME" => "",
		"HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
		"HIDE_SECTION_NAME" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "structura",
		"LIST_COLUMNS_COUNT" => "6",
		"SECTION_CODE" => "dokumenty",
		"SECTION_FIELDS" => array(0=>"",1=>"",),
		"SECTION_ID" => "",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(0=>"UF_TABNAME",1=>"",),
		"SHOW_ANGLE" => "Y",
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "1",
		"VIEW_MODE" => "TILE"
	)
);?>
	</div>
	<div class="col-lg-8 col-md-7">
		 <?$APPLICATION->IncludeComponent(
	"zhek:catalog.section.list", 
	"page-content", 
	array(
		"ADDITIONAL_COUNT_ELEMENTS_FILTER" => "",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "360000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "page-content",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_ELEMENTS" => "N",
		"COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
		"FILTER_NAME" => "",
		"HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
		"HIDE_SECTION_NAME" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "structura",
		"LIST_COLUMNS_COUNT" => "6",
		"SECTION_CODE" => "dokumenty",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_ID" => "",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_ANGLE" => "Y",
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "2",
		"VIEW_MODE" => "TILE"
	),
	false
);?>
	</div>
</div><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>