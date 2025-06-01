<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Компания",
	"DESCRIPTION" => GetMessage("T_IBLOCK_DESC_LIST_DESC"),
	"ICON" => "/images/news_list.gif",
	"SORT" => 20,
	"CACHE_PATH" => "Y",
	"PATH" => array(
			"ID" => "zhek",
			"NAME" => 'ЖЭК',
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "master",
				"NAME" => 'Мастер',
			),
	),
);
?>