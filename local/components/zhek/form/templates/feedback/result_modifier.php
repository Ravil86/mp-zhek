<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();

$arSelect = ['ID', 'CODE', 'NAME'];
$rsSect = CIBlockSection::GetList(["SORT"=>"ASC"],['IBLOCK_ID'=>$arParams['IBLOCK_ID_SERVICE'], 'ACTIVE'=>'Y'], false, $arSelect);
while ($arSect = $rsSect->GetNext())
{
    $arSections[] = $arSect;
}
$arResult['SERVICES'] = $arSections;
?>