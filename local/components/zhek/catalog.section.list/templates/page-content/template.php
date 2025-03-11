<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true); 
if (is_array($arResult["SECTION"]["PATH"])) 
	{
		$APPLICATION->SetTitle($arResult["SECTION"]["NAME"]); 
	}
?>

<?
    function renderItemLink($item) {

        if($item['PROPERTIES']['DOCS']['VALUE'])
            $fileArray = getFileArray($item["PROPERTIES"]["DOCS"]["VALUE"]);

        $url = $fileArray ? $fileArray['SRC'] : ($item['PROPERTIES']['LINK']['VALUE'] ?: ''/*$arElement["DETAIL_PAGE_URL"]*/);

        $target = '';

        if($item['PROPERTIES']['LINK']['VALUE'] && parse_url($url)["scheme"] && parse_url($url)['host']!==$_SERVER['SERVER_NAME'])
            $target = ' target="_blank"';
       
        $fileName = $fileArray['FILE_NAME']?:$item["NAME"];

        if($fileArray){
                //$target = ' target="_blank"';
            if($fileArray['CLASS'] != 'pdf')
                $target = ' download="'.str_replace('.', '_', $fileName) .'"';
            else
                $target = ' data-fancybox="pdf" data-caption="'.$fileName.'"';
        }

        if($url)
            $result = '<a class="d-flex align-items-center fs-5" title="'.$fileName.'" href="'.$url.'"'.$target.'>';
                //echo '<a class="top-detail-link'.($class?' d-flex align-items-center justify-content-between':'').'" href="'.$url.'"'.$target.'>';
        else
            $result =  '<span class="d-flex align-items-center justify-content-between">';

        

        if($fileArray && $fileArray['SRC']){
            $result .= '<div class="d-flex flex-wrap align-items-center justify-content-end! float-end! ps-0 me-2">';
            $result .= '<div class="ui-icon ui-icon-xs ui-icon-file-'.$fileArray['UI_ICON'].'"><i></i></div>';
            $result .= '</div>';
        }

        if($item['PROPERTIES']['LINK']['VALUE']){
            $result .= '<span class="material-symbols-outlined pe-1 me-1">open_in_new</span>';
        }

        $result .=  ($item["PREVIEW_TEXT"]?$item["PREVIEW_TEXT"]:$item["NAME"]);
        

        // if($item['DATE_ACTIVE_FROM'])
        // $result .= '<div class="d-flex flex-wrap align-items-center float-end ps-2">';
        //     $result .= '<span class="fs-7 small! lh-1 pe-2">'.$item['DATE_ACTIVE_FROM'].'</span>';
        // $result .= '</div>';

        if($url)
            $result .=  '</a>';
        else
            $result .=  '</span>';

        // if($fileArray && $fileArray['SRC']){
        //     $result .= '<div class="d-flex flex-wrap align-items-center justify-content-end float-end! ps-2">';
        //     $result .= '<div class="ui-icon ui-icon-xs ui-icon-file-'.$fileArray['UI_ICON'].'"><i></i></div>';
        //     $result .= '</div>';
        // }

        return $result;
    }
?>

<div class="catalog-section-list page-content tab-content" id="pills-tabContent">
	<?
	$TOP_DEPTH = $arResult["SECTION"]["DEPTH_LEVEL"];
	$CURRENT_DEPTH = $TOP_DEPTH;
    $LAST_FOLDER_DEPTH_LEVEL = $arParams["TOP_DEPTH"];
	?>
    <?if ($arResult['SECTION']["UF_FILE"]):?>
    <?$Attachment = $arResult['SECTION']["UF_FILE"];?>
    <?
        $File = CFile::GetById($Attachment)->Fetch();
        $Size = CFile::FormatSize($File['FILE_SIZE'], 0);
        $Format=substr($File["FILE_NAME"], strrpos($File["FILE_NAME"], '.') + 1);?>
                    <a title="<?echo $File["FILE_NAME"]?>" href="<?=CFile::GetPath($Attachment)?>">Скачать документ <i>(формат .<? echo $Format;?>)</i>
                    </a>
                    <span>(<? echo $Size;?>)</span>
    <?endif;?>
    <li class="tab-pane fade pt-3 pt-md-0 show active" id="home" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

    <?//if($arResult['SECTION']['UF_TABNAME']):?>
        <h3 class="catalog-section-title lh-sm pb-2 pt-0 ps-0 my-0"><?=$arResult['SECTION']['UF_TABNAME']??'Информация'?></h3>
    <?//endif;?>
    <?if($arResult['SECTION']['DESCRIPTION']):?>
        <div class="decription lh-sm">
        <?=$arResult['SECTION']['DESCRIPTION'];?>
        </div>
    <?endif;?>
    <?
    if (isset($arResult['SECTION']['ELEMENTS']) && !empty($arResult['SECTION']['ELEMENTS']))
    {
        echo '<ul class="top-elements mb-2">';
        foreach ($arResult['SECTION']['ELEMENTS'] as $arElement) {

            $this->AddEditAction($arElement['ID'], $arElement['ADD_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_ADD"));
            $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
			// $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            echo '<li class="d-flex align-items-center top-list template-tick-1! template-arrow-horizontal-2 pt-1"><div id="'.$this->GetEditAreaId($arElement['ID']).'" >';
                echo renderItemLink($arElement);
			echo '</div></li>';
        }
        echo '</ul>';
    }
    ?>
    <?if($arResult['SECTION']['UF_DESC']):?>
        <p class="decription"><?=$arResult['SECTION']['~UF_DESC']?></p>
    <?endif;?>
</li>
    <?
	foreach($arResult["SECTIONS"] as $arSection)
	{
        if ($arSection["DEPTH_LEVEL"] > $LAST_FOLDER_DEPTH_LEVEL)
        {
            continue;
        }

		/*if($CURRENT_DEPTH < $arSection["DEPTH_LEVEL"])
		{
			echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH),'<ul>';
		}
		elseif($CURRENT_DEPTH == $arSection["DEPTH_LEVEL"])*/
        if($CURRENT_DEPTH == $arSection["DEPTH_LEVEL"])
		{
			echo "</li>";
		}
		else
		{
			while($CURRENT_DEPTH > $arSection["DEPTH_LEVEL"])
			{
				echo "</li>";
				echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
				$CURRENT_DEPTH--;
			}
			echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</li>";
		}

		$count = $arParams["COUNT_ELEMENTS"] && $arSection["ELEMENT_CNT"] ? "&nbsp;(".$arSection["ELEMENT_CNT"].")" : "";

        $useElements = isset($arSection['ELEMENTS']) && !empty($arSection['ELEMENTS']) ?? false;
        
        $link = '';

        if(!$useElements && $arSection['UF_LINK'])
            $link .= '<a class="catalog-section-link py-0 ms-2! ps-2!" href="'.$arSection['UF_LINK'].'">';

        // if(!$useElements || $arSection['UF_LAST_FOLDER'])
        //     $link .= '<a class="catalog-section-link py-0 ms-2! ps-2!" href="'.($arSection['UF_LINK'] ? $arSection['UF_LINK'] : $arSection["SECTION_PAGE_URL"]).'">';
        
        $link .= '<h3 class="catalog-section-title lh-sm'.($useElements?' card-header pb-2 pt-0 ps-0':' ps-0').' my-0">'.($arSection["UF_LONGNAME"]?$arSection["UF_LONGNAME"]:$arSection["NAME"]).'</h3>';
        
        if(!$useElements && $arSection['UF_LINK'])
            $link .= '</a>';

		/*if ($_REQUEST['SECTION_ID']==$arSection['ID'] || !$arSection['UF_LAST_FOLDER'])
			$link = '<span class="catalog-section-title">'.($arSection["UF_LONGNAME"]?$arSection["UF_LONGNAME"]:$arSection["NAME"]).'</span>';
		else
			$link = '<a class="catalog-section-link" href="'.($arSection['UF_EXT_URI'] ? $arSection['UF_EXT_URI'] : $arSection["SECTION_PAGE_URL"]).'">'.($arSection["UF_LONGNAME"]?$arSection["UF_LONGNAME"]:$arSection["NAME"]).'</a>';
		*/
		echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH);
		?>
        <?
        $code = $arSection['ID'].'-'.(strpos($arSection['CODE'], '-') ? substr($arSection['CODE'], 0, strpos($arSection['CODE'], '-')) : $arSection['CODE']);
        ?>

        <li class="tab-pane fade pt-3 pt-md-0" id="<?=$code?>" role="tabpanel" aria-labelledby="<?=$code?>-tab" tabindex="0">
        <?/*<li class="<?=$useElements && !$arSection['UF_LAST_FOLDER']?'card ms-3!':'pb-0'?> pt-0 mt-2 mb-1 ps-0">*/?>
        <?=$link?>
        <?if($arSection['DESCRIPTION']):?>
            <div class="decription py-2">
                <?=$arSection['DESCRIPTION']?>
            </div>
        <?endif;?>
	    <?
        if ($useElements && !$arSection['UF_LAST_FOLDER'])
        {
            echo '<ul class="child list-group list-group-flush ms-0">';
			
            foreach ($arSection['ELEMENTS'] as $arElement) {

                $this->AddEditAction($arElement['ID'], $arElement['ADD_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_ADD"));
                $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
				//$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                
                echo '<li class="child-list list-group-item my-0 pt-1 ps-1 d-flex align-items-center template-tick-1! template-arrow-horizontal-2 border-bottom-0">
						<div id="'.$this->GetEditAreaId($arElement['ID']).'">';

                // dump($arElement);
                echo renderItemLink($arElement);

                /*$target = '';
                $class = '';
                $url = '';
                $fileArray = [];
               
                if($arElement["PROPERTIES"]["DOCS"]["VALUE"] && !$arElement["DETAIL_PICTURE"]){
                    $fileArray = getFileArray($arElement["PROPERTIES"]["FILE"]["VALUE"]);
                
                    $url = $fileArray['SRC'];
                    $class = $fileArray['CLASS'];
                    if($arElement["PROPERTIES"]["TARGET"]["VALUE"])
                        $target = ' target="_blank"';
                    elseif($fileArray['CLASS'] != 'pdf')
                        $target = ' download="'.$fileArray['FILE_NAME'].'"';
                    else
                        $target = ' data-fancybox="pdf" data-caption="'.$fileArray['FILE_NAME'].'"';
                }
                elseif($arElement["DETAIL_PICTURE"] && mb_strlen($arElement['DETAIL_TEXT'])< 1){
                    $url = $arElement["DETAIL_PICTURE"];
                    $target = ' data-fancybox';
                    $class = 'image';
                 
                    $fileArray = getFileArray($url,1);
                }
                // elseif(mb_strlen($arElement['DETAIL_TEXT'])>0)
                //     $url = $arElement["DETAIL_PAGE_URL"];

                if($url)
                    echo '<a class="top-detail-link'.($class?' d-flex align-items-center justify-content-between':'').'" href="'.$url.'"'.$target.'>';
                else
                    echo '<span class="d-flex align-items-center justify-content-between">';

                    echo $arElement["NAME"];

                    echo '<div class="d-flex flex-wrap align-items-center float-end">';
                    if($arElement['DATE_ACTIVE_FROM'])
                        echo '<span class="fs-7 small! lh-1 pe-2">'.$arElement['DATE_ACTIVE_FROM'].'</span>';
                  
                    if($fileArray && $fileArray['SRC'])
                        echo '<div class="ui-icon ui-icon-sm ui-icon-file-'.$fileArray['UI_ICON'].'"><i></i></div>';
                    //echo '<i class="fa-light fa-file-'.$class.' fa-2x"></i>';
                    echo '</div>';
                    //echo '<span class="float-end"><i class="fa-light fa-file-'.$class.' fa-2x"></i></span>';
                    //echo ($arElement["PREVIEW_TEXT"]?$arElement["PREVIEW_TEXT"]:$arElement["NAME"]);
                if($url)
                    echo '</a>';
                else
                    echo '</span>';*/

				echo '</div></li>';
            }
            echo '</ul>';
        }

		$CURRENT_DEPTH = $arSection["DEPTH_LEVEL"];

        if ($arSection['UF_LAST_FOLDER'])
        {
            $LAST_FOLDER_DEPTH_LEVEL = $arSection["DEPTH_LEVEL"];
        }
        else
        {
            $LAST_FOLDER_DEPTH_LEVEL = $arParams["TOP_DEPTH"];
        }
	}

	while($CURRENT_DEPTH > $TOP_DEPTH)
	{
        // echo "</div>";
		echo "</li>";
		// echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
		$CURRENT_DEPTH--;
	}
	?>
</div>
<script type="text/javascript">
</script>
