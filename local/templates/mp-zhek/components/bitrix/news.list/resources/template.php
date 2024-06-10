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
<div class="our-clients-list-container margin-top-40 type-list!">
	<ul class="owl-carousel owl-theme our-clients-list type-list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="d-flex flex-column justify-content-between h-100 service-item vertical-align" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<div class="our-clients-item-container">
										<div class="vertical-align-cell">
											<a target="_blank" href="<?=$arItem["PROPERTIES"]['LINK']["VALUE"]?>" title="<?=$arItem["NAME"]?>">
												<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
											</a>
										</div>
									</div>
								</li>	
		<?endforeach;?>
	</ul>
	<!-- <div class="cm-carousel-pagination"></div> -->
</div>
<script>
	$(document).ready(function(){
		$(".our-clients-list").owlCarousel({
			loop:true,
			margin:10,
			autoHeight:true,
			autoplay: false,
			// responsiveClass:true,
			responsive:{
				0:{
					items:2,
					nav:false
				},
				600:{
					items:2,
					nav:false
				},
				1000:{
					items:3,
					nav:false,
					loop:true
				}
			}
		});
	});
</script>