<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
	<ul class="d-flex flex-column">
		<?
		foreach ($arResult as $arItem):
			if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
				continue;
		?>
			<li class="ui-btn-container my-1"><a class="d-flex ui-btn ui-btn-default text-wrap lh-1<?= $arItem["SELECTED"] ? ' ui-btn-active' : '' ?>" href="<?= $arItem["LINK"] ?>" <?= $arItem['PARAMS']['TARGET'] ? ' target="' . $arItem['PARAMS']['TARGET'] . '"' : '' ?>>
					<?= $arItem["TEXT"] ?>
				</a></li>
		<? endforeach ?>
	</ul>
<? endif ?>