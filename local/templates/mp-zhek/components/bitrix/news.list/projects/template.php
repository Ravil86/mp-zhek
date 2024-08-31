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
?>

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
	<ul class="projects-list owl-carousel owl-theme horizontal-carousel! clearfix page-margin-top!">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

			$preview = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>400, 'height'=>280), BX_RESIZE_IMAGE_EXACT, true);

			$detail = $arItem['DETAIL_PICTURE']['SRC']?:($arItem['PROPERTIES']['FILE']['VALUE']?CFile::GetPath($arItem['PROPERTIES']['FILE']['VALUE']):'');
			?>
			<li class="project-item me-0" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="d-flex flex-column justify-content-between">
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<?if($detail):?>
									<a href="<?=$detail?>" title="<?=$arItem["NAME"]?>" data-fancybox="pamyatki_mobile">
								<?else:?>
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" target="_blank">
								<?endif;?>
									<img
										class="preview_picture"
										src="<?=$preview["src"]?>"
										alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
								/></a>
							<?else:?>
								<img
									class="preview_picture"
									src="<?=$preview["src"]?>"
									alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
									/>
							<?endif;?>
						<?else:?>
							<img src="images/samples/480x320/placeholder.jpg" alt="">
						<?endif?>
							<div class="view align-center">
								<div class="vertical-align-table">
									<div class="vertical-align-cell">
										<p><?=$arItem["NAME"]?></p>
										<?if($detail):?><a class="more simple" href="<?=$detail?>" data-fancybox="pamyatki" title="<?=$arItem["NAME"]?>">Посмотреть</a><?endif;?>
									</div>
								</div>
							</div>
						
						<?/*if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
							<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
						<?endif?>
						<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<h4 class="pt-1"><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h4>
							<?else:?>
								<h4 class="pt-1"><?echo $arItem["NAME"]?></h4>
							<?endif;?>
						<?endif;?>
						<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<p><?=TruncateText($arItem["PREVIEW_TEXT"], 100);?></p>
						<?endif;?>
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
							<div style="clear:both"></div>
						<?endif*/?>
						<?/*foreach($arItem["FIELDS"] as $code=>$value):?>
							<small>
							<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
							</small><br />
						<?endforeach;*/?>
						<?/*foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
							<small>
							<?=$arProperty["NAME"]?>:&nbsp;
							<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
								<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
							<?else:?>
								<?=$arProperty["DISPLAY_VALUE"];?>
							<?endif?>
							</small><br />
						<?endforeach;*/?>
				</div>
			</li>
		<?endforeach;?>
	</ul>
	<div class="cm-carousel-pagination"></div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>

<script>
	$(document).ready(function(){
		$(".projects-list").owlCarousel({
			loop:true,
			margin:10,
			//autoHeight:true,
			// responsiveClass:true,
			responsive:{
				0:{
					items:1,
					nav:false
				},
				500:{
					items:2,
					nav:false
				},
				750:{
					items:3,
					nav:false
				},
				1000:{
					items:4,
					nav:false,
					loop:false
				}
			}
		});
	});
</script>