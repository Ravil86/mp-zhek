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
<div class="carousel-container margin-top-65 padding-bottom-60 clearfix">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
	<ul class="owl-carousel owl-theme services-list services-icons horizontal-carousel! gray clearfix">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="h-100 service-item">
				<div class="d-flex flex-column justify-content-between h-100" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>"><img
										class="preview_picture"
										border="0"
										src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
										width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
										height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
										alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
										title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
										style="float:left"
										/></a>
							<?else:?>
								<img
									class="preview_picture"
									border="0"
									src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
									width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
									height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
									alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
									title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
									style="float:left"
									/>
							<?endif;?>
						<?endif?>
						<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
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
						<?endif?>
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
						<div class="align-center margin-top-42 padding-bottom-16">
							<a class="more" href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="Читать подробнее...">Читать подробнее...</a>
						</div>
				</div>
			</li>
		<?endforeach;?>
	</ul>
	<div class="cm-carousel-pagination"></div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>

<script>
	$(document).ready(function(){
		$(".services-list").owlCarousel({
			loop:true,
			margin:10,
			autoHeight:true,
			// responsiveClass:true,
			responsive:{
				0:{
					items:1,
					nav:false
				},
				600:{
					items:2,
					nav:false
				},
				1000:{
					items:3,
					nav:false,
					loop:false
				}
			}
		});
	});
</script>