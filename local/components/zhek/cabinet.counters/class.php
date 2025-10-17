<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Uriit\Contest\Helper;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock,
	Bitrix\Iblock\Component\Tools,
	Bitrix\Main\Application,
	Bitrix\Main\Web\Uri;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;


class CabinetCounters extends CBitrixComponent implements Controllerable
{

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	public function configureActions()
	{
		return [
			'sendMeter' => [
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

		if ($this->arParams["SEF_MODE"] === "Y") {
			$componentPage = $this->sefMode();
		}
		// если отключен режим поддержки ЧПУ, вызываем метод noSefMode()
		if ($this->arParams["SEF_MODE"] != "Y") {
			$componentPage = $this->noSefMode();
		}

		// отдаем 404 статус если не найден шаблон
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
		$arResult = $this->arResult;
		// $arItems = [];

		$serviceList = LKClass::getService();

		$myCompany = LKClass::myCompany();

		$this->arResult['COMPANY'] = LKClass::getCompany();

		$monthList = LKClass::getMonth();

		$selfMonth = date("m", strtotime('-1 month'));
		// $selfMonth = date("m");
		$prevMonth = date("m", strtotime('-1 month'));

		//Текущая дата
		$curDay = date("d");
		// $curDay = 25;

		//Дата начала подачи
		$dateStart = 8;
		// $dateStart = 25;

		//Дата окончания подачи
		// $dateEnd = date('t');	//конец месяца
		$dateEnd = 24;

		// Дата окончания редактирования модератором
		// $editEnd = 5;
		$editEnd = 30;

		if ($dateStart <= $curDay && $curDay <= $dateEnd) {		//период подачи пользователем до конца месяца
			$this->arResult['SAVE_MONTH'] = $monthList[$selfMonth];
			$arResult['DATE_USER'] = true;
		} elseif ($curDay == 1) {								//период подачи пользователем 1 числа
			$this->arResult['SAVE_MONTH'] = $monthList[$prevMonth];
			$arResult['DATE_USER'] = true;
		} elseif ($curDay > 1 && $curDay <= $editEnd) {
			$this->arResult['SAVE_MONTH'] = $monthList[$prevMonth];
			$arResult['DATE_ADMIN'] = true;
		} elseif ($curDay > $editEnd && $curDay < $dateStart) {
			$this->arResult['SAVE_MONTH'] = $monthList[$selfMonth];
		}
		// gg($arResult);
		$arResult['SAVE_MONTH'] = $this->arResult['SAVE_MONTH'];

		// if ($dateStart <= $day && $day <= $dateEnd)
		// 	$arResult['DATE_USER'] = true;

		// if ($dateEnd < $day && $day <= $editEnd)
		// 	$arResult['DATE_ADMIN'] = true;

		$this->arResult['SEND_ADMIN'] = $arResult['DATE_ADMIN'] && $arResult['MODERATOR'] || $arResult['ADMIN'];
		$this->arResult['SEND_FORM'] = $arResult['DATE_USER'] && !$arResult['MODERATOR'] ?? false;

		if ($this->arResult['ADMIN'] || $this->arResult['MODERATOR'])
			$arObjects = LKClass::getObjects();
		elseif ($myCompany)
			$arObjects = LKClass::getObjects($myCompany['ID']);

		//NEW  detail to LIST

		$this->arResult['COUNTER_OBJECTS'] = $arObjects;

		$this->arResult['COUNTERS'] = LKClass::getCounters();
		$this->arResult['RELATED'] = LKClass::getRelated();

		if ($this->arResult['VARIABLES']) {

			$objectID = $this->arResult['VARIABLES']['DETAIL_ID'];

			$this->arResult['OBJECT_ID'] = $objectID;

			// $this->arResult['DETAIL']['GRID'] =  'object_detail';
			// $arResult['DETAIL']['GRID'] = $this->arResult['DETAIL']['GRID'];

			if (!array_key_exists($objectID, $arObjects))
				$this->arResult['WRONG'] = true;

			$this->arResult['DETAIL']['OBJECT'] = $arObjects[$objectID];

			$countersObject  = LKClass::getCounters($objectID);

			foreach ($countersObject as $key => &$item) {

				// gg($item);

				if ($item['UF_ACTIVE'] !== null && !$item['UF_ACTIVE'])	//Отключаем неактивные
					continue;

				$types = [];
				foreach ($item['UF_TYPE'] as $value) {
					$typeItem = $serviceList[$value];
					$unit = $typeItem['UNIT'];
					$types[] = '<img src="' . $typeItem['ICON'] . '" width="23" height="23" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '" data-bs-toggle="tooltip"
							data-bs-title="' . $typeItem['NAME'] . '"/>';
				}
				$item['UNIT'] = $unit;
				$item['SERVICE'] = '<div>' . implode(' ', $types) . '</div>';

				$this->arResult['DETAIL']['LIST'][$item['ID']] = $item;
			}

			// связанные счетчики

			// if ($related['UF_MAIN'])
			// 	$countersObject[$related['UF_COUNTER']]['MAIN_RELATED'] = $counter;


			$arRelated = $this->arResult['RELATED'][$objectID];
			// gg($related);
			if (is_array($arRelated)) {

				foreach ($arRelated as $key => $related) {

					$relateCounter = $this->arResult['COUNTERS'][$related['UF_COUNTER']];

					$relatetypes = [];
					foreach ($relateCounter['UF_TYPE'] as $value) {
						$typeItem = $serviceList[$value];
						$unit = $typeItem['UNIT'];
						$relatetypes[] = '<img class="me-1" src="' . $typeItem['ICON'] . '" width="23" height="23" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '" data-bs-toggle="tooltip"
							data-bs-title="' . $typeItem['NAME'] . '"/>';
					}

					$counter = $related;
					// gg($relateCounter);

					$counter['RELATED'] = true;
					// $counter['ID'] = $relateCounter['ID'];
					$counter['UF_NUMBER'] =  $relateCounter['UF_NUMBER'];
					$counter['UF_NAME'] = '<div class="d-flex align-items-center">' . $relateCounter['UF_NAME'] . '<a role="button" class="ps-1 text-danger"
							data-bs-toggle="tooltip"
							data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - ' . $related['UF_PERCENT'] . '%">
							<i class="bi bi-link-45deg fs-5"></i></a></div>';
					$counter['UF_DATE'] = $relateCounter['UF_DATE'];
					$counter['UF_CHECK'] = $relateCounter['UF_CHECK'];
					$counter['SERVICE'] = '<div class="d-flex">' . implode('', $relatetypes) . '</div>';
					$counter['UNIT'] = $unit;

					if (!$related['UF_MAIN'])
						$this->arResult['DETAIL']['LIST'][$related['UF_COUNTER']] = $counter;
					else
						$this->arResult['DETAIL']['LIST'][$related['UF_COUNTER']]['MAIN_RELATED'] = $counter;
				}
			}


			// показания
			$prevMeters = LKClass::meters($objectID);
			$lastMeters = LKClass::meters($objectID, true);
			// dump($lastMeters);
			// dump($prevMeters);

			foreach ($prevMeters as $key => $value) {

				$arPrevMeters[$value['COUNTER']][] = $value['METER'];
				// $arPrevMeters[$value['COUNTER']] = [
				// 	'VALUE' => $value['METER'],
				// 	'DATE' => $value['DATE'],
				// 'COUNTER' => $value['COUNTER']
				// ];
			}

			// dump($lastMeters);
			foreach ($lastMeters as $key => $value) {
				$arLastMeters[$value['COUNTER']] = $value['METER'];

				$noteMeter[$value['COUNTER']] = $value['NOTE'];
				// $arLastMeters[$value['COUNTER']] = [
				// 	'VALUE' => $value['METER'],
				// 	'DATE' => $value['DATE'],
				// 	'COUNTER' => $value['COUNTER']
				// ];
			}
			// dump($arLastMeters);

			$this->arResult['DETAIL']['PREV_METERS'] = $arPrevMeters;
			$this->arResult['DETAIL']['LAST_METERS'] = $arLastMeters;
			$this->arResult['DETAIL']['NOTE_METERS'] = $noteMeter;

			// end DETAIL
			//

		} else { //LIST

			$prevMeters = [];
			$lastMeters = [];

			foreach ($this->arResult['COUNTER_OBJECTS'] as $key => &$object) {

				$objectID = $object['ID'];
				$object['LIST']  = LKClass::getCounters($objectID);

				foreach ($object['LIST'] as $key => &$item) {


					// gg($item['UF_ACTIVE']);

					if ($item['UF_ACTIVE'] !== null && !$item['UF_ACTIVE'])	//Отключаем неактивные
						unset($object['LIST'][$key]);

					$types = [];
					foreach ($item['UF_TYPE'] as $value) {
						$typeItem = $serviceList[$value];
						$unit = $typeItem['UNIT'];
						$types[] = '<img class="me-1" src="' . $typeItem['ICON'] . '" width="22" height="22" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '" data-bs-toggle="tooltip"
							data-bs-title="' . $typeItem['NAME'] . '"/>';
					}
					$item['UNIT'] = $unit;
					$item['SERVICE'] = '<div class="d-flex">' . implode(' ', $types) . '</div>';
				}

				$arRelated = $this->arResult['RELATED'][$objectID];

				if ($arRelated/* && !$related['UF_MAIN']*/) {

					if (is_array($arRelated)) {

						foreach ($arRelated as $key => $related) {

							$relateCounter = $this->arResult['COUNTERS'][$related['UF_COUNTER']];

							$relatetypes = [];
							// gg($serviceList);
							// gg($relateCounter['UF_TYPE']);
							foreach ($relateCounter['UF_TYPE'] as $value) {
								$typeItem = $serviceList[$value];
								$unit = $typeItem['UNIT'];
								$relatetypes[] = '<img class="me-1" src="' . $typeItem['ICON'] . '" width="22" height="22" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '"
							data-bs-toggle="tooltip"
							data-bs-title="' . $typeItem['NAME'] . '"
							/>';
							}
							// gg($related);
							$counter = $related;

							// if($related['UF_MAIN'])

							$counter['RELATED'] = true;
							// $counter['ID'] = $relateCounter['ID'];
							$counter['UF_NUMBER'] =  $relateCounter['UF_NUMBER'];
							$counter['UF_NAME'] = '<div class="d-flex align-items-center">' . $relateCounter['UF_NAME'] . '<a role="button" class="ps-1 text-danger"
							data-bs-toggle="tooltip"
							data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - ' . $related['UF_PERCENT'] . '%">
							<i class="bi bi-link-45deg fs-5"></i></a></div>';
							$counter['UF_DATE'] = $relateCounter['UF_DATE'];
							$counter['UF_CHECK'] = $relateCounter['UF_CHECK'];
							$counter['SERVICE'] = '<div class="d-flex">' . implode('', $relatetypes) . '</div>';
							$counter['UNIT'] = $unit;

							// $counter = [
							// 	'RELATED' => true,
							// 	'ID' => $relateCounter['ID'],
							// 	'UF_NAME' => '<div class="d-flex align-items-center">' . $relateCounter['UF_NAME'] . '<a role="button" class="ps-1 text-danger"
							// 		data-bs-toggle="tooltip"
							// 		data-bs-title="Расчет потребления производится от процента занимаемой объема/площади - ' . $related['UF_PERCENT'] . '%">
							// 		<i class="bi bi-link-45deg fs-5"></i></a></div>',
							// 	'UF_NUMBER' =>  $relateCounter['UF_NUMBER'],
							// 	'UF_DATE' => $relateCounter['UF_DATE'],
							// 	'UF_CHECK' => $relateCounter['UF_CHECK'],
							// 	'SERVICE' => '<div class="row gy-1">' . implode('', $relatetypes) . '</div>',
							// ];
							// gg($counter);

							if (!$related['UF_MAIN'])
								$object['LIST'][$related['ID']] = $counter;
							else
								$object['LIST'][$related['UF_COUNTER']]['MAIN_RELATED'] = $counter;
							// $object['RELATED'][] = $counter;
						}
					}
				}

				// gg($related);

				// if ($related['UF_MAIN'])
				// 	$object['LIST'][$related['UF_COUNTER']]['MAIN_RELATED'] = $counter;


				$prevMeters = LKClass::meters($objectID);
				$lastMeters = LKClass::meters($objectID, true);

				foreach ($prevMeters as $value) {
					$arPrevMeters[$value['COUNTER']][] = $value['METER'];
				}
				// gg($lastMeters);
				foreach ($lastMeters as $key => $value) {
					$arLastMeters[$value['COUNTER']] = $value['METER'];
					$noteMeter[$value['COUNTER']] = $value['NOTE'];
				}
				$object['PREV_METERS'] = $arPrevMeters;
				$object['LAST_METERS'] = $arLastMeters;
				$object['NOTE_METERS'] = $noteMeter;
			}

			$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));

			$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

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
				['id' => 'NAME', 'name' => 'Наименование объекта', /*'sort' => 'NAME', */ 'default' => true],
				['id' => 'ADDRESS', 'name' => 'Адрес объекта', /*'sort' => 'ADDRESS', */ 'default' => true, 'width' => 350],
				//['id' => 'DOGOVOR', 'name' => 'Договор',/* 'sort' => 'TIMESTAMP_X',*/ 'default' => true],

				// ['id' => 'STATUS', 'name' => 'Статус', 'sort' => '', 'default' => true, 'width' => '200'],
				['id' => 'DETAIL', 'name' => '', 'default' => true, 'width' => 130],
			];

			foreach ($arObjects as $key => &$item) {

				// $item['COMPANY'] = $item['COMPANY']['NAME'];

				$status = '<a class="ui-btn ui-btn-primary-dark" href="' . $item["ID"] . '/" target="_blank">Внести</a>';
				$item["DETAIL"] = $status;

				$this->arResult['GRID']['ROWS'][] = [
					'data' => $item
				];
			}
		}

		return $this->arResult;
	}

	public function sendMeterAction()
	{
		$request = Application::getInstance()->getContext()->getRequest();

		// return $request['METER'];
		$log = [
			'OBJECT' => $request['OBJECT'],
			'METER' => $request['METER'],
			'MONTH' => $request['MONTH'],
			'NOTE' => $request['NOTE']
		];

		Bitrix\Main\Diag\Debug::dumpToFile(var_export($log, 1), 'request sendMeter', 'test.log');

		foreach ($request['METER'] as $kCounter => $meter) {
			if ($meter)
				LKClass::saveMeter($request['OBJECT'], $request['MONTH'], $kCounter, $meter, $request['NOTE'][$kCounter]);
		}

		return true;
		// dump($request);
		// $data = $request['data'];
		// $time = $request['time'];

		// foreach ($time as $key => $value) {
		// 	$dateTime = new DateTime($value, "d.m.Y H:i:s");
		// 	// $dateTime->format("Y-m-d\TH:i");
		// 	Option::set("hackathon", $key, $dateTime->format("Y-m-d\TH:i"));
		// }

		// foreach ($data as $key => $value) {
		// 	Option::set("hackathon", $key, $value);
		// }

		// return $result;
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
				} elseif (
					$arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ADMINISTRATOR']
				) {
					$this->arResult['MODERATOR'] = true;
					return true;
				}
				if ($arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ORGANIZATION']) {
					return true;
				}

				// if ($arGroup['GROUP_ID'] == 1 || $arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ADMINISTRATOR']) {
				// 	$this->arResult['ADMIN'] = true;
				// 	return true;
				// }
				// if ($arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ORGANIZATION']) {
				// 	return true;
				// }

			}
		}
		return false;
	}

	// private function arraySort($a, $b)
	// {
	// 	if ($a['SORT'] == $b['SORT']) {
	// 		return 0;
	// 	}
	// 	return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	// }


	public function getRequest()
	{

		$instance = \Bitrix\Main\Application::getInstance();
		$context = $instance->getContext();
		$request = $context->getRequest();
		$arRequest = $request->toArray();
		return $arRequest;
	}

	public function isPost()
	{
		$instance = \Bitrix\Main\Application::getInstance();
		$context = $instance->getContext();
		$server = $context->getServer();
		return $server->getRequestMethod() == 'POST';
	}

	public function prepareComponentResult()
	{

		// if ($this->isPost()) {
		// $arRequest = $this->getRequest();

		// $REQUEST_ID = $this->arResult['DETAIL']['ID'];

		// $sendEmail = false;

		// if ($arRequest['checked'] && check_bitrix_sessid()) {

		// 	global $APPLICATION;
		// 	LocalRedirect($APPLICATION->GetCurDir());
		// 	//LocalRedirect($this->arParams['SEF_FOLDER']);

		// }
		// }
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
	protected function noSefMode()
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
	}
}
