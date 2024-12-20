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

class LKObjects extends CBitrixComponent
{

	protected static $_HL_Reference = "ReferenceCustomer"; // HL общий реестр
	protected static $_HL_Objects = "Objects"; // HL общий реестр
	protected static $_HL_Company = "Company"; // HL категории курсов


	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	public function executeComponent()
	{

		if ($this->arParams["SEF_MODE"] == "Y") {
			$componentPage = $this->sefMode();
		}
		if ($this->arParams["SEF_MODE"] != "Y") {
			$componentPage = $this->noSefMode();
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

		$this->arResult['ACCESS'] = $this->checkAccess();
		$arItems = [];

		$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
		$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

		$serviceList = LKClass::getService();
		$this->arResult['SERVICE_LIST'] = $serviceList;

		// $myCompany = LKClass::myCompany();
		$arItems = LKClass::getCompany();

		// if ($myCompany)
		$getObjects = LKClass::getObjects();
		foreach ($getObjects as $key => $value) {
			$arObjects[$value['ORG']][] = $value;
		}

		if ($this->arResult['VARIABLES']) {

			$orgID = $this->arResult['VARIABLES']['DETAIL_ID'];

			$this->arResult['DETAIL']['ORG'] =  $arItems[$orgID];

			$this->arResult['DETAIL']['GRID'] =  $this->arResult['GRID_ID'] . '_detail';
			$arResult['DETAIL']['GRID'] = $this->arResult['DETAIL']['GRID'];

			$objectList = $arObjects[$orgID];
			// dump($objectList);

			$grid_options = new CGridOptions($this->arResult['DETAIL']['GRID']);
			$arSort = $grid_options->GetSorting(array("sort" => array("timestamp_x" => "desc"), "vars" => array("by" => "by", "order" => "order")));

			$this->arResult['DETAIL']['COLUMNS'] = [
				['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false, 'width' => 70],
				['id' => 'UF_NAME', 'name' => 'Наименование cчетчика', 'default' => true, 'width' => 250, 'editable' => true],
				['id' => 'UF_NUMBER', 'name' => 'Номер cчетчика', 'default' => true, 'width' => 250, 'editable' => true],
				['id' => 'SERVICE', 'name' => 'Тип счетчика', 'default' => true, 'width' => 200],
				['id' => 'UF_DATE', 'name' => 'Сл. дата поверки', 'default' => true, 'width' => 200, "editable" => ['TYPE' => 'DATE']],
				// ['id' => 'DETAIL', 'name' => '', 'default' => true, 'width' => '130'],
			];

			$snippet = new Bitrix\Main\Grid\Panel\Snippet();

			foreach ($objectList as $key => &$item) {

				$objectID = $item['ID'];

				$countersObject = LKClass::getCounters($objectID);

				foreach ($countersObject as $key => $counter) {

					$types = [];

					foreach ($counter['UF_TYPE'] as $value) {
						$typeItem = $serviceList[$value];
						$types[] = '<img src="' . $typeItem['ICON'] . '" width="25" height="25" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '"/>';
					}
					$counter['SERVICE'] = implode(' ', $types);

					$data = $counter;
					if ($counter['UF_DATE']) {
						$objDateTime = new DateTime($counter['UF_DATE']);
						$data['UF_DATE'] = $objDateTime->format("d.m.Y");
					}

					$item['ROWS'][$key] = [
						'columns' => $counter,
						'data' => $data,		//Данные для инлайн-редактирования

						//'actions' => [ //Действия над ними
						// [
						// 	'text'    => 'Редактировать',
						// 	'onclick' => 'document.location.href="/accountant/reports/1/edit/"'
						// ],
						// 	[
						// 		'text'    => 'Удалить',
						// 		'onclick' => 'document.location.href="/accountant/reports/1/delete/"'
						// 	]
						// ],
					];
				}

				// $item['ROWS'] = $countersObject;

				// $item['ROWS'][$objectID] = [
				// 	'data' => $countersObject
				// ];

				// $this->arResult['ITEMS'][$objectID]['ROWS'][] = [
				// 	'data' => $item
				// ];

				// $this->arResult['DETAIL']
			}

			$this->arResult['ITEMS'] = $objectList;



			// $this->arResult['DETAIL'] = $arItems[$this->arResult['VARIABLES']['DETAIL_ID']];

		} else {


			//инициализируем объект с настройками пользователя для нашего грида
			$grid_options = new CGridOptions($this->arResult["GRID_ID"]);

			//размер страницы в постраничке (передаем умолчания)
			$nav_params = $grid_options->GetNavParams(array("nPageSize" => 10));
			// $nav_params = $grid_options->GetNavParams();

			$nav = new Bitrix\Main\UI\PageNavigation($this->arResult["GRID_ID"]);
			$nav->allowAllRecords(true)
				->setPageSize($nav_params['nPageSize'])
				->initFromUri();

			if ($nav->allRecordsShown())
				$nav_params = false;
			else
				$nav_params['iNumPage'] = $nav->getCurrentPage();


			//какую сортировку сохранил пользователь (передаем то, что по умолчанию)
			$arSort = $grid_options->GetSorting(array("sort" => array("timestamp_x" => "desc"), "vars" => array("by" => "by", "order" => "order")));
			$this->arResult['GRID']['COLUMNS'] = [
				['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false, 'width' => 70],
				['id' => 'NAME', 'name' => 'Организация', /*'sort' => 'NAME', */ 'default' => true],
				['id' => 'ADRES', 'name' => 'Адрес организации', /*'sort' => 'ADDRESS', */ 'default' => true, 'width' => 300],
				['id' => 'INN', 'name' => 'ИНН',/* 'sort' => 'TIMESTAMP_X',*/ 'default' => true, 'width' => 200],
				['id' => 'DETAIL', 'name' => '', 'default' => true,],
			];

			// $filterOption = new Bitrix\Main\UI\Filter\Options($this->arResult["GRID_ID"]);
			// $filterData = $filterOption->GetFilter();

			/*
			$useFilter = false;

			if (isset($filterData["DATE_MODIFY_from"])) {
				$userFilter["DATE_MODIFY_FROM"] = $filterData["DATE_MODIFY_from"];
				$userFilter["DATE_MODIFY_TO"] = $filterData["DATE_MODIFY_to"];
			}

			if (isset($filterData["FIND"])) {
				if (isset($filterData["NAME"]))
					$userFilter["NAME"] = "%" . $filterData["NAME"] . "%";
				else
					$userFilter["NAME"] = "%" . $filterData["FIND"] . "%";
			}
			if (isset($filterData["DATE_CREATE_from"])) {
				$userFilter[">=DATE_CREATE"] = $filterData["DATE_CREATE_from"];
			}
			if (isset($filterData["DATE_CREATE_to"])) {
				$userFilter["<=DATE_CREATE"] = $filterData["DATE_CREATE_to"];
			}
			if (isset($filterData["COURSE"])) {
				$userFilter["PROPERTY_COURSE"] = $filterData["COURSE"];
			}
			if (isset($filterData["COURSE"]))
				$userFilter["PROPERTY_COURSE"] = $filterData["COURSE"];

			if (isset($filterData["MO"]))
				$userFilter["PROPERTY_MO"] = $filterData["MO"];

			if (!$nav_params['nPageSize'])
				$nav_params['nPageSize'] = 500;

			if ($arSort['sort']['TIMESTAMP_X'])
				$arSort['sort']['DATE_CREATE'] = $arSort['sort']['TIMESTAMP_X'];
			*/

			foreach ($arItems as $key => &$item) {
				$countObjects = 0;

				if ($arObjects[$item['ID']])
					$countObjects = count($arObjects[$item['ID']]);

				// $item['COMPANY'] = $item['COMPANY']['NAME'];

				$status = '<a class="d-flex!" href="' . $item["ID"] . '/">';
				$status .= '<div class="ui-btn ui-btn-secondary px-3 py-1 text-center opacity-75">Объектов <i class="ui-btn-counter ms-2">' . $countObjects . '</i></div>';
				$status .= '</a>';
				$item["DETAIL"] = $status;

				$this->arResult['GRID']['ROWS'][] = [
					'data' => $item
				];
			}

		}

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

	private function arraySort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']) {
			return 0;
		}
		return ($a['SORT'] < $b['SORT']) ? -1 : 1;
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


			Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), '$arRequest', 'test.log');
			// dump($arRequest);

			if ($arRequest["ADD"] == 'Y') {
				LKClass::addCounter($arRequest["FIELDS"]);
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
