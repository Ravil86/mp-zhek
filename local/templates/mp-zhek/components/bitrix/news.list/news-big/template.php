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
<ul class="blog big news row clearfix">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="col-12 col-md-6 mt-0 d-flex flex-column justify-content-between" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?if($arParams["DISPLAY_PICTURE"]!="N"):?>
								<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
									<a class="post-image" href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>">
								<?endif;?>
									<?/*
									<div class="post-date">
										<div class="month"><?=FormatDate("M", MakeTimeStamp($arItem['ACTIVE_FROM']))?></div>
										<h4><?=ConvertDateTime($arItem['ACTIVE_FROM'], "DD", "ru")?></h4>
									</div>
									*/?>
									<?if(is_array($arItem["PREVIEW_PICTURE"])):?>
										<?$preview = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>480, 'height'=>320), BX_RESIZE_IMAGE_EXACT, true);?>
										<img
											class="post-image"
											src="<?=$preview["src"]?>"
											width="<?=$preview["width"]?>"
											height="<?=$preview["height"]?>"
											alt="<?=$arItem["NAME"]?>"
											/>
									<?else:?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/no-photo.jpg" alt="">
									<?endif;?>
								<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
									</a>
								<?endif;?>
						<?endif?>
						<?//dump($arItem);?>

						<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<h3 class="post-title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h3>
							<?else:?>
								<h3 class="post-title"><?=$arItem["NAME"]?></h3>
							<?endif;?>
						<?endif;?>

						<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
							<div class="post-content-details-container clearfix">
								<ul class="post-content-details">
									<li class="template-clock"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></li>
									<?/*<li>in <a href="?page=category&amp;cat=house_cleaning" title="House Cleaning">House Cleaning</a></li> -->
									<li>by <a href="?page=team_paige_morgan" title="Paige Morgan">Paige Morgan</a></li>*/?>
								</ul>
							</div>
							<!-- <span class="news-date-time"></span> -->
						<?endif?>
						
						<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<p><?=TruncateText($arItem["PREVIEW_TEXT"], 100);?> <a href="?page=post" title="Read more">Читать далее</a></p>
						<?endif;?>

						<?/*
						<div class="post-content-details-container clearfix">
								<ul class="post-content-details">
									<li class="template-display"><a href="#<?//?page=post?>">250</a></li>
									<li class="template-comment"><a href="#<?//?page=post#comments-list?>" title="3 comments">3</a></li>
								</ul>
						</div>
						*/?>
						

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
			</li>
		<?endforeach;?>
</ul>
	
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
<script>
</script>