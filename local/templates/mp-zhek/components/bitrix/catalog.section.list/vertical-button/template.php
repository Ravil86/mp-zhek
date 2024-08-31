<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
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
$emptyImagePath = $this->getFolder().'/images/tile-empty.png';

// dump($arResult['SECTION']['DESCRIPTION']);

// dump($arResult['SECTION']['ELEMENTS']);

// if ($arResult['SECTIONS_COUNT'] > 0)
// {
	?>
		<ul class="vertical-menu nav-pills!" id="pills-tab" role="tablist">
			<?php
			$sectionEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_EDIT');
			$sectionDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_DELETE');
			$sectionDeleteParams = [
				'CONFIRM' => Loc::getMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'),
			];
			?>
			<li class="sections-list-menu-item nav-item first-item">
				<button class="nav-link sections-list-menu-item-link active" id="pills-home-tab" 
						data-bs-toggle="pill" 
						data-bs-target="#pills-home" 
						type="button" 
						role="tab" 
						aria-controls="pills-home" 
						aria-selected="true">Общая информация
						<span class="template-arrow-horizontal-3"></span>
					</button>
				<!-- <a href="#" class="sections-list-menu-item-link">
					Общая информация
				</a> -->
			</li>
			<?
			foreach ($arResult['SECTIONS'] as &$section)
			{
				$this->addEditAction($section['ID'], $section['EDIT_LINK'], $sectionEdit);
				$this->addDeleteAction($section['ID'], $section['DELETE_LINK'], $sectionDelete, $sectionDeleteParams);

				$code = $section['ID'].'-'.(strpos($section['CODE'], '-') ? substr($section['CODE'], 0, strpos($section['CODE'], '-')) : $section['CODE']);
				?>
				<li class="sections-list-menu-item nav-item" id="<?=$this->getEditAreaId($section['ID'])?>">
				<button class="nav-link w-100 text-start" id="pills-<?=$code?>-tab" 
						data-bs-toggle="pill" 
						data-bs-target="#pills-<?=$code?>" 
						type="button" role="tab" 
						aria-controls="pills-<?=$code?>" 
						aria-selected="false">
						<?=$section['NAME']?>
						<span class="template-arrow-horizontal-3"></span>
					</button>
					<?/*<a href="<?=$section['SECTION_PAGE_URL']?>" class="sections-list-menu-item-link">
						<span class="sections-list-menu-item-text"><?=$section['NAME'] ." <i>".$section['ELEMENT_CNT']. "</i>";?></span>
						<span class="template-arrow-horizontal-3"></span>
					</a>*/?>
				</li>
				<?php
			}
			unset($section);
			?>
		</ul>
	<?php
// }
