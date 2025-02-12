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
				// 'DATE' => $contr['DATE'],
			];
		}
		// dump($orgContracts);

		$arItems = LKClass::getCompany();

		if (is_array($arItems))
			$this->arResult['GRID']['COUNT'] = count($arItems);

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
			['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false, 'width' => 50, 'resizeable' => false, 'rowspan' => true],
			// ['id' => 'COUNTER', 'name' => '', 'default' => true],
			['id' => 'UF_NAME', 'name' => 'Организация', /*'sort' => 'NAME', */ 'default' => true, 'rowspan' => true /*'sticked' => true, 'resizeable' => true*/],
			// ['id' => 'UF_ADDRESS', 'name' => 'Адрес организации', /*'sort' => 'ADDRESS', */ 'default' => false],
			// ['id' => 'UF_INN', 'name' => 'ИНН',/* 'sort' => 'TIMESTAMP_X',*/ 'default' => false, 'rowspan' => true],
			['id' => 'DOGOVOR', 'name' => 'Текущий контракт', 'default' => true, 'rowspan' => true],
			['id' => 'OBJECT', 'name' => 'Объект', 'default' => true],
			['id' => 'COUNTER', 'name' => 'ПУ', 'default' => true],
			['id' => 'METER_LAST', 'name' => 'Текущие показания', 'default' => true, 'colspan' => 2, 'text' => 'Показания', 'color' => '#ddd'],
			['id' => 'METER_ALL', 'name' => 'Предыдущие показания', 'default' => true, 'colspan' => 0, 'color' => '#ddd'],
			// ['id' => 'UF_TYPE', 'name' => 'Тип организации', 'default' => false, 'rowspan' => true /*"editable" => ['TYPE' => 'DROPDOWN', 'items' => $userItems]*/],
			];

			$filterOption = new Bitrix\Main\UI\Filter\Options("filter_" . $this->arResult["GRID_ID"]);
			$filter = $filterOption->GetFilter();

			$navParams = [
				'offset' => $nav->getOffset(),
				'limit' => $nav->getLimit(),
			];

			$itemsCompany = LKClass::getCompany(null, $filter, $navParams);


		$orgObjects = null;
		foreach ($itemsCompany as &$item) {

			if ($orgObjects = $arObjects[$item['ID']])
			foreach ($orgObjects as $object) {
				$item['OBJECTS'][$object['ID']] = $object;		//заполняем обьектами организации для сортировки
			}


			if ($getDogovOrg = $orgContracts[$item['ID']])	//заполняем контрактами для сортировки
			$item['CONTRACTS'] = $getDogovOrg;
		}

		function sortObjects($a, $b)
		{

			if (isset($a['OBJECTS']) == isset($b['OBJECTS'])) {
				return 0;
			}
			return ($a['OBJECTS'] > $b['OBJECTS']) ? -1 : 1;

			// return ($a['OBJECTS'] < $b['OBJECTS']) ? -1 : 1;
		}
		usort($itemsCompany, "sortObjects");

		function sortContract($a, $b)
		{
			if (isset($a['CONTRACTS']) == isset($b['CONTRACTS'])) {
				return 0;
			}
			return ($a['CONTRACTS'] > $b['CONTRACTS']) ? -1 : 1;
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
		foreach ($itemsCompany as $key => &$item) {		// записи по всем организации
				$countObjects = 0;



			$column = $item;
			// if ($itemsCompany[$orgID])
			// 	$column['UF_NAME'] = $itemsCompany[$orgID]['UF_NAME'];

			if ($item['CONTRACTS']) {
				$dogovor = '<div class="text-' . $item['CONTRACTS'][0]['STATUS']['CODE'] . ' px-3 py-1 text-center"><small>' . $item['CONTRACTS'][0]['NUMBER'] . '</small></div>';
				$column["DOGOVOR"] = $dogovor;
			}

			if ($item['OBJECTS']) {

				$countObjects = count($item['OBJECTS']);
				$colRow['COUNTS'] = $countObjects;
				$colRow['ROWSPAN'] = $countObjects > 1 ?: false;

				$g = 0;

				$arService = LKClass::getService();

				// dump($arService);

				foreach ($item['OBJECTS'] as $key => $object) {

					$servicesLast = [];
					$servicesAll = [];
					$lastIconType = '';
					$allIconType = '';

					$column['OBJECT'] = '#' . $object['ID'] . ' ' . $object['NAME'];

					$counterObjects = LKClass::getCounters($object['ID']);

					// gg($counterObjects);

					//последние показания
					$arType = null;
					$curentLast = current(LKClass::meters($object['ID'], 1));
					$curentCounterLast = $counterObjects[$curentLast['COUNTER']];

					if ($curentCounterLast['UF_TYPE']) {
						foreach ($curentCounterLast['UF_TYPE'] as $type) {
							$arType = $arService[$type];
							$servicesLast[] = '<img src="' . $arType['ICON'] . '" width="20"/>';
							// $servicesLast[] = $arType['LITERA'] . '<img class="ps-1" src="' . $arType['ICON'] . '" width="20"/>';
						}
						$lastIconType = implode('/', $servicesLast);
						unset($arType);
					}
					$column['METER_LAST'] =  $lastIconType . ' ' . $curentLast['METER'];		//только последние данные


					$curentCounterAll = [];

					//все показания
					$arType = null;
					$curentAll = current(LKClass::meters($object['ID']));
					$curentCounterAll = $counterObjects[$curentAll['COUNTER']];

					if ($curentCounterAll['UF_TYPE']) {
						// dump($curentCounterAll['UF_TYPE']);
						foreach ($curentCounterAll['UF_TYPE'] as $type) {

							$arType = $arService[$type];

							$servicesAll[] = '<img src="' . $arType['ICON'] . '" width="20"/> ';
							//$servicesAll[] = $arType['LITERA'] . '<img class="ps-1" src="' . $arType['ICON'] . '" width="20"/> ';
						}
						// dump($servicesAll);
						$allIconType = implode('', $servicesAll);
					}
					$column['METER_ALL'] =  $allIconType . ' ' . $curentAll['METER']; //все данные
					// $column['METER_ALL'] =  $curentAll;
					//$column['OBJECT'] = $object['NAME'];

					if ($g > 0)
					$colRow['ROWSPAN'] = false;

					$this->arResult['GRID']['ROWS'][$i] = [
						'columns'	=> $column,
					];
					$this->arResult['ROWS_COLUMNS'][$i] = $colRow;

					$i++;
					$g++;
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
		// }

		// dump($this->arResult['GRID']['ROWS']);

		// $this->arResult['GRID']['ROW_LAYOUT'][0] = ['data' => 'data_field_1', 'colspan' => 3];
		// $this->arResult['GRID']['ROW_LAYOUT'][1] = $columns;

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
					LKClass::saveCounter($counterID, $fields);
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

	// метод обработки режима без ЧПУ
	/*protected function noSefMode()
	{
		if (empty($this->arParams["VARIABLE_ALIASES"]["CATALOG_URL"])) {
			$dbResult = CIBlock::GetByID($this->arParams["IBLOCK_ID"])->GetNext();
			if (!empty($dbResult)) {
				$this->arParams["VARIABLE_ALIASES"]["ELEMENT_ID"] = preg_replace('/\#/', '', $dbResult["DETAIL_PAGE_URL"]);
				$this->arParams["VARIABLE_ALIASES"]["SECTION_ID"] = preg_replace('/\#/', '', $dbResult["SECTION_PAGE_URL"]);
				$this->arParams["VARIABLE_ALIASES"]["CATALOG_URL"] = preg_replace('/\#/', '', $dbResult["LIST_PAGE_URL"]);
			}
		}
		$arDefaultVariableAliases = [
			'ELEMENT_COUNT' => 'count',
		];
		$arVariableAliases = CComponentEngine::makeComponentVariableAliases(
			$arDefaultVariableAliases,
			$this->arParams["VARIABLE_ALIASES"]
		);
		$arVariables = [];
		// дополнительные GET параметры которые будем отлавливать в запросе, в массив $arVariables будет добавлена переменная sort, значение которой будет получено из $_REQUEST['sort'], применяется когда не нужно указывать точный псевдоним для ключа
		$arComponentVariables = [
			'sort'
		];
		// метод предназначен для получения и объединения GET параметров результат записываем в $arVariables
		CComponentEngine::initComponentVariables(
			false,
			$arComponentVariables,
			$arVariableAliases,
			$arVariables
		);
		$context = Application::getInstance()->getContext();
		$request = $context->getRequest();
		$rDir = $request->getRequestedPageDirectory();
		$componentPage = "";
		// если запрошенная директория равна переданой в arParams["CATALOG_URL"], определяем тип страницы стартовая
		if ($arVariableAliases["CATALOG_URL"] == $rDir) {
			$componentPage = "index";
		}
		// по найденным параметрам $arVariables определяем тип страницы секция
		if ((isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0) || (isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> '')) {
			$componentPage = "section";
		}
		// по найденным параметрам $arVariables определяем тип страницы элемент
		if ((isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) || (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '')) {
			$componentPage = "element";
		}
		$this->arResult = [
			"VARIABLES" => $arVariables,
			"ALIASES" => $arVariableAliases
		];
		return $componentPage;
	}*/
}
