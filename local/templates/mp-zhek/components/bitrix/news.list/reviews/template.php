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
<div class="owl-theme">
	<div id="testimonials-nav" class="owl-nav"></div>
</div>
	<ul class="testimonials-list owl-carousel testimonials-carousel!">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>

			<li class="col-12">
				<p class="template-quote" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?=$arItem["NAME"]?></p>
					<div class="author-details-box">
						<div class="author"><?=$arItem["PREVIEW_TEXT"]//TruncateText($arItem["PREVIEW_TEXT"], 100);?></div>
						<!--<div class="author-details">CLEANING TECHNICAN</div>-->
					</div>
			</li>
		<?endforeach;?>
	</ul>

<script>
	$(document).ready(function(){
		$(".testimonials-list").owlCarousel({
			loop:true,
			nav: true,
			margin:10,
			autoHeight:true,
			items:1,
			dots: false,
			navContainer: '#testimonials-nav'
			// responsiveClass:true,
		});
	});
</script>