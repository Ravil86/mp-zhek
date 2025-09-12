<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
<div class="revolution-slider-container">
	<div class="revolution-slider" data-version="5.4.5" style="display: none;">
		<ul>
			<? foreach ($arResult["ITEMS"] as $arItem): ?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<? //dump($arItem['PROPERTIES']['LINK']['VALUE'])
				?>
				<li data-transition="fade" data-masterspeed="500" data-slotamount="1" data-delay="60000">
					<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arItem["NAME"] ?>" data-bgfit="cover">
					<div class="tp-caption container"
						id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
						data-frames='[{"delay":500,"speed":500,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
						data-x="center"
						data-y="<?= $arItem['PROPERTIES']['LINK']['VALUE'] ? "['100', '80', '60', '40']" : "['125', '100', '70', '50']" ?>">

						<div class="row! d-flex justify-content-center z-2">
							<div class="col-9 main-caption p-4">
								<h2 class="h1!">
									<? if ($arItem['PROPERTIES']['LINK']['VALUE']): ?><a href="<?= $arItem['PROPERTIES']['LINK']['VALUE'] ?>" title="<?= $arItem["NAME"] ?>"><? endif; ?>
										<?= $arItem["NAME"] ?>
										<? if ($arItem['PROPERTIES']['LINK']['VALUE']): ?></a><? endif; ?>
								</h2>

								<h4 class="lh-base margin-top-<?= $arItem['PROPERTIES']['LINK']['VALUE'] ? "30" : "65" ?>"><?= $arItem['PROPERTIES']['DESC']['VALUE'] ? $arItem['PROPERTIES']['DESC']['~VALUE']['TEXT'] : ''; ?></h4>
							</div>
						</div>
						<?/* <div class="tp-caption"
						data-frames='[{"delay":1500,"speed":1500,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
						>
						<h4><? //=$arItem['PROPERTIES']['DESC']['VALUE']?$arItem['PROPERTIES']['DESC']['~VALUE']['TEXT']:'';
							?></h4>
					</div>*/ ?>
					</div>
					<? ?>
					<div class="tp-caption"
						data-frames='[{"delay":1000,"speed":1000,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
						data-x="center"
						data-y="['386', '418', '300', '340']">
						<div class="align-center">
							<? if ($arItem['PROPERTIES']['LINK']['VALUE']): ?><a class="more" href="<?= $arItem['PROPERTIES']['LINK']['VALUE'] ?>"><?= $arItem['PROPERTIES']['LINK']['DESCRIPTION'] ?: 'Подробнее...' ?></a><? endif; ?>
						</div>
					</div>
					<? ?>
				</li>
				<?/*
			<p class="news-item" >
				<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
					<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
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
						<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
					<?else:?>
						<b><?echo $arItem["NAME"]?></b><br />
					<?endif;?>
				<?endif;?>
				<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
					<?echo $arItem["PREVIEW_TEXT"];?>
				<?endif;?>
				<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
					<div style="clear:both"></div>
				<?endif?>
				<?foreach($arItem["FIELDS"] as $code=>$value):?>
					<small>
					<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
					</small><br />
				<?endforeach;?>
				<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
					<small>
					<?=$arProperty["NAME"]?>:&nbsp;
					<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
						<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
					<?else:?>
						<?=$arProperty["DISPLAY_VALUE"];?>
					<?endif?>
					</small><br />
				<?endforeach;?>
			</p>
			*/ ?>
			<? endforeach; ?>
		</ul>
	</div>
</div>