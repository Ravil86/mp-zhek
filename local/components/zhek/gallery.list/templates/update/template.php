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
<div class="gallery position-relative" data-aos="fade-up" data-aos-delay="20000">
    <?/*<a id="prev" class="prev"><i class="lni lni-chevron-left"></i></a>*/ ?>
    <div id="galleryCarousel" class="gallery-carousel d-flex align-items-start justify-content-between !carousel f-carousel">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $imageSrc = $arItem['PATH'];
            $resizeImage = CFile::ResizeImageGet($arItem['SOURCE_ID'], array('width' => 320, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);

            ?>
            <a href="<?= $imageSrc //$bigImage
                        ?>" data-fancybox="gallery" class="gallery-item f-carousel__slide col-12 col-md-6 col-lg-4 col-xxl-3 px-2">
                <div class="logo-img rounded-4!">
                    <? if ($imageSrc): ?><img src="<?= $resizeImage['src'] ?>" class="img-fluid rounded-4" alt=""><? endif; ?>
                </div>
            </a>
        <? endforeach; ?>
    </div>
    <?/*<a id="next" class="next"><i class="lni lni-chevron-right"></i></a>*/ ?>
</div>