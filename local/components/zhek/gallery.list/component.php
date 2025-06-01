<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $INTRANET_TOOLBAR;

use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if (!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if ($arParams["IBLOCK_TYPE"] == '')
	$arParams["IBLOCK_TYPE"] = "news";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["PARENT_SECTION"] = intval($arParams["PARENT_SECTION"]);
$arParams["INCLUDE_SUBSECTIONS"] = $arParams["INCLUDE_SUBSECTIONS"] != "N";
$arParams["SET_LAST_MODIFIED"] = $arParams["SET_LAST_MODIFIED"] === "Y";

$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
if ($arParams["SORT_BY1"] == '')
	$arParams["SORT_BY1"] = "ACTIVE_FROM";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"]))
	$arParams["SORT_ORDER1"] = "DESC";

if ($arParams["SORT_BY2"] == '') {
	if (mb_strtoupper($arParams["SORT_BY1"]) == 'SORT') {
		$arParams["SORT_BY2"] = "ID";
		$arParams["SORT_ORDER2"] = "DESC";
	} else {
		$arParams["SORT_BY2"] = "SORT";
	}
}
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"]))
	$arParams["SORT_ORDER2"] = "ASC";

if ($arParams["FILTER_NAME"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
	$arrFilter = array();
} else {
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if (!is_array($arrFilter))
		$arrFilter = array();
}

$arParams["CHECK_DATES"] = $arParams["CHECK_DATES"] != "N";

if (!is_array($arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"] = array();
foreach ($arParams["FIELD_CODE"] as $key => $val)
	if (!$val)
		unset($arParams["FIELD_CODE"][$key]);

if (empty($arParams["PROPERTY_CODE"]) || !is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
foreach ($arParams["PROPERTY_CODE"] as $key => $val)
	if ($val === "")
		unset($arParams["PROPERTY_CODE"][$key]);

$arParams["DETAIL_URL"] = trim($arParams["DETAIL_URL"]);

$arParams["NEWS_COUNT"] = intval($arParams["NEWS_COUNT"]);
if ($arParams["NEWS_COUNT"] <= 0)
	$arParams["NEWS_COUNT"] = 20;

$arParams["CACHE_FILTER"] = $arParams["CACHE_FILTER"] == "Y";
if (!$arParams["CACHE_FILTER"] && count($arrFilter) > 0)
	$arParams["CACHE_TIME"] = 0;

$arParams["SET_TITLE"] = $arParams["SET_TITLE"] != "N";
$arParams["SET_BROWSER_TITLE"] = (isset($arParams["SET_BROWSER_TITLE"]) && $arParams["SET_BROWSER_TITLE"] === 'N' ? 'N' : 'Y');
$arParams["SET_META_KEYWORDS"] = (isset($arParams["SET_META_KEYWORDS"]) && $arParams["SET_META_KEYWORDS"] === 'N' ? 'N' : 'Y');
$arParams["SET_META_DESCRIPTION"] = (isset($arParams["SET_META_DESCRIPTION"]) && $arParams["SET_META_DESCRIPTION"] === 'N' ? 'N' : 'Y');
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"] != "N"; //Turn on by default
$arParams["INCLUDE_IBLOCK_INTO_CHAIN"] = $arParams["INCLUDE_IBLOCK_INTO_CHAIN"] != "N";
$arParams["STRICT_SECTION_CHECK"] = (isset($arParams["STRICT_SECTION_CHECK"]) && $arParams["STRICT_SECTION_CHECK"] === "Y");
$arParams["ACTIVE_DATE_FORMAT"] = trim($arParams["ACTIVE_DATE_FORMAT"]);
if ($arParams["ACTIVE_DATE_FORMAT"] == '')
	$arParams["ACTIVE_DATE_FORMAT"] = $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT"));
$arParams["PREVIEW_TRUNCATE_LEN"] = intval($arParams["PREVIEW_TRUNCATE_LEN"]);
$arParams["HIDE_LINK_WHEN_NO_DETAIL"] = $arParams["HIDE_LINK_WHEN_NO_DETAIL"] == "Y";

$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"] == "Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"] != "N";
$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"] == "Y";
$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"] == "Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"] == "Y";
$arParams["CHECK_PERMISSIONS"] = ($arParams["CHECK_PERMISSIONS"] ?? '') != "N";

if ($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"]) {
	$arNavParams = array(
		"nPageSize" => $arParams["NEWS_COUNT"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
		"bShowAll" => $arParams["PAGER_SHOW_ALL"],
	);
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	if ($arNavigation["PAGEN"] == 0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] > 0)
		$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
} else {
	$arNavParams = array(
		"nTopCount" => $arParams["NEWS_COUNT"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
	);
	$arNavigation = false;
}

if (empty($arParams["PAGER_PARAMS_NAME"]) || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PAGER_PARAMS_NAME"])) {
	$pagerParameters = array();
} else {
	$pagerParameters = $GLOBALS[$arParams["PAGER_PARAMS_NAME"]];
	if (!is_array($pagerParameters))
		$pagerParameters = array();
}

$arParams["USE_PERMISSIONS"] = ($arParams["USE_PERMISSIONS"] ?? '') == "Y";
if (!is_array(($arParams["GROUP_PERMISSIONS"] ?? null)))
	$arParams["GROUP_PERMISSIONS"] = array(1);

$bUSER_HAVE_ACCESS = !$arParams["USE_PERMISSIONS"];
if ($arParams["USE_PERMISSIONS"] && isset($GLOBALS["USER"]) && is_object($GLOBALS["USER"])) {
	$arUserGroupArray = $USER->GetUserGroupArray();
	foreach ($arParams["GROUP_PERMISSIONS"] as $PERM) {
		if (in_array($PERM, $arUserGroupArray)) {
			$bUSER_HAVE_ACCESS = true;
			break;
		}
	}
}

if ($this->startResultCache(false, array(($arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups()), $bUSER_HAVE_ACCESS, $arNavigation, $arrFilter, $pagerParameters))) {
	if (!Loader::includeModule("fileman")) {
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	CMedialib::Init();

	class MedialibCollectionTools
	{

		static function GetRecursive($collection_id = 0, $arCollections, $arOrder, $arFilter)
		{

			$arFilterTmp = $arFilter;
			$arFilterTmp["PARENT_ID"] = $collection_id;
			$rsCollections = CMedialibCollection::GetList(array('arOrder' => $arOrder, 'arFilter' => $arFilterTmp));
			//gg( $rsCollections);
			foreach ($rsCollections as $arCollection) {
				$arCollections[$arCollection["ID"]] = $arCollection;
				self::GetRecursive($arCollection["ID"], $arCollections, $arOrder, $arFilter);
			}

			return $arCollections;
		}
	}

	$arResult["GATEGORY"] = array();
	$arResult["ITEMS"] = array();

	if (!$arParams['ID_COLLECTION']) {
		$arCollections = array();
		$collection_id = 0; // ID основной коллекции, для полного дерева коллекций указываем 0
		$arResult["GATEGORY"] = MedialibCollectionTools::GetRecursive($collection_id, $arCollections, array('NAME' => 'ASC'), array('ACTIVE' => 'Y', "ML_TYPE" => "1"));

		foreach ($arResult["GATEGORY"] as $key => $collection) {
			$MedialibItem = CMedialibItem::GetList(array('arCollections' => array("0" => $collection['ID'])));

			foreach ($MedialibItem as $key => $item) {
				$arResult["ITEMS"][$item['ID']] = $item;
			}
			arsort($arResult["ITEMS"]);

			$arResult["ELEMENTS"][] = $MedialibItem;
		}
	} else {
		$MedialibItem = CMedialibItem::GetList(array('arCollections' => array("0" => $arParams['ID_COLLECTION'])));

		foreach ($MedialibItem as $key => $item) {
			$arResult["ITEMS"][$item['ID']] = $item;
		}
		arsort($arResult["ITEMS"]);

		$arResult["ELEMENTS"][] = $MedialibItem;
	}

	//$arResult = $rsIBlock->GetNext();
	if (!$arResult) {
		$this->abortResultCache();
		Iblock\Component\Tools::process404(
			trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_NEWS_NA"),
			true,
			$arParams["SET_STATUS_404"] === "Y",
			$arParams["SHOW_404"] === "Y",
			$arParams["FILE_404"]
		);
		return;
	}

	$this->setResultCacheKeys(array(
		"ID",
		"IBLOCK_TYPE_ID",
		"LIST_PAGE_URL",
		"NAV_CACHED_DATA",
		"NAME",
		"SECTION",
		"ELEMENTS",
		"IPROPERTY_VALUES",
		"ITEMS_TIMESTAMP_X",
	));
	$this->includeComponentTemplate();
}
