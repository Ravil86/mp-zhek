<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Приборы учёта",
	"ICON" => "/images/news_list.gif",
	"SORT" => 20,
	"CACHE_PATH" => "Y",
	"PATH" => array(
			"ID" => "zhek",
			"NAME" => 'ЖЭК',
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "cabinet",
				"NAME" => 'Кабинет',
			),
	),
);
?>