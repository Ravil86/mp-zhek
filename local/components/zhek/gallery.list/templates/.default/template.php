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
<!--div class="mentor-list row"-->
<div class="gallery position-relative" data-aos="fade-up" data-aos-delay="20000">
    <?/*<a id="prev" class="prev"><i class="lni lni-chevron-left"></i></a>*/?>
	<div class="gallery-carousel d-flex align-items-start justify-content-between carousel">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            //$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $imageSrc = $arItem['PATH'];
                $resizeImage = CFile::ResizeImageGet($arItem['SOURCE_ID'], array('width'=>300, 'height'=>230), BX_RESIZE_IMAGE_EXACT, true);

                //$image = $arItem['DETAIL_PICTURE']?$arItem['DETAIL_PICTURE']:$arItem["PREVIEW_PICTURE"];
                //$imageSrc = $image['SRC'];
                //$bigImage = $arItem['DETAIL_PICTURE']?$arItem['DETAIL_PICTURE']['SRC']:'';
                //$resizeImage = CFile::ResizeImageGet($image, array('width'=>300, 'height'=>250), BX_RESIZE_IMAGE_EXACT, true);
                ?>
                <a href="<?=$imageSrc //$bigImage?>" data-fancybox="gallery" class="gallery-item carousel__slide text-center col-12 col-md-6 col-lg-4 col-xxl-3 w-100! text-center!">
                    <div class="logo-img rounded-4!">
                            <?if($imageSrc):?><img src="<?=$resizeImage['src'] //$imageSrc?>" class="img-fluid rounded-4" alt=""><?endif;?>
                    </div>
                </a>

        <?endforeach;?>
    </div>
    <?/*<a id="next" class="next"><i class="lni lni-chevron-right"></i></a>*/?>
</div>
<!--/div-->
