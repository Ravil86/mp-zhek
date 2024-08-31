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
<ul class="blog small d-flex flex-column margin-top-30! clearfix">
		<?foreach($arResult["ITEMS"] as $i => $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			// dump($i);

			// if($i<2)
			// 	continue;
			?>
			<li class="<?=($i<2)?'d-md-none':''?><?=($i==2)?'pt-lg-0 mt-lg-0 border-top-0':''?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="row">
						<div class="col-4">
							<?if($arParams["DISPLAY_PICTURE"]!="N"):?>
									<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
										<a class="post-image" href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>">
									<?endif;?>
									<?if(is_array($arItem["PREVIEW_PICTURE"])):?>
										<?$preview = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>480, 'height'=>320), BX_RESIZE_IMAGE_EXACT, true);?>
										<img
											class="post-image"
											src="<?=$preview["src"]?>"
											width="<?=$preview["width"]?>"
											height="<?=$preview["height"]?>"
											alt="<?=$arItem["NAME"]?>"
											title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
											/>
									<?else:?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/no-photo.jpg" alt="">
									<?endif;?>
									<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
										</a>
									<?endif;?>
							<?endif?>
						</div>
						<div class="col-8 text-start">
							<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
								<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
									<h4 class="post-title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h4>
								<?else:?>
									<h4 class="post-title"><?=$arItem["NAME"]?></h4>
								<?endif;?>
							<?endif;?>

							<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
								<ul class="post-details mt-0">
									<li class="date"><span class="template-clock pe-1"></span><?=$arItem["DISPLAY_ACTIVE_FROM"]?></li>
								</ul>
							<?endif?>
						</div>
				</div>
						<?/*if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<p><?=TruncateText($arItem["PREVIEW_TEXT"], 100);?> <a href="?page=post" title="Read more">Читать далее</a></p>
						<?endif;*/?>
						
			</li>
		<?endforeach;?>
</ul>
	
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>