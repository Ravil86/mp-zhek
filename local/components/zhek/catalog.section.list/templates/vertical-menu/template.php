<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
$emptyImagePath = $this->getFolder() . '/images/tile-empty.png';

// dump($arResult['SECTION']['DESCRIPTION']);
?>
<? if ($arResult['SECTIONS_COUNT'] > 0): ?>
	<div class="col-lg-4 col-md-5">
		<ul class="vertical-menu nav-pills!" id="pills-tab" role="tablist">
			<?php
			$sectionEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_EDIT');
			$sectionDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_DELETE');
			$sectionDeleteParams = [
				'CONFIRM' => Loc::getMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'),
			];
			?>
			<? //dump($arResult['SECTION']['UF_TABNAME']);
			?>
			<? if ($arResult['SECTION']['DESCRIPTION'] || $arResult['SECTION']['ELEMENTS']): ?>
				<li class="sections-list-menu-item nav-item">
					<a class="nav-link active" id="home-tab"
						data-bs-toggle="pill"
						data-bs-target="#home"
						role="tab"
						href="#home"
						aria-controls="home"
						aria-selected="false">
						<?= $arResult['SECTION']['UF_TABNAME'] ?: 'Информация' ?>
						<span class="template-arrow-horizontal-3"></span>
					</a>
					<?/*!--button class="nav-link sections-list-menu-item-link active" id="pills-home-tab"
						data-bs-toggle="pill"
						data-bs-target="#pills-home"
						type="button"
						role="tab"
						aria-controls="pills-home"
						aria-selected="true">Общая информация
						<span class="template-arrow-horizontal-3"></span>
					</button-->
				<!-- <a href="#" class="sections-list-menu-item-link">
					Общая информация
				</a> --*/ ?>
				</li>
			<? endif; ?>
			<?
			foreach ($arResult['SECTIONS'] as &$section) {
				$this->addEditAction($section['ID'], $section['EDIT_LINK'], $sectionEdit);
				$this->addDeleteAction($section['ID'], $section['DELETE_LINK'], $sectionDelete, $sectionDeleteParams);

				$code = $section['ID'] . '-' . (strpos($section['CODE'], '-') ? substr($section['CODE'], 0, strpos($section['CODE'], '-')) : $section['CODE']);
			?>
				<li class="sections-list-menu-item nav-item" id="<?= $this->getEditAreaId($section['ID']) ?>">
					<? if (!$section['UF_LINK']): ?>
						<?/*<button class="nav-link w-100 text-start" id="pills-<?=$code?>-tab"
						data-bs-toggle="pill"
						data-bs-target="#pills-<?=$code?>"
						type="button" role="tab"
						aria-controls="pills-<?=$code?>"
						aria-selected="false">
						<?=$section['NAME']?>
						<span class="template-arrow-horizontal-3"></span>
					</button>*/ ?>
						<a class="nav-link" id="<?= $code ?>-tab"
							data-bs-toggle="pill"
							data-bs-target="#<?= $code ?>"
							role="tab"
							href="#<?= $code ?>"
							aria-controls="<?= $code ?>"
							aria-selected="false">
							<?= $section['NAME'] ?>
							<span class="template-arrow-horizontal-3"></span>
						</a>
					<? else: ?>
						<a class="nav-link" id="<?= $code ?>"
							href="<?= $section['UF_LINK'] ?>">
							<?= $section['NAME'] ?>
							<span class="template-arrow-horizontal-3"></span>
						</a>
					<? endif ?>
					<?/*<a href="<?=$section['SECTION_PAGE_URL']?>" class="sections-list-menu-item-link">
						<span class="sections-list-menu-item-text"><?=$section['NAME'] ." <i>".$section['ELEMENT_CNT']. "</i>";?></span>
						<span class="template-arrow-horizontal-3"></span>
					</a>*/ ?>
				</li>
			<?php
			}
			unset($section);
			?>
		</ul>

	</div>
<? elseif ($arResult['SECTION']['DETAIL_PICTURE']): ?>
	<div class="col-lg-9 col-md-7">
		<h3 class="h5 catalog-section-title lh-sm pb-2 pt-0 ps-0 my-0"><?= $arResult['SECTION']['UF_TABNAME'] ?? 'Информация' ?></h3>
		<? if ($arResult['SECTION']['DETAIL_PICTURE']): ?>
			<? $detailPicture = CFile::GetFileArray($arResult['SECTION']['DETAIL_PICTURE']); ?>
			<? if ($detailPicture['WIDTH'] > 1150): ?>
				<a title="<?= $arResult['SECTION']["NAME"] ?> <?= $arResult['SECTION']['UF_TABNAME'] ?>" href="<?= $detailPicture['SRC'] ?>" data-fancybox>
				<? endif; ?>
				<img class="img-fluid" src="<?= $detailPicture['SRC']; ?>" alt="<?= $arResult['SECTION']["NAME"] ?> <?= $arResult['SECTION']['UF_TABNAME'] ?>">
				<? if ($detailPicture['WIDTH'] > 1150): ?></a><? endif; ?>
		<? endif; ?>
	</div>
<? else: ?>
	<?/*<div class="col-lg-2 col-md-3"></div>*/ ?>
<? endif ?>

<script>
	$(document).ready(function() {
		// deep linking - load tab on refresh
		let url = location.href.replace(/\/$/, '/');
		// let url = location.href.replace(/\/$/, ''); //old без /


		if (location.hash) {
			const hash = url.split('#');
			const currentTab = document.querySelector('#pills-tab a[href="#' + hash[1] + '"]');
			const curTab = new bootstrap.Tab(currentTab);
			curTab.show();

			url = location.href.replace(/\#/, '#');
			// url = location.href.replace(/\/#/, '#');

			history.replaceState(null, null, url);
			setTimeout(() => {
				window.scrollTop = 0;
			}, 400);
		}
		// change url based on selected tab
		const selectableTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="pill"]'));

		selectableTabList.forEach((selectableTab) => {

			const selTab = new bootstrap.Tab(selectableTab);
			selectableTab.addEventListener('click', function() {

				var newUrl;
				const hash = selectableTab.getAttribute('href');

				if (hash == '#home') {
					newUrl = url.split('#')[0];
				} else {

					newUrl = url.split('#')[0] + hash;
				}
				history.replaceState(null, null, newUrl);

			});

		});
	});
</script>