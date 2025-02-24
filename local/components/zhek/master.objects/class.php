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

class MasterObjects extends CBitrixComponent
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
		//gg(Context::getCurrent()->getRequest()->getRequestUri());
		$this->arResult['PAGE_SIZE'] = 10;

		$this->arResult['ACCESS'] = $this->checkAccess();
		$arItems = [];

		$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
		$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

		$serviceList = LKClass::getService();
		$this->arResult['SERVICE_LIST'] = $serviceList;

		$arItems = LKClass::getCompany();

		if (is_array($arItems))
			$this->arResult['GRID']['COUNT'] = count($arItems);

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

			// dump($serviceList);
			foreach ($serviceList as $key => $value) {
				$serviceItems[$value['ID']] = $value['NAME'];
			}

			$this->arResult['DETAIL']['COLUMNS'] = [
				['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true, 'width' => 70],
				['id' => 'NAME', 'name' => 'Наименование cчетчика', 'default' => true, 'width' => 200, 'editable' => true],
				['id' => 'NUMBER', 'name' => 'Номер cчетчика', 'default' => true, 'width' => 200, 'editable' => true],
				['id' => 'DATE', 'name' => 'Дата установки', 'default' => true, "editable" => ['TYPE' => 'CUSTOM']],
				['id' => 'TYPE', 'name' => 'Тип счетчика', 'default' => true, "editable" => ['TYPE' => 'MULTISELECT', 'items' => $serviceItems]],
				['id' => 'CHECK', 'name' => 'Дата очередной поверки', 'default' => true, "editable" => ['TYPE' => 'CUSTOM']],
				// ['id' => 'UF_CHECK', 'name' => 'Дата очередной поверки', 'default' => true, "editable" => ['TYPE' => 'DATE']],
				// ['id' => 'DETAIL', 'name' => '', 'default' => true, 'width' => '130'],
			];

			$snippet = new Bitrix\Main\Grid\Panel\Snippet();

			foreach ($objectList as $key => &$item) {

				$objectID = $item['ID'];

				$countersObject = LKClass::getCounters($objectID);

				$data = [];
				foreach ($countersObject as $key => $counter) {

					$types = [];
					// $editTypes = [];

					$column = $counter;

					$column['NAME'] = $counter['UF_NAME'];
					$column['NUMBER'] = $counter['UF_NUMBER'];

					foreach ($counter['UF_TYPE'] as $value) {

						$typeItem = $serviceList[$value];
						$types[] = '<div><img class="mb-1@" src="' . $typeItem['ICON'] . '" width="20" height="20" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '"/><span class="ps-1">' . $typeItem['NAME'] . '</span></div>';
						// $types[] = '<img src="' . $typeItem['ICON'] . '" width="25" height="25" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '"/>';
						// $editTypes[] = $value;
					}
					$column['TYPE'] = '<div class="row gy-1">' . implode('', $types) . '</div>';
					$data = $column;

					$data['TYPE'] = array_values($counter['UF_TYPE']);

					//if ($counter['UF_CHECK']) {
					// $objDateTime = new DateTime($counter['UF_CHECK']);
					// $column['UF_CHECK'] = $objDateTime->format("d.m.Y");
					//}
					$column['DATE'] = $counter['UF_DATE'] ?: '';
					$column['CHECK'] = $counter['UF_CHECK'] ?: '';

					$data['DATE'] = '<div class="ui-ctl ui-ctl-after-icon ui-ctl-date">
									<a class="ui-ctl-after ui-ctl-icon-calendar"></a>
									<!--<div class="ui-ctl-element">14.10.2014</div>-->
								<input type="text" id="" class= "ui-ctl-element" name="UF_DATE" value="' . $column['DATE'] . '">
								</div>
									<script>
										(function() {
											const input = document.querySelector(`input[name="UF_DATE"]`);

											const button = input.closest(".ui-ctl-date")
											// const button = input.previousElementSibling;
											console.log("button",button);
											// const button = input.nextElementSibling;
											let picker = null;
											const getPicker = () => {
												if (picker === null) {
													picker = new BX.UI.DatePicker.DatePicker({
														targetNode: input,
														inputField: input,
														enableTime: false,
														useInputEvents: false,
													});
												}

												return picker;
											};

											BX.Event.bind(button, "click", () => getPicker().show());
										})();
									</script>';

					$data['CHECK'] = '<div class="ui-ctl ui-ctl-after-icon ui-ctl-date">
									<a class="ui-ctl-after ui-ctl-icon-calendar"></a>
									<!--<div class="ui-ctl-element">14.10.2014</div>-->
								<input type="text" id="" class= "ui-ctl-element" name="UF_CHECK" value="' . $column['CHECK'] . '">
								</div>
									<script>
										(function() {
											const input = document.querySelector(`input[name="UF_CHECK"]`);

											const button = input.closest(".ui-ctl-date")
											// const button = input.previousElementSibling;
											console.log("button",button);
											// const button = input.nextElementSibling;
											let picker = null;
											const getPicker = () => {
												if (picker === null) {
													picker = new BX.UI.DatePicker.DatePicker({
														targetNode: input,
														inputField: input,
														enableTime: false,
														useInputEvents: false,
													});
												}

												return picker;
											};

											BX.Event.bind(button, "click", () => getPicker().show());
										})();
									</script>';


					$item['ROWS'][$key] = [
						'columns' => $column,
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

				$this->arResult['ITEMS'][] = $item;
			}

		} else {

			$result = \Bitrix\Main\UserGroupTable::getList(array(
				'order' => array('USER.LAST_LOGIN' => 'DESC'),
				'filter' => array(
					// 'USER.ACTIVE' => 'Y',
					'GROUP_ID' => [8, 1],
				),
				'select' => array(
					'ID' => 'USER.ID',
					'LOGIN' => 'USER.LOGIN',
					// 'PERSONAL_GENDER' => 'USER.PERSONAL_GENDER',
					'NAME' => 'USER.NAME',
					'LAST_NAME' => 'USER.LAST_NAME',
					// 'PERSONAL_CITY' => 'USER.PERSONAL_CITY',
					'UF_COMPANY' => 'USER.UF_COMPANY',
					'UF_PASSWORD' => 'USER.UF_PASSWORD'
				),
			));

			while ($user = $result->fetch()) {

				$user['SHORT_NAME'] = ($user['LAST_NAME'] ? $user['LAST_NAME'] . ' ' : '') . $user['NAME'];

				$userList[$user['ID']] = $user;
				$userItems[$user['ID']] = $user['SHORT_NAME'];
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
			while ($arEnum = $rsEnum->Fetch()) {
				//dump($arEnum);
			}

			//какую сортировку сохранил пользователь (передаем то, что по умолчанию)
			$arSort = $grid_options->GetSorting(array("sort" => array("timestamp_x" => "desc"), "vars" => array("by" => "by", "order" => "order")));
			$this->arResult['GRID']['COLUMNS'] = [
				['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true, 'width' => 70],
				['id' => 'UF_NAME', 'name' => 'Организация', /*'sort' => 'NAME', */ 'default' => true, 'width' => 300,  'editable' => true],
				['id' => 'UF_ADDRESS', 'name' => 'Адрес организации', 'width' => 200, 'default' => false, 'editable' => true],
				['id' => 'UF_INN', 'name' => 'ИНН',/* 'sort' => 'TIMESTAMP_X',*/ 'default' => true, 'editable' => true],
				// ['id' => 'DOGOVOR', 'name' => 'Текущий договор', 'default' => false],
				['id' => 'UF_USER', 'name' => 'Оператор', 'default' => true, "editable" => ['TYPE' => 'DROPDOWN', 'items' => $userItems]],
				['id' => 'UF_TYPE', 'name' => 'Тип организации', 'default' => false, "editable" => ['TYPE' => 'DROPDOWN', 'items' => $userItems]],
				['id' => 'DETAIL', 'name' => 'Объектов', 'default' => true],
			];

			$filterOption = new Bitrix\Main\UI\Filter\Options("filter_" . $this->arResult["GRID_ID"]);
			$filter = $filterOption->GetFilter();

			$navParams = [
				'offset' => $nav->getOffset(),
				'limit' => $nav->getLimit(),
			];

			$itemsCompany = LKClass::getCompany(null, $filter, $navParams);


			// $result = \Bitrix\Main\UserTable::getList(array(
			// 	'filter' => array('GROUP_ID' => 8),
			// 	'select' => array('ID', 'SHORT_NAME'), // выберем идентификатор и генерируемое (expression) поле SHORT_NAME
			// 	'order' => array('LAST_LOGIN' => 'DESC'), // все группы, кроме основной группы администраторов,
			// 	// 'limit' => 3
			// ));

			// while ($arUser = $result->fetch()) {

			// 	dump($arUser);
			// }


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

			foreach ($itemsCompany as $key => &$item) {
				$countObjects = 0;

				$column = $item;

				// dump($item);
				if ($item['UF_NAME']) {
					$column['UF_NAME'] = '<a class="ui-link fs-6" href="' . $item["ID"] . '/">' . $item['UF_NAME'] . '</a>';
				}

				if ($item['UF_USER_ID']) {
					$orgUser = $userList[$item['UF_USER_ID']];
					$item['UF_USER'] = '[' . $orgUser['ID'] . '] ' . $orgUser['SHORT_NAME'];
				}

				if ($arObjects[$item['ID']])
					$countObjects = count($arObjects[$item['ID']]);

				// $item['COMPANY'] = $item['COMPANY']['NAME'];

				$status = '<a class="d-flex!" href="' . $item["ID"] . '/">';

				$status .= '<div class="ui-btn ui-btn-secondary ui-btn-sm px-3 py-1 text-center opacity-75">Объектов ';
				//if ($countObjects) {
				$status .= '<div class="ui-counter ui-counter-' . ($countObjects ? 'primary gray!' : 'dark') . ' ms-2">
									<div class="ui-counter-inner">' . $countObjects . '</div>
								</div>';
				//$status .= '<i class="ui-btn-counter ui-counter-primary ms-2">' . $countObjects . '</i>';
				//}
				$status .= '</div>';
				$status .= '</a>';
				$item["DETAIL"] = $status;

				// dump($item);

				$this->arResult['GRID']['ROWS'][] = [
					'data' => $item,			//для редактирования
					'columns'	=> $column		//отображение
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

			// dump($arRequest);
			Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), '$arRequest', 'test.log');

			if ($arRequest["ADD_OBJECT"] == 'Y') {
				LKClass::addObject($arRequest["FIELDS"]);
			} elseif ($arRequest["ADD_COUNTER"] == 'Y') {
				LKClass::addCounter($arRequest["FIELDS"]);
			} elseif ($arRequest["ADD_COMPANY"] == 'Y') {
				LKClass::addCompany($arRequest["FIELDS"]);
			} elseif ($arRequest["grid_id"] == 'zhek_master_objects') {

				foreach ($arRequest["FIELDS"] as $companyID => $fields) {
					LKClass::saveCompany($companyID, $fields);
				}
			} else {

				if (isset($arRequest["ID"])) {
					foreach ($arRequest["ID"] as $counterID) {
						LKClass::deleteCounter($counterID);
					}
				} else {
				foreach ($arRequest["FIELDS"] as $counterID => $fields) {

						foreach ($fields as $key => $value) {
							if ($key == 'TYPE' && is_array($value)) {
								foreach ($value as $val) {
									$arVal[] = $val['VALUE'];
								}
								$data['UF_' . $key] = $arVal;
							} else {
								$data['UF_' . $key] = $value;
							}
						}
						LKClass::updateCounter($counterID, $data);
						//LKClass::updateCounter($counterID, $fields);
					}
				}
			}

			// $fields = [
			// 	'VOICE'         => $arFields['VOICE'],
			// 	'TEAM_ID'       => $ID,
			// ];
			// HackApi::sendVoiceMentor($fields);


			// }

			// if (!isset($arRequest["AJAX_CALL"])) {
			// 	self::run();
				LocalRedirect(Context::getCurrent()->getRequest()->getRequestUri());
			// }

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