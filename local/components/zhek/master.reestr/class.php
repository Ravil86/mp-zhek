<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Uriit\Contest\Helper;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock\Component\Tools,
	Bitrix\Iblock,
	Bitrix\Main\Application,
	Bitrix\Main\Web\Uri;
use PhpParser\Node\Stmt\Break_;

class MasterReestr extends CBitrixComponent
{

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	public function executeComponent()
	{

		if ($this->arParams["SEF_MODE"] == "Y") {
			$componentPage = $this->sefMode();
		}
		// if ($this->arParams["SEF_MODE"] != "Y") {
		// 	$componentPage = $this->noSefMode();
		// }

		if (!$componentPage) {
			Tools::process404(
				$this->arParams["MESSAGE_404"],
				($this->arParams["SET_STATUS_404"] === "Y"),
				($this->arParams["SET_STATUS_404"] === "Y"),
				($this->arParams["SHOW_404"] === "Y"),
				$this->arParams["FILE_404"]
			);
		}

		$this->run();
		$this->prepareComponentResult();
		$this->includeComponentTemplate($componentPage);
	}


	private function run()
	{

		// function testEmpty($array)
		// {
		// 	foreach ($array as $element) {
		// 		if ($element == '')
		// 			return true;
		// 	}
		// 	return false;
		// }

		$arParams = $this->arParams;
		$this->arResult['PAGE_SIZE'] = 20;

		$this->arResult['ACCESS'] = $this->checkAccess();
		$arItems = [];

		$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
		$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

		$serviceList = LKClass::getService();
		$this->arResult['SERVICE_LIST'] = $serviceList;

		$contracts = LKClass::getContracts();
		$this->arResult['CONTRACTS'] = $contracts;

		foreach ($contracts as $contr) {
			// dump($contr);
			$orgContracts[$contr['COMPANY']][] = [
				'NUMBER' => $contr['FULL_NUMBER'],
				'STATUS' => $contr['STATUS'],
				'ACTIVE' => $contr['UF_STATUS'] == 14 ?: false,
				// 'DATE' => $contr['DATE'],
			];
		}

		// $arItems = LKClass::getCompany();
		// if (is_array($arItems))
		// 	$this->arResult['GRID']['COUNT'] = count($arItems);

		// if ($myCompany)
		$getObjects = LKClass::getObjects();
		foreach ($getObjects as $key => $value) {
			$arObjects[$value['ORG']][] = $value;
		}

			$grid_options = new CGridOptions($this->arResult["GRID_ID"]);
			$nav_params = $grid_options->GetNavParams(array("nPageSize" => $this->arResult['PAGE_SIZE']));
			$nav = new Bitrix\Main\UI\PageNavigation($this->arResult["GRID_ID"]);
			$nav->allowAllRecords(true)
				->setPageSize($nav_params['nPageSize'])
				->initFromUri();

			if ($nav->allRecordsShown())
				$nav_params = false;
			else
				$nav_params['iNumPage'] = $nav->getCurrentPage();

			$rsEnum = HLWrap::getEnumProp('UF_TYPE');
			// while ($arEnum = $rsEnum->Fetch()) {
			// 	//dump($arEnum);
			// }

			//какую сортировку сохранил пользователь (передаем то, что по умолчанию)
			$arSort = $grid_options->GetSorting(array("sort" => array("timestamp_x" => "desc"), "vars" => array("by" => "by", "order" => "order")));
			$this->arResult['GRID']['COLUMNS'] = [
			// ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false, 'width' => 50, 'resizeable' => false, 'rowspan' => true],
			['id' => 'UF_NAME', 'name' => 'Организация', /*'sort' => 'NAME', */ 'default' => true, 'rowspan' => true /*'sticked' => true, 'resizeable' => true*/],
			// ['id' => 'UF_ADDRESS', 'name' => 'Адрес организации', /*'sort' => 'ADDRESS', */ 'default' => false],
			// ['id' => 'UF_INN', 'name' => 'ИНН',/* 'sort' => 'TIMESTAMP_X',*/ 'default' => false, 'rowspan' => true],
			['id' => 'DOGOVOR', 'name' => 'Текущий контракт', 'default' => true, 'rowspan' => true],
			['id' => 'OBJECT', 'name' => 'Объект', 'default' => true],
			['id' => 'COUNTER', 'name' => 'ПУ', 'default' => true],
			['id' => 'METER_LAST', 'name' => 'Текущие', 'default' => true, 'colspan' => 4, 'text' => 'Показания', 'color' => '#ddd'],
			['id' => 'METER_ALL', 'name' => 'Предыдущие', 'default' => true, 'colspan' => 0, 'color' => '#ddd'],
			['id' => 'METER_RAZNOST', 'name' => 'Разность', 'default' => true, 'colspan' => 0],
			['id' => 'EDIT', 'name' => 'кор.', 'default' => true, 'colspan' => 0, 'center' => true],
			// ['id' => 'UF_TYPE', 'name' => 'Тип организации', 'default' => false, 'rowspan' => true /*"editable" => ['TYPE' => 'DROPDOWN', 'items' => $userItems]*/],
		];

		// $filterOption = new Bitrix\Main\UI\Filter\Options("filter_" . $this->arResult["GRID_ID"]);
		// $filter = $filterOption->GetFilter();

		// $navParams = [
		// 	'offset' => $nav->getOffset(),
		// 	'limit' => $nav->getLimit(),
		// ];
		$arResult['COUNTER_SHOW'] = $arParams['CLEAR_DATA'] == 'Y' ? true : false;
		// $this->$arResult['COUNTER_SHOW'] = $arResult['COUNTER_SHOW'];

		if ($arParams['ALL_ORG'] != 'Y')
			$filter['UF_ACTIVE'] = 1;

		$itemsCompany = LKClass::getCompany(null, $filter);
		// $itemsCompany = LKClass::getCompany(null, $filter, $navParams);

		$orgObjects = null;
		foreach ($itemsCompany as &$item) {

			if ($orgObjects = $arObjects[$item['ID']])
			foreach ($orgObjects as $object) {
				$item['OBJECTS'][$object['ID']] = $object;		//заполняем обьектами организации для сортировки
			}

			if ($getDogovOrg = $orgContracts[$item['ID']])	//заполняем контрактами для сортировки
				$item['CONTRACT'] = $getDogovOrg[0];
			// $item['CONTRACTS'] = $getDogovOrg;
		}

		if ($arParams['ALL_ORG'] != 'Y') {
			foreach ($itemsCompany as $key => $org) {
				// gg($org['CONTRACT']);
				if (!$org['CONTRACT']['ACTIVE'])
					unset($itemsCompany[$key]);
			}
		}


		if (!function_exists('sortObjects')) {
			function sortObjects($a, $b)
			{
				if (isset($a['OBJECTS']) == isset($b['OBJECTS'])) {
					return 0;
				}
				return ($a['OBJECTS'] > $b['OBJECTS']) ? -1 : 1;

				// return ($a['OBJECTS'] < $b['OBJECTS']) ? -1 : 1;
			}
		}
		usort($itemsCompany, "sortObjects");

		if (!function_exists('sortContract')) {
		function sortContract($a, $b)
		{
				if (isset($a['CONTRACT']) == isset($b['CONTRACT'])) {
				return 0;
			}
				return ($a['CONTRACT'] > $b['CONTRACT']) ? -1 : 1;
		}
		}
		usort($itemsCompany, "sortContract");

		// $columns[0] = 'ID';
		$p = 1;
		foreach ($this->arResult['GRID']['COLUMNS'] as $key => $value) {
			$columns[$key] = $value['id'];
			// $columns[$p] = $value['id'];
			// $columns[$key]['column'] = $value['id'];

			$p++;
		}
		// array_push($columns, 'END');

		$i = 0;

		// foreach ($orgContracts as $orgID => &$item) {	// записи по контрактам
		foreach ($itemsCompany as $keyCompany => &$item) {		// записи по всем организации
			$countObjects = 0;

			$column = $item;


			$column['UF_NAME'] = '#' . $column['ID'] . ' ' . $column['UF_NAME'];
			// if ($itemsCompany[$orgID])
			// 	$column['UF_NAME'] = $itemsCompany[$orgID]['UF_NAME'];

			if ($item['CONTRACT']) {
				$dogovor = '<div class="text-' . $item['CONTRACT']['STATUS']['CODE'] . ' text-center"><small>' . $item['CONTRACT']['NUMBER'] . '</small></div>';
				$column["DOGOVOR"] = $dogovor;
			}

			if ($item['OBJECTS']) {

				$countObjects = count($item['OBJECTS']);
				$colRow['COUNTS'] = $countObjects;
				$colRow['ROWSPAN'] = $countObjects > 1 ?: false;

				$gRow = 0;

				$arService = LKClass::getService();

				foreach ($item['OBJECTS'] as $keyObject => &$object) {

					$servicesAll = [];
					$lastIconType = '';

					$iconType = '';

					$column['OBJECT'] = '#' . $object['ID'] . ' ' . $object['NAME'];

					$column['EDIT'] = '<a class="text-center" href="/master/counter/' . $object['ID'] . '" target="_blank"><i class="revicon-pencil-1"></i></a>';

					$counterObjects = LKClass::getCounters($object['ID']);

					$column['COUNTER'] = [];
					$countersObject = [];
					$arType = null;

					foreach ($counterObjects as $key => $value) {

						$servicesImage = [];
						if ($value['UF_TYPE']) {
							foreach ($value['UF_TYPE'] as $type) {

								$arType = $arService[$type];
								// gg($arType);
								$servicesImage[] = '<img class="" src="' . $arType['ICON'] . '" width="15"/>';
							}
							$iconType = implode('', $servicesImage);
							unset($arType);
						}

						$countersObject[$value['ID']] = '<div class="d-flex align-items-center "><small class="p-0 bg-0 me-1">#' . $value['ID'] . '</small>' . (!$arResult['COUNTER_SHOW'] ? '<span class="col-3 p-0 bg-0">' . $iconType . '</span>' : '') . '<small class="ms-1 p-0 bg-0">' . ($value['UF_NUMBER'] ?: $value['UF_NAME']) . '</small></div>';
					}
					$column['COUNTER'] = $countersObject;

					//последние показания
					$metersLast = LKClass::meters($object['ID'], 1);
					if ($metersLast) {
						foreach ($metersLast as $key => $meter) {
							// gg($meter);
							$lastObjMeters[$meter['OBJECT']][$meter['COUNTER']] = $meter['METER'];
						}
					}

					//все показания
					$metersAll = LKClass::meters($object['ID']);
					// dump($metersAll);
					if ($metersAll) {
						foreach ($metersAll as $key => $meter) {
							// gg($meter);
							$allObjMeters[$meter['OBJECT']][$meter['COUNTER']][] = $meter['METER'];
						}
					}

					$column['METER_LAST'] = [];
					$column['METER_ALL'] = [];
					$column['METER_RAZNOST'] = [];

					// $lastMeter = null;
					// $allMeters = null;

					foreach ($countersObject as $key => $value) {
						$curentAllMeter = '';

						$lastMeter = $lastObjMeters[$object['ID']][$key];

						$column['METER_LAST'][$key] =  $lastMeter ?: '';
						// $column['METER_LAST'][$key] =  $lastMeter ?: (!$arResult['COUNTER_SHOW'] ? '-' : '');
						// $column['METER_LAST'][$key] =  $lastMeter ?: (!$arResult['COUNTER_SHOW'] ? '<span class="alert alert-danger p-1">-</span>' : '');

						if (!$lastMeter) {
							$column['ALERT'][$key] = true;
						}

						$allMeters =  $allObjMeters[$object['ID']][$key];
						if ($allMeters)
							$curentAllMeter = current($allMeters);
						$column['METER_ALL'][$key] =  $curentAllMeter ?? '-';

						$column['METER_RAZNOST'][$key] = $lastMeter && $curentAllMeter ? ($lastMeter - $curentAllMeter) : '-';

						// $column['EDIT'][$key] = '<a href="/master/counter/' . $key . '" target="_blank">i</a><i class="bi bi-pencil"></i>';
					}

					//$curentLast = current(LKClass::meters($object['ID'], 1));
					//$curentCounterLast = $counterObjects[$curentLast['COUNTER']];
					/*if ($curentCounterLast['UF_TYPE']) {
						foreach ($curentCounterLast['UF_TYPE'] as $type) {
							$arType = $arService[$type];
							$servicesLast[] = '<img src="' . $arType['ICON'] . '" width="18"/>';
							// $servicesLast[] = $arType['LITERA'] . '<img class="ps-1" src="' . $arType['ICON'] . '" width="20"/>';
						}
						$lastIconType = implode('/', $servicesLast);
						unset($arType);
					}*/

					//$column['METER_LAST'] =  $lastIconType . ' ' . $curentLast['METER'];		//только последние данные

					// $curentCounterAll = [];

					//все показания

					$metersAll = LKClass::meters($object['ID']);
					// dump($metersAll);
					if ($metersAll) {
						foreach ($metersAll as $key => $meter) {
							// gg($meter);
							$arObjMeters[$meter['OBJECT']][$meter['COUNTER']][] = $meter['METER'];
						}
					}
					// dump($arObjMeters);

					// $arType = null;
					// $curentAll = current(LKClass::meters($object['ID']));
					// $curentCounterAll = $counterObjects[$curentAll['COUNTER']];

					/*if ($curentCounterAll['UF_TYPE']) {
						// dump($curentCounterAll['UF_TYPE']);
						foreach ($curentCounterAll['UF_TYPE'] as $type) {

							$arType = $arService[$type];

							$servicesAll[] = '<img src="' . $arType['ICON'] . '" width="18"/> ';
							//$servicesAll[] = $arType['LITERA'] . '<img class="ps-1" src="' . $arType['ICON'] . '" width="20"/> ';
						}
						// dump($servicesAll);
						$allIconType = implode('', $servicesAll);
					}*/
					//$column['METER_ALL'] =  $allIconType . ' ' . $curentAll['METER']; //все данные

					// if (!$column['COUNTER'])
					// 	$i--;

					// gg($column['METER_LAST']);

					// gg(testEmpty($column['METER_LAST']));

					// if ($arParams['CLEAR_DATA'] == 'Y' && empty($column['COUNTER']))
					// 	continue;

					if ($gRow > 0)
						$colRow['ROWSPAN'] = false;


					$this->arResult['GRID']['ROWS'][$i] = [
						'columns'	=> $column,
					];

					$this->arResult['ROWS_COLUMNS'][$i] = $colRow;

					$i++;
					$gRow++;
				}
			}

			/*$orgObjects = $arObjects[$item['ID']];
			// $column['OBJECT'] = var_export($orgObjects, 1);
			if ($orgObjects) {
				$column['COUNTS'] = count($orgObjects);
				$column['OBJECT'] = '';

				foreach ($orgObjects as $object) {
					$rowKey = $object['ID'];

					$column['OBJECT'] = $object;

					$this->arResult['GRID']['ROWS'][$i] = [
						'columns'	=> $column,
					];
					$i++;
				}
			}*/

			/*$this->arResult['GRID']['ROWS'][$i] = [
				// 'data'	=> $item,			//для редактирования
				'columns'	=> $column,		//отображение
				];*/
			if (!$item['OBJECTS'])
				$i++;
		}

		// gg($this->arResult['ROWS_COLUMNS']);

		// foreach ($column['COUNTER'] as $keyCounter => &$counter) {

		// 	// gg($counter);
		// 	// gg($keyCounter);

		// 	foreach ($column['METER_LAST'] as $meter) {
		// 		// gg($meter);

		// 		// if ($meter)
		// 		// unset($column['COUNTER'][$keyCounter]);
		// 		// unset($column['OBJECTS'][$keyObject]);
		// 		// $item['OBJECTS']$keyObject
		// 		// unset($object);

		// 		//unset($item['OBJECTS'][$keyObject]);
		// 	}
		// }
		// }


		// }



		// $rowColumns = ['ID', 'UF_NAME'/*, 'END'*/];
		$i = 1;
		foreach ($this->arResult['ROWS_COLUMNS'] as $key => $value) {

			// $this->arResult['GRID']['ROW_LAYOUT'][$key] = $columns;

			// dump($value);
			$c = 0;

			foreach ($this->arResult['GRID']['COLUMNS'] as $col) {

				if (
					$value['COUNTS'] > 1 && !$col['rowspan']
				) {
					$this->arResult['GRID']['ROW_LAYOUT'][$key][$c]['number'] = $i;
				}

				// foreach ($columns as $col) {

				if (!$value['ROWSPAN']) {
					// $c = 0;
					// dump($col['rowspan']);

					if (!$col['rowspan'] || $value['COUNTS'] == 1) {
						//if (!in_array($col, $rowColumns) || $value['COUNTS'] == 1)
						$this->arResult['GRID']['ROW_LAYOUT'][$key][$c]['column'] = $col['id'];
					}
				} else {
					// $c++;
					$this->arResult['GRID']['ROW_LAYOUT'][$key][$c]['column'] = $col['id'];

					if ($col['rowspan'])
						// if (in_array($col, $rowColumns))
						$this->arResult['GRID']['ROW_LAYOUT'][$key][$c]['rowspan'] = $value['COUNTS'];
				}

				$c++;
			}

			$i++;

			// foreach ($this->arResult['GRID']['ROW_LAYOUT'] as $key => $value) {
			// 	asort($value);
			// }

			// $this->arResult['GRID']['ROW_LAYOUT'][$key] = $columns;
		}

		// gg($this->arResult['GRID']['ROW_LAYOUT']);

		// if ($arResult['COUNTER_SHOW']) {
		// foreach ($this->arResult['GRID']['ROWS'] as $kRow => $row) {

		// 	// gg($row['columns']['COUNTER']);
		// 	if (empty($row['columns']['COUNTER'])) {
		// 		unset($this->arResult['GRID']['ROWS'][$kRow]);
		// 		unset($this->arResult['ROWS_COLUMNS'][$kRow]);
		// 	}
		// }
		// gg($this->arResult['GRID']['ROWS']);
		// gg($this->arResult['ROWS_COLUMNS']);


		// dump($this->arResult['ROWS_COLUMNS']);
		// dump($this->arResult['GRID']['ROW_LAYOUT']);
		// dump($this->arResult['GRID']['ROWS']);

		//return $componentPage;
		return $this->arResult;
	}


	/**
	 * Проверка доступа
	 *
	 * @return bool
	 */
	private function checkAccess()
	{
		global $USER;
		$arParams = $this->arParams;
		if ($USER->IsAuthorized()) {
			$rsGroups = \CUser::GetUserGroupEx($USER->GetID());
			while ($arGroup = $rsGroups->GetNext()) {
				if ($arGroup['GROUP_ID'] == 1 || $arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ADMINISTRATOR']) {
					$this->arResult['ADMIN'] = true;
					return true;
				}
				if ($arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ORGANIZATION']) {
					return true;
				}
			}
		}
		return false;
	}

	public function getRequest()
	{

		$instance = Application::getInstance();
		$context = $instance->getContext();
		$request = $context->getRequest();
		$arRequest = $request->toArray();
		return $arRequest;
	}

	public function isPost()
	{
		$instance = Application::getInstance();
		$context = $instance->getContext();
		$server = $context->getServer();
		return $server->getRequestMethod() == 'POST';
	}

	public function prepareComponentResult()
	{

		$arRequest = $this->getRequest();

		if ($this->isPost() && check_bitrix_sessid()) {

			// dump($arRequest);

			if ($arRequest["ADD_OBJECT"] == 'Y') {
				LKClass::addObject($arRequest["FIELDS"]);
			} elseif ($arRequest["ADD_COUNTER"] == 'Y') {
				LKClass::addCounter($arRequest["FIELDS"]);
			} elseif ($arRequest["ADD_COMPANY"] == 'Y') {
				LKClass::addCompany($arRequest["FIELDS"]);
			} elseif ($arRequest["grid_id"] == 'zhek_master_objects') {
				Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), '$arRequest', 'test.log');
				foreach ($arRequest["FIELDS"] as $companyID => $fields) {
					LKClass::saveCompany($companyID, $fields);
				}
			} else {
				foreach ($arRequest["FIELDS"] as $counterID => $fields) {
					LKClass::updateCounter($counterID, $fields);
					// LKClass::saveCounter($counterID, $fields);
				}
			}

			// $fields = [
			// 	'VOICE'         => $arFields['VOICE'],
			// 	'TEAM_ID'       => $ID,
			// ];
			// HackApi::sendVoiceMentor($fields);


			// }

			if (!isset($arRequest["AJAX_CALL"]))
				LocalRedirect(Context::getCurrent()->getRequest()->getRequestUri());
		}
	}

	// метод обработки режима ЧПУ
	protected function sefMode()
	{
		$arComponentVariables = [
			'sort'
		];
		$arDefaultVariableAliases404 = array(
			'section' => array(
				'ELEMENT_COUNT' => 'count',
			)
		);
		$arVariableAliases = CComponentEngine::makeComponentVariableAliases(
			$arDefaultVariableAliases404,
			$this->arParams["VARIABLE_ALIASES"]
		);

		$arDefaultUrlTemplates404 = [
			"detail" => "#DETAIL_ID#/",
		];
		$arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
			$arDefaultUrlTemplates404,
			$this->arParams["SEF_URL_TEMPLATES"]
		);

		$engine = new CComponentEngine($this);
		$arVariables = [];

		$componentPage = $engine->guessComponentPath(
			$this->arParams["SEF_FOLDER"],
			$arUrlTemplates,
			$arVariables
		);

		if ($componentPage == FALSE) {
			$componentPage = 'list';
		}

		CComponentEngine::initComponentVariables(
			$componentPage,
			$arComponentVariables,
			$arVariableAliases,
			$arVariables
		);

		$this->arResult = [
			'FOLDER'        => $this->arParams["SEF_FOLDER"],
			'URL_TEMPLATES' => $arUrlTemplates,
			'VARIABLES'     => $arVariables,
			'ALIASES'       => $arVariableAliases,
		];

		return $componentPage;
	}
}
