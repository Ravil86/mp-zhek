<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
	<div class="tabs-menu mb-3">
		<ul class="nav nav-tabs">
			<? foreach ($arResult as $arItem): ?>
				<? if ($arItem["PERMISSION"] > "D"): ?>
					<li class="nav-item"><a class="nav-link<?= $arItem['SELECTED'] ? ' active' : '' ?>" href="<?= $arItem["LINK"] ?>">
							<?= $arItem["TEXT"] ?>
						</a></li>
				<? endif ?>

			<? endforeach ?>

		</ul>
	</div>
<? endif ?>