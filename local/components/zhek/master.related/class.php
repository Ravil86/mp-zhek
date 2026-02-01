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

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class MasterRelated extends CBitrixComponent implements Controllerable
{

	// protected static $_HL_Related = "RelatedCounters"; // HL общий реестр
	// protected static $_HL_Reference = "ReferenceCustomer"; // HL общий реестр
	// protected static $_HL_Objects = "Objects"; // HL общий реестр
	// protected static $_HL_Company = "Company"; // HL категории курсов


	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	public function configureActions()
	{
		return [
			'editObject' => [
				'prefilters' => [
					new ActionFilter\Authentication,
					new ActionFilter\HttpMethod([
						ActionFilter\HttpMethod::METHOD_POST
					])
				],
			],
		];
	}

	public function executeComponent()
	{

		if ($this->arParams["SEF_MODE"] == "Y") {
			$componentPage = $this->sefMode();
		}

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

		$this->arResult['PAGE_SIZE'] = 10;

		$this->arResult['ACCESS'] = $this->checkAccess();
		$arItems = [];

		$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
		$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

		$serviceList = LKClass::getService();
		$this->arResult['SERVICE_LIST'] = $serviceList;

		foreach ($serviceList as $value) {
			// gg($value);
			$this->arResult['SERVICES'][$value['ID']] = '<img src="' . $value['ICON'] . '" width="23" height="23" alt="' . $value['NAME'] . '" title="' . $value['NAME'] . '" data-bs-toggle="tooltip"
							data-bs-title="' . $value['NAME'] . '"/><span class="ps-1">' . $value['NAME'] . '</span>';
		}

		// $item['SERVICE'] = '<div>' . implode(' ', $types) . '</div>';

		$getCompany = LKClass::getCompany();

		$getObjects = LKClass::getObjects(null, 'ID');
		$this->arResult['OBJECTS'] = $getObjects;

		$getCounters = LKClass::getCounters();
		$this->arResult['COUNTERS'] = $getCounters;

		foreach ($getObjects as $object) {

			$arObjects[$object['ORG']][] = $object;		// по ключу организации
			$orgObjectsIDs[$object['ORG']][$object['ID']] = $object['ID']; // по ключу организации и id объекта

			$arCompany[$object['ORG']]['OBJECTS'][$object['ID']] = $object;

			$valCompany = $getCompany[$object['ORG']];

			$this->arResult['OBJECTS_ITEMS'][$object['ID']] = '#' . $object['ID'] . ' ' . TruncateText($object['NAME'], 50) . ' / #' . $object['ORG'] . ' - ' . ($valCompany['UF_SHORT_NAME'] ?: TruncateText($valCompany['UF_NAME'], 50));
		}
		$this->arResult['COMPANY'] = $arCompany;

		$this->arResult['COMPANY_JSON'][] = [
			'id' => '',
			'text' => 'выберите',
		];

		foreach ($getCompany as $key => $org) {

			// gg($org);
			$objects = [];

			foreach ($org['OBJECTS'] as $key => $object) {

				$objects[] = [
					'id' => (int)$object['ID'],
					'text' => $object['NAME'],
					// "disabled" => true
				];

				// $this->arResult['OBJECTS_JSON'][$org['ID']] = $objects;
				// $this->arResult['OBJECTS_JSON'][$org['ID']][$key] = [
				// 	'id' => $object['ID'],
				// 	'text' => $object['NAME'],
				// ];
			}

			$this->arResult['OBJECTS_JSON'][$org['ID']] = [
				// 'id' => $org['ID'],
				'text' => $org['UF_NAME'],
				'children' => $objects,
			];

			$this->arResult['COMPANY_JSON'][] = [
				'id' => $org['ID'],
				'text' => $org['UF_NAME']
			];

			$this->arResult['COMPANY_ITEMS'][$org['ID']] = '#' . $org['ID'] . ' ' . TruncateText($org['UF_NAME'], 50);


			//$this->arResult['OBJECTS_ITEMS'][$org['ID']]['ITEMS'][$object['ID']] = '#' . $object['ID'] . ' ' . $object['NAME'];
		}

		// gg($getCompany);
		// gg($this->arResult['OBJECTS_JSON']);
		// gg($this->arResult['COMPANY_JSON']);

		// gg($arObjects);

		#LIST
		// $result = \Bitrix\Main\UserGroupTable::getList(array(
		// 	'order' => array('USER.LAST_LOGIN' => 'DESC'),
		// 	'filter' => array(
		// 		// 'USER.ACTIVE' => 'Y',
		// 		'GROUP_ID' => [8, 1],
		// 	),
		// 	'select' => array(
		// 		'ID' => 'USER.ID',
		// 		'LOGIN' => 'USER.LOGIN',
		// 		// 'PERSONAL_GENDER' => 'USER.PERSONAL_GENDER',
		// 		'NAME' => 'USER.NAME',
		// 		'LAST_NAME' => 'USER.LAST_NAME',
		// 		// 'PERSONAL_CITY' => 'USER.PERSONAL_CITY',
		// 		'UF_COMPANY' => 'USER.UF_COMPANY',
		// 		'UF_PASSWORD' => 'USER.UF_PASSWORD'
		// 	),
		// ));

		// while ($user = $result->fetch()) {

		// 	$user['SHORT_NAME'] = ($user['LAST_NAME'] ? $user['LAST_NAME'] . ' ' : '') . $user['NAME'];

		// 	$userList[$user['ID']] = $user;
		// 	$userItems[$user['ID']] = $user['SHORT_NAME'];
		// }

		$grid_options = new CGridOptions($this->arResult["GRID_ID"]);
		$nav_params = $grid_options->GetNavParams(array("nPageSize" => $this->arResult['PAGE_SIZE']));
		$nav = new Bitrix\Main\UI\PageNavigation($this->arResult["GRID_ID"]);

		$order = $grid_options->GetSorting(['sort' => ['UF_ACTIVE' => 'desc', 'ID' => 'desc'], 'vars' => ['by' => 'by', 'order' => 'order']]);

		$nav->allowAllRecords(true)
			->setPageSize($nav_params['nPageSize'])
			->initFromUri();

		if ($nav->allRecordsShown())
			$nav_params = false;
		else
			$nav_params['iNumPage'] = $nav->getCurrentPage();

		$navParams = [
			'offset' => $nav->getOffset(),
			'limit' => $nav->getLimit(),
		];

		$this->arResult['COLUMNS'] = [
			['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true, 'width' => 50],
			['id' => 'UF_COUNTER', 'name' => 'Прибор учёта', 'sort' => '', 'default' => true, 'width' => 150],
			['id' => 'UF_OBJECT', 'name' => 'Объект', 'sort' => '', 'default' => true, 'editable' => ['TYPE' => 'DROPDOWN', 'items' => $this->arResult['OBJECTS_ITEMS']]],
			['id' => 'UF_ORG', 'name' => 'Организация', 'sort' => '', 'default' => true, 'editable' => ['TYPE' => 'DROPDOWN', 'items' => $this->arResult['COMPANY_ITEMS']]],
			['id' => 'UF_MAIN', 'name' => 'Главная организация', 'sort' => '', 'default' => true, 'width' => 100],
			['id' => 'UF_PERCENT', 'name' => 'Процент занимаемого объема/площади, %', 'default' => true, 'width' => 150, 'editable' => ['TYPE' => 'NUMBER', 'min' => 0.1, 'max' => 100]],
			['id' => 'COUNTER', 'name' => '', 'default' => true, 'editable' => false],
		];

		// $this->arResult['GRID']["FILTER"] = [
		// 	['id' => 'UF_ACTIVE', 'name' => 'Активность', 'type' => 'list', 'items' => [1 => 'да', 0 => 'нет'], 'default' => true],
		// ];

		// $filterOption = new Bitrix\Main\UI\Filter\Options("filter_" . $this->arResult["GRID_ID"]);
		// $filter = $filterOption->GetFilter();


		$itemsRelated = LKClass::getRelated(1);
		// asort($itemsRelated);
		// natsort($itemsRelated);

		// $this->arResult['ITEMS'] = $itemsRelated;
		foreach ($itemsRelated as $key => &$item) {

			$row = [];

			foreach ($item as $pid => $value) {

				$column['ID'] = $value['ID'];

				$column['UF_COUNTER'] = $getCounters[$value['UF_COUNTER']]['UF_NUMBER'];
				$column['UF_OBJECT'] = '<small>#' . $value['UF_OBJECT'] . '</small> ' . $getObjects[$value['UF_OBJECT']]['NAME'] . '
											<a title="перейти в обьекты" href="/master/objects/' . $value['UF_ORG'] . '/#item-' . $value['UF_OBJECT'] . '"
											target="_blank">
											<i class="bi bi-buildings"></i>
											</a>';

				$column['UF_ORG'] = '<small>#' . $value['UF_ORG'] . '</small> ' . $getCompany[$value['UF_ORG']]['UF_NAME'];
				$column['UF_MAIN'] = $value['UF_MAIN'] ? 'да' : '';
				$column['UF_PERCENT'] = $value['UF_PERCENT'];

				$column['COUNTER'] = '<a title="показания" href="/master/counter/' . $value['UF_OBJECT'] . '"
											target="_blank">
											<img src="' . SITE_TEMPLATE_PATH . '/images/counter_small.png" width="25">
											</a>';

				$data['ID'] = $value['ID'];		//Обязательно
				$data['UF_OBJECT'] = $value['UF_OBJECT'];
				$data['UF_PERCENT'] = $value['UF_PERCENT'];
				$data['UF_ORG'] = $value['UF_ORG'];

				$row[$value['ID']] = [
					'data' => $data,			//для редактирования
					'columns'	=> $column		//отображение
				];
			}

			// $this->arResult['ITEMS'][$key]['ROWS']['id'] = $key;
			$this->arResult['ITEMS'][$key]['ROWS'] = $row;
		}
		// gg($this->arResult['ITEMS']);
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
				if ($arGroup['GROUP_ID'] == 1) {
					$this->arResult['ADMIN'] = true;
					return true;
				} elseif ($arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ADMINISTRATOR']) {
					$this->arResult['MODERATOR'] = true;
					return true;
				}
				if ($arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ORGANIZATION']) {
					return true;
				}
			}
		}
		return false;
	}

	// public function sendLossesAction()
	// {
	// 	$request = $this->getRequest();
	// 	//$request = Application::getInstance()->getContext()->getRequest();

	// 	Bitrix\Main\Diag\Debug::dumpToFile(var_export($request, 1), '$request', 'test.log');

	// 	LKClass::saveLosses($request['object'], $request['losses']);
	// 	// foreach ($request['METER'] as $kCounter => $meter) {
	// 	// 	LKClass::saveMeter($request['OBJECT'], $kCounter, $meter, $request['NOTE'][$kCounter]);
	// 	// }

	// 	return true;
	// }

	// public function sendNormaAction()
	// {
	// 	$request = $this->getRequest();
	// 	Bitrix\Main\Diag\Debug::dumpToFile(var_export($request, 1), '$request', 'test.log');

	// 	return LKClass::saveLosses($request['object'], $request['norma'], true);

	// 	// return true;
	// }

	// public function editObjectAction()
	// {
	// 	$request = $this->getRequest();
	// 	//$request = Application::getInstance()->getContext()->getRequest();

	// 	Bitrix\Main\Diag\Debug::dumpToFile(var_export($request, 1), '$request', 'test.log');

	// 	$result = LKClass::updateObject($request['OBJECT'], $request['object']);

	// 	return $result;
	// }

	// private function arraySort($a, $b)
	// {
	// 	if ($a['SORT'] == $b['SORT']) {
	// 		return 0;
	// 	}
	// 	return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	// }


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
			Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), '$arRequest', 'test.log');

			if ($arRequest["ADD_RELATED"] == 'Y') {

				Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest["FIELDS"], 1), 'ADD_RELATED', 'test.log');
				LKClass::addRelated($arRequest["FIELDS"]);
			} else {

				foreach ($arRequest["FIELDS"] as $counterID => $fields) {

					foreach ($fields as $key => $value) {
						$data[$key] = $value;
					}
					LKClass::updateRelated($counterID, $data);

					Bitrix\Main\Diag\Debug::dumpToFile(var_export($data, 1), 'UPDATE_RELATED', 'test.log');
				}


				/*if (isset($arRequest["ID"])) {
					foreach ($arRequest["ID"] as $counterID) {
						LKClass::deleteCounter($counterID);
					}
				} else {*/
				//}
			}
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
