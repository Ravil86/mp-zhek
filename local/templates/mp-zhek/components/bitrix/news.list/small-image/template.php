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
<ul class="blog news row gy-3 clearfix">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="col-12 col-md-6 mt-0 mb-4 d-flex flex-column justify-content-between" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="row">
					<div class="col-4">
						<?if($arParams["DISPLAY_PICTURE"]!="N"):?>
							<?//dump($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]);?>
								<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]))
									$imgLink = $arItem["DETAIL_PAGE_URL"];
								else
									$imgLink = $arItem["DETAIL_PICTURE"]['SRC'].'" data-fancybox="';
								?>
									<a class="" href="<?=$imgLink?>" title="<?=$arItem["NAME"]?>">
								<?//endif;?>
									<?/*
									<div class="post-date">
										<div class="month"><?=FormatDate("M", MakeTimeStamp($arItem['ACTIVE_FROM']))?></div>
										<h4><?=ConvertDateTime($arItem['ACTIVE_FROM'], "DD", "ru")?></h4>
									</div>
									*/?>
									<?if(is_array($arItem["PREVIEW_PICTURE"])):?>
										<?$preview = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>480, 'height'=>320), BX_RESIZE_IMAGE_EXACT, true);?>
										<img
											class="post-image img-fluid"
											src="<?=$preview["src"]?>"
											width="<?=$preview["width"]?>"
											height="<?=$preview["height"]?>"
											alt="<?=$arItem["NAME"]?>"
											/>
									<?else:?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/no-photo.jpg" alt="">
									<?endif;?>
								<?//if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
									</a>
								<?//endif;?>
						<?endif?>
				</div>
				<div class="col-8">
						<?//dump($arItem);?>

						<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<h3 class="h5 post-title mb-0"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h3>
							<?else:?>
								<h3 class="h5 post-title mb-0"><?=$arItem["NAME"]?></h3>
							<?endif;?>
						<?endif;?>

						<div class="post-content-details-container pt-1 clearfix">

							<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
								<div class="d-flex">
									<ul class="post-content-details">
										<li><?=$arItem["DISPLAY_ACTIVE_FROM"]?></li>
										<?/*<li>in <a href="?page=category&amp;cat=house_cleaning" title="House Cleaning">House Cleaning</a></li> -->
										<li>by <a href="?page=team_paige_morgan" title="Paige Morgan">Paige Morgan</a></li>*/?>
									</ul>
								</div>
								<!-- <span class="news-date-time"></span> -->
							<?endif?>
						
						<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<p class="mt-0"><?=TruncateText($arItem["PREVIEW_TEXT"], 250);?> 
								<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="Read more">Читать далее</a>
								<?endif;?>
							</p>
						<?endif;?>

						</div>

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
					</div>
				</div>
			</li>
		<?endforeach;?>
</ul>
	
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
<script>
</script>