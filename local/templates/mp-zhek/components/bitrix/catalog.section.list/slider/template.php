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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
    'TILE' => array(
        'TITLE' => 'service-list-item-title',
        'LIST' =>  'services-list owl-carousel owl-theme gx-3 justify-content-center mb-4',
        'EMPTY_IMG' => $this->GetFolder() . '/images/tile-empty.png'
    )
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>
<div class="mb-4">
    <? if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID']) {
        $this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
        $this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

    ?>
        <h2 class="mb-3" id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>">
            <? echo (
                isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
                ? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
                : $arResult['SECTION']['NAME']
            );
            ?>
        </h2>
    <?
    }

    if (0 < $arResult["SECTIONS_COUNT"]) {
    ?>
        <div class="<? echo $arCurView['LIST']; ?>">
            <?
            foreach ($arResult['SECTIONS'] as &$arSection) {
                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

                if (false === $arSection['PICTURE'])
                    $arSection['PICTURE'] = array(
                        'SRC' => $arCurView['EMPTY_IMG'],
                        'ALT' => (
                            '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                            ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                            : $arSection["NAME"]
                        ),
                        'TITLE' => (
                            '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                            ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                            : $arSection["NAME"]
                        )
                    );
            ?>
                <div id="<? echo $this->GetEditAreaId($arSection['ID']); ?>" class="service-list-item">
                    <div class="service-list-tile-img-container">
                        <a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" class="service-list-item-img"
                            style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
                            title="<? echo $arSection['PICTURE']['TITLE']; ?>"></a>
                    </div>
                    <? if ('Y' != $arParams['HIDE_SECTION_NAME']) {
                    ?>
                        <div class="service-list-item-inner">
                            <h3 class="service-list-item-title pt-3 fw-medium">
                                <a class="service-list-item-link" href="<? echo $arSection['SECTION_PAGE_URL']; ?>">
                                    <? echo $arSection['NAME']; ?>
                                </a>
                                <? if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null) {
                                ?>
                                    <span class="service-list-item-counter">(
                                        <? echo $arSection['ELEMENT_CNT']; ?>)
                                    </span>
                                <?
                                }
                                ?>
                            </h3>
                        </div>
                    <?
                    }
                    ?>
                </div>
            <?
            }
            unset($arSection);
            ?>
        </div>
    <?
    }
    ?>
</div>

<script>
    $(document).ready(function() {
        $(".services-list").owlCarousel({
            loop: false,
            margin: 10,
            autoHeight: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            nav: false,
            // responsiveClass:true,
            responsive: {
                0: {
                    items: 2,
                    // nav: false
                },
                600: {
                    items: 3,
                    // nav: false
                },
                1000: {
                    items: 5,
                    // nav: false,
                    loop: true
                }
            }
        });
    });
</script>