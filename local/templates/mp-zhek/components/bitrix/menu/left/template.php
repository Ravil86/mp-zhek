<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<ul class="vertical-menu">

<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>

	<?if($arItem["SELECTED"]):?>
		<li class="selected"><a href="<?=$arItem["LINK"]?>" class="selected"<?=$arItem['PARAMS']['TARGET']?' target="'.$arItem['PARAMS']['TARGET'].'"':''?>>
			<?=$arItem["TEXT"]?>
			<span class="template-arrow-horizontal-3"></span>
		</a></li>
	<?else:?>
		<li><a href="<?=$arItem["LINK"]?>"<?=$arItem['PARAMS']['TARGET']?' target="'.$arItem['PARAMS']['TARGET'].'"':''?>>
			<?=$arItem["TEXT"]?>
			<span class="template-arrow-horizontal-3"></span>
		</a></li>
	<?endif?>
	
<?endforeach?>

</ul>
<?endif?>