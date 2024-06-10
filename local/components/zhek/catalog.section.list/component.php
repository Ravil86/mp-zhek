<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

\Bitrix\Main\UI\Extension::load("ui.icons.disk");

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/*************************************************************************
Processing of received parameters
 *************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);
$arParams["SECTION_CODE"] = trim($arParams["SECTION_CODE"]);

$arParams["SECTION_URL"]=trim($arParams["SECTION_URL"]);

$arParams["TOP_DEPTH"] = intval($arParams["TOP_DEPTH"]);
if($arParams["TOP_DEPTH"] <= 0)
	$arParams["TOP_DEPTH"] = 2;
$arParams["COUNT_ELEMENTS"] = $arParams["COUNT_ELEMENTS"]!="N";
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"]!="N"; //Turn on by default

$arResult["SECTIONS"]=array();

/*************************************************************************
Work with cache
 *************************************************************************/
if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
{
	if(!\Bitrix\Main\Loader::includeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$arFilter = array(
		"ACTIVE" => "Y",
#		"GLOBAL_ACTIVE" => "Y",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"CNT_ACTIVE" => "Y",
	);

	$arSelect = array();
	if(array_key_exists("SECTION_FIELDS", $arParams) && !empty($arParams["SECTION_FIELDS"]) && is_array($arParams["SECTION_FIELDS"]))
	{
		foreach($arParams["SECTION_FIELDS"] as &$field)
		{
			if (!empty($field) && is_string($field))
				$arSelect[] = $field;
		}
		if (isset($field))
			unset($field);
	}

	if(!empty($arSelect))
	{
		$arSelect[] = "ID";
		$arSelect[] = "NAME";
		$arSelect[] = "LEFT_MARGIN";
		$arSelect[] = "RIGHT_MARGIN";
		$arSelect[] = "DEPTH_LEVEL";
		$arSelect[] = "IBLOCK_ID";
		$arSelect[] = "IBLOCK_SECTION_ID";
		$arSelect[] = "LIST_PAGE_URL";
		$arSelect[] = "SECTION_PAGE_URL";
	}
	$boolPicture = empty($arSelect) || in_array('PICTURE', $arSelect);

	if(isset($arParams['SECTION_USER_FIELDS']) && !empty($arParams["SECTION_USER_FIELDS"]) && is_array($arParams["SECTION_USER_FIELDS"]))
	{
		foreach($arParams["SECTION_USER_FIELDS"] as &$field)
		{
			if(is_string($field) && preg_match("/^UF_/", $field))
				$arSelect[] = $field;
		}
		if (isset($field))
			unset($field);
	}

	$arResult["SECTION"] = false;
	$intSectionDepth = 0;
	if($arParams["SECTION_ID"]>0)
	{
		$arFilter["ID"] = $arParams["SECTION_ID"];
		$rsSections = CIBlockSection::GetList(array(), $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
		$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult["SECTION"] = $rsSections->GetNext();
	}
	elseif('' != $arParams["SECTION_CODE"])
	{
		$arFilter["=CODE"] = $arParams["SECTION_CODE"];
		$rsSections = CIBlockSection::GetList(array(), $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
		$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult["SECTION"] = $rsSections->GetNext();
	}

	if(is_array($arResult["SECTION"]))
	{
		unset($arFilter["ID"]);
		unset($arFilter["=CODE"]);
		$arFilter["LEFT_MARGIN"]=$arResult["SECTION"]["LEFT_MARGIN"]+1;
		$arFilter["RIGHT_MARGIN"]=$arResult["SECTION"]["RIGHT_MARGIN"];
		$arFilter["<="."DEPTH_LEVEL"]=$arResult["SECTION"]["DEPTH_LEVEL"] + $arParams["TOP_DEPTH"];

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
		$arResult["SECTION"]["IPROPERTY_VALUES"] = $ipropValues->getValues();

		$arResult["SECTION"]["PATH"] = array();
		$rsPath = CIBlockSection::GetNavChain($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
		$rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
		while($arPath = $rsPath->GetNext())
		{
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $arPath["ID"]);
			$arPath["IPROPERTY_VALUES"] = $ipropValues->getValues();
			$arResult["SECTION"]["PATH"][]=$arPath;
		}

		// элементы корневого раздела
		$arResult["SECTION"]["ELEMENTS"] = array();
		$arElementsSelect = Array(
			"IBLOCK_ID",
			"SECTION_ID",
			"ID",
			"NAME",
			"SORT",
			"DETAIL_PAGE_URL",
			"PREVIEW_TEXT",
			"DETAIL_TEXT",
			"DATE_ACTIVE_FROM"
		);

		//Первая сортировка 
		$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
		if(strlen($arParams["SORT_BY1"])<=0)
			$arParams["SORT_BY1"] = "SORT";
			// $arParams["SORT_BY1"] = "ACTIVE_FROM";
		if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"]))
			$arParams["SORT_ORDER1"]="ASC";
			// $arParams["SORT_ORDER1"]="DESC";
		
		//Вторая сортировка
		if(strlen($arParams["SORT_BY2"])<=0)
		{
			if (strtoupper($arParams["SORT_BY1"]) == 'SORT')
			{
				$arParams["SORT_BY2"] = "ID";
				$arParams["SORT_ORDER2"] = "DESC";
			}
			else
			{
				$arParams["SORT_BY2"] = "SORT";
			}
		}
		if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"]))
			$arParams["SORT_ORDER2"]="ASC";

		// сортировку берем из параметров компонента
		$arElementsSort = array(
			$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
			$arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
		);

		$arElementsFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $arResult["SECTION"]["ID"], "ACTIVE"=>"Y", "ACTIVE_TO"=>"Y");
#        $arElementsFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $arResult["SECTION"]["ID"], "ACTIVE_TO"=>"Y");
		$rsElements = CIBlockElement::GetList($arElementsSort, $arElementsFilter, false, false, $arElementsSelect);
		$arParentElements=[];
		while($arElement = $rsElements->GetNext())
		{
			$arButtons = CIBlock::GetPanelButtons(
				$arElement["IBLOCK_ID"],
				$arElement["ID"],
				0,
				array("SECTION_BUTTONS"=>false, "SESSID"=>false)
			);
			$arElement["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
			$arElement["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

			$arElement['DETAIL_PAGE_URL'] = CIBlock::ReplaceDetailUrl($arElement['DETAIL_PAGE_URL'], $arElement, false, 'E');
			$arElement['PROPERTIES'] = [];
			
			$arParentElements[$arElement['ID']] = $arElement;
		}
		CIBlockElement::GetPropertyValuesArray($arParentElements, $arElementsFilter['IBLOCK_ID'], $arElementsFilter);

		foreach ($arParentElements as $key => &$element) {
			foreach ($element['PROPERTIES'] as $pid => $prop) {
				unset($element['PROPERTIES'][$pid]);

				// if($prop['PROPERTY_TYPE'] == 'F'){
				// 	$prop['VALUE'] = CFile::GetByID($prop['VALUE'])->Fetch();
				// }
				
				$element['PROPERTIES'][$pid]['NAME'] 		= $prop['NAME'];
				$element['PROPERTIES'][$pid]['DESCRIPTION'] = $prop['DESCRIPTION'];
				$element['PROPERTIES'][$pid]['VALUE'] 		= $prop['VALUE'];
			}
		}
		if($arParentElements)
			$arResult["SECTION"]["ELEMENTS"] = $arParentElements;
	}
	else
	{
		$arResult["SECTION"] = array("ID"=>0, "DEPTH_LEVEL"=>0);
		$arFilter["<="."DEPTH_LEVEL"] = $arParams["TOP_DEPTH"];
	}
	$intSectionDepth = $arResult["SECTION"]['DEPTH_LEVEL'];

	//ORDER BY
	$arSort = array(
		"SORT"=>"ASC",
		"left_margin"=>"desc",
		// "left_margin"=>"asc",
	);
	//EXECUTE
	$rsSections = CIBlockSection::GetList($arSort, $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
	$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
	while($arSection = $rsSections->GetNext())
	{
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection["IBLOCK_ID"], $arSection["ID"]);
		$arSection["IPROPERTY_VALUES"] = $ipropValues->getValues();

		if ($boolPicture)
		{
			$mxPicture = false;
			$arSection["PICTURE"] = intval($arSection["PICTURE"]);
			if (0 < $arSection["PICTURE"])
				$mxPicture = CFile::GetFileArray($arSection["PICTURE"]);
			$arSection["PICTURE"] = $mxPicture;
			if ($arSection["PICTURE"])
			{
				$arSection["PICTURE"]["ALT"] = $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"];
				if ($arSection["PICTURE"]["ALT"] == "")
					$arSection["PICTURE"]["ALT"] = $arSection["NAME"];
				$arSection["PICTURE"]["TITLE"] = $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"];
				if ($arSection["PICTURE"]["TITLE"] == "")
					$arSection["PICTURE"]["TITLE"] = $arSection["NAME"];
			}
			$arSection["SITE_LANGUAGE"] = LANGUAGE_ID;
		}
		$arSection['RELATIVE_DEPTH_LEVEL'] = $arSection['DEPTH_LEVEL'] - $intSectionDepth;

		$arButtons = CIBlock::GetPanelButtons(
			$arSection["IBLOCK_ID"],
			0,
			$arSection["ID"],
			array("SESSID"=>false, "CATALOG"=>true)
		);
		$arSection["EDIT_LINK"] = $arButtons["edit"]["edit_section"]["ACTION_URL"];
		$arSection["DELETE_LINK"] = $arButtons["edit"]["delete_section"]["ACTION_URL"];

		// элементы подразделов
		$arSection["ELEMENTS"] = array();
		$arElementsSelect = Array(
			"IBLOCK_ID",
			"SECTION_ID",
			"ID",
			"NAME",
			"SORT",
			"DETAIL_PAGE_URL",
			"PREVIEW_TEXT",
			"PREVIEW_PICTURE",
			"DETAIL_PICTURE",
			"DETAIL_TEXT",
			"DATE_ACTIVE_FROM"
		);
		// dump($arElementsSort);
		$arElementsFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $arSection["ID"], "ACTIVE"=>"Y", "ACTIVE_TO"=>"Y");
		$rsElements = CIBlockElement::GetList($arElementsSort, $arElementsFilter, false, false, $arElementsSelect);
		// $rsElements = CIBlockElement::GetList(Array("SORT"=>"ASC","ID"=>"ASC"), $arElementsFilter, false, false, $arElementsSelect);

		$arElements=[];
		while($arElement = $rsElements->Fetch())
		{
			$arButtons = CIBlock::GetPanelButtons(
				$arElement["IBLOCK_ID"],
				$arElement["ID"],
				0,
				array("SECTION_BUTTONS"=>true, "SESSID"=>false)
			);
			$arElement["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
			$arElement["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
			$arElement["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
			$arElement["PREVIEW_PICTURE"] = CFile::GetPath($arElement["PREVIEW_PICTURE"]);
			$arElement["DETAIL_PICTURE"] = CFile::GetPath($arElement["DETAIL_PICTURE"]);
			$arElement['DETAIL_PAGE_URL'] = CIBlock::ReplaceDetailUrl($arElement['DETAIL_PAGE_URL'], $arElement, false, 'E');
			$arElement['PROPERTIES'] = [];
			
			$arElements[$arElement['ID']] = $arElement;
		}
		CIBlockElement::GetPropertyValuesArray($arElements, $arElementsFilter['IBLOCK_ID'], $arElementsFilter);

		foreach ($arElements as $key => &$element) {
			foreach ($element['PROPERTIES'] as $pid => $prop) {
				unset($element['PROPERTIES'][$pid]);
				//if($prop['VALUE']){
					$element['PROPERTIES'][$pid]['NAME'] 		= $prop['NAME'];
					$element['PROPERTIES'][$pid]['DESCRIPTION'] = $prop['~DESCRIPTION'];
					$element['PROPERTIES'][$pid]['VALUE'] 		= $prop['~VALUE'];
				//}
			}
		}
		$arSection["ELEMENTS"] = $arElements;

		$arResult["SECTIONS"][]=$arSection;
	}

	$arResult["SECTIONS_COUNT"] = count($arResult["SECTIONS"]);

	$this->SetResultCacheKeys(array(
		"SECTIONS_COUNT",
		"SECTION",
	));

	$this->IncludeComponentTemplate();
}

if($arResult["SECTIONS_COUNT"] > 0 || isset($arResult["SECTION"]))
{
	if($arParams["ADD_SECTIONS_CHAIN"] && isset($arResult["SECTION"]) && is_array($arResult["SECTION"]["PATH"]))
	{
		foreach($arResult["SECTION"]["PATH"] as $arPath)
		{
			if (isset($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
				$APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
			else
				$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
		}
	}
}

global $APPLICATION;
$APPLICATION->SetTitle($arResult['SECTION']['NAME']);

// dump($arResult['SECTION']['NAME']);



/*$arButtons = CIBlock::GetPanelButtons(
	$arParams["IBLOCK_ID"],
	0,
	$arParams["SECTION_ID"],
	array("CATALOG"=>true)
);

if($APPLICATION->GetShowIncludeAreas())
	$this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));*/

?>