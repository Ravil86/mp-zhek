<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <?
    $previousLevel = 0;
    foreach($arResult as $arItem):?>

        <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
            <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
        <?endif?>

        <?if ($arItem["IS_PARENT"]):?>

            <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                <?/*if ($arItem["PARAMS"]["SEPARATOR"]=="Y"):?>
                    <li class="nav-item<?if ($arItem["SELECTED"]):?> active selected<?endif?>">
                        <a class="nav-link<?if ($arItem["SELECTED"]):?> active selected<?endif?>" href="#"><?=$arItem["TEXT"]?></a>
                        <ul class="rd-menu rd-navbar-dropdown">
                <?else:*/?>
                    <li class="nav-item dropdown<?if ($arItem["SELECTED"]):?> active selected<?endif?>">
                        <a class="nav-link dropdown-toggle<?if ($arItem["SELECTED"]):?> active selected<?endif?>" 
                            href="<?//=$arItem["LINK"]?>"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false"><?=$arItem["TEXT"]?></a>
                        <ul class="dropdown-menu">
                <?//endif?>
            <?else:?>
                <li class="dropdown<?if ($arItem["SELECTED"]):?> active selected<?endif?>">
                        <a class="rd-dropdown-link" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                    <ul>
            <?endif?>

        <?else:?>
            <?//Если нет дочерних меню и есть доступ?>
            <?if ($arItem["PERMISSION"] > "D"):?>

                <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                    <li class="nav-item <?if ($arItem["SELECTED"]):?> active selected<?endif?>">
                        <a class="nav-link<?if ($arItem["SELECTED"]):?> active selected<?endif?>" href="<?=$arItem["LINK"]?>" <?=$arItem["PARAMS"]['TARGET']?' target="'.$arItem["PARAMS"]['TARGET'].'"':''?>>
                        <?=$arItem["TEXT"]?></a>
                    </li>
                <?else:?>
                    <li class="dropdown<?if ($arItem["SELECTED"]):?> active selected<?endif?>">
                        <a class="dropdown-item" href="<?=$arItem["LINK"]?>"<?=$arItem["PARAMS"]['TARGET']?' target="'.$arItem["PARAMS"]['TARGET'].'"':''?>>
                        <?=$arItem["TEXT"]?>
                    </a></li>
                <?endif?>
                
            <?else:?>
            
                <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                    <li class="nav-item<?if ($arItem["SELECTED"]):?> active selected<?endif?>"><a href="nav-link" class="<?if ($arItem["SELECTED"]):?>active<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
                <?else:?>
                    <li class="nav-item dropdown<?if ($arItem["SELECTED"]):?> active selected<?endif?>">
                        <a href="nav-link" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a>
                    </li>
                <?endif?>

            <?endif?>

        <?endif?>

        <?$previousLevel = $arItem["DEPTH_LEVEL"];?>

    <?endforeach?>

    <?if ($previousLevel > 1)://close last item tags?>
        <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
    <?endif?>

    </ul>
<?endif?>
