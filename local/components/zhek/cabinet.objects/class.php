<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Uriit\Contest\Helper;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock,
	Bitrix\Main\Application,
	Bitrix\Main\Web\Uri;

class CabinetObjects extends CBitrixComponent
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

		$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
		$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

		$arItems = [];

		$serviceList = LKClass::getService();

		$myCompany = LKClass::myCompany();

		if ($myCompany)
			$arObjects = LKClass::getObjects($myCompany['ID']);

		if ($this->arResult['VARIABLES']) {

			$objectID = $this->arResult['VARIABLES']['DETAIL_ID'];
			$this->arResult['DETAIL']['ID'] = $objectID;

			$this->arResult['DETAIL']['GRID'] =  $this->arResult['GRID_ID'] . '_detail';
			$arResult['DETAIL']['GRID'] = $this->arResult['DETAIL']['GRID'];

			$this->arResult['DETAIL']['OBJECT'] = $arObjects[$objectID];

			$countersObject = LKClass::getCounters($this->arResult['VARIABLES']['DETAIL_ID']);
			$this->arResult['COUNTERS'] = LKClass::getCounters();
			// gg($this->arResult['COUNTERS']);

			$grid_options = new CGridOptions($this->arResult['DETAIL']['GRID']);
			$arSort = $grid_options->GetSorting(array("sort" => array("timestamp_x" => "desc"), "vars" => array("by" => "by", "order" => "order")));

			$this->arResult['DETAIL']['COLUMNS'] = [
				['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false, 'width' => 70],
				['id' => 'UF_NAME', 'name' => 'Наименование', 'default' => true, 'width' => 220],
				['id' => 'UF_NUMBER', 'name' => 'Номер cчетчика', 'default' => true, 'width' => 230],
				['id' => 'UF_DATE', 'name' => 'Дата установки', 'default' => false],
				['id' => 'SERVICE', 'name' => 'Тип счетчика', 'default' => true, 'width' => 230],
				['id' => 'UF_CHECK', 'name' => 'Дата очередной поверки', 'default' => true, 'width' => 200],
				// ['id' => 'DETAIL', 'name' => '', 'default' => true, 'width' => '130'],
			];

			foreach ($countersObject as $key => &$item) {

				$types = [];

				foreach ($item['UF_TYPE'] as $value) {
					$typeItem = $serviceList[$value];
					$types[] = '<div><img src="' . $typeItem['ICON'] . '" width="25" height="25" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '"/><span class="ps-1">' . $typeItem['NAME'] . '</span></div>';
					// $types[] = '<img src="' . $typeItem['ICON'] . '" width="25" height="25" alt="' . $typeItem['NAME'] . '" title="' . $typeItem['NAME'] . '"/>';
				}

				$item['SERVICE'] = '<div class="row gy-1">' . implode(' ', $types) . '</div>';
				// dump($serviceList[$item['TYPE']]);

				// $item['TYPE'] = $serviceList[$item['TYPE']]['NAME'];

				// $serviceList

				// $status = '<a class="d-flex!" href="' . $item["ID"] . '/">';
				// $status .= '<div class="btn btn-primary px-3 py-1 text-center opacity-75"><small>Инфо</small></div>';
				// $status .= '</a>';
				// $item["DETAIL"] = $status;

				$this->arResult['DETAIL']['ROWS'][] = [
					'data' => $item
				];
			}
			$this->arResult['RELATED'] = LKClass::getRelated();
			$this->arResult['OBJECTS'] = LKClass::getObjects();
			$this->arResult['COMPANY'] = LKClass::getCompany();
			// gg(LKClass::getRelated());

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
				['id' => 'NAME', 'name' => 'Наименование объекта', /*'sort' => 'NAME', */ 'default' => true, 'width' => 300],
				['id' => 'ADDRESS', 'name' => 'Адрес объекта', /*'sort' => 'ADDRESS', */ 'default' => true, 'width' => 350],
				//['id' => 'DOGOVOR', 'name' => 'Договор',/* 'sort' => 'TIMESTAMP_X',*/ 'default' => true, 'width' => '150'],

				// ['id' => 'STATUS', 'name' => 'Статус', 'sort' => '', 'default' => true, 'width' => '200'],
				['id' => 'DETAIL', 'name' => '', 'default' => true, 'width' => 130],
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



			// $arItems = $this->getDocs(false, $arSort['sort'], $nav_params, $userFilter);

			// dump($arItems);
			foreach ($arObjects as $key => &$item) {

				// dump($item);

				// $item['COMPANY'] = $item['COMPANY']['NAME'];

				$status = '<a class="ui-btn ui-btn-default" href="' . $item["ID"] . '/">Инфо</a>';
				//$status .= '<div class="btn btn-primary px-3 py-1 text-center opacity-75"><small>Инфо</small></div>';

				$item["DETAIL"] = $status;

				$this->arResult['GRID']['ROWS'][] = [
					'data' => $item
				];
			}

			// $this->arResult['LIST'] = $arItems['ITEMS'];

			// $this->arResult['GRID']["COUNT"] = $arItems['COUNT'];

			// $this->arResult['AREA'] = $this->getArea($arParams['IBLOCK_CODES']['CITY']);
			// foreach ($this->arResult['AREA'] as $key => $city) {
			// 	$arCity[$city['ID']] = $city['NAME'];
			// }

			/*$this->arResult['GRID']["FILTER"] = [
				['id' => 'DATE_CREATE', 'name' => 'Дата', 'type' => 'date', 'default' => true],
				['id' => 'NAME', 'name' => 'ФИО', 'type' => 'text', 'default' => true],
				// ['id' => 'MO', 'name' => 'Муниципалитет', 'type' => 'list', 'items' => $arCity, 'params' => ['multiple' => 'N'], 'default' => true],
				// ['id' => 'COURSE', 'name' => 'Курс', 'type' => 'list', 'items' => $this->courses, 'params' => ['multiple' => 'Y'], 'default' => true],
			];*/

			/*foreach ($this->arResult['LIST'] as $key => $value):?>
<?
				$data = [];
				$arr = ParseDateTime($value['DATE_UPDATE'], FORMAT_DATETIME); // $value['DATE_CREATE']
				$dateModif = strtolower(FormatDate("d M", MakeTimeStamp($value['DATE_UPDATE'])));
				$dateCreate = strtolower(FormatDate("d M y", MakeTimeStamp($value['DATE_CREATE'])));

				$time = $arr["HH"].":".$arr["MI"];
				//$yearShort = strtolower(FormatDate("y", MakeTimeStamp($value['DATE_UPDATE'])));;
				$year = $arr["YYYY"];

				$data["ID"] = $value['ID'];

				$data['DATE'] = '<div class="info_item_inner info_item_date">';
				$data['DATE'] .= '<span class="text-nowrap! lh-sm small">'.$dateModif.' '.$time.'<br>'.$year.'</span></div>';
				//$data['DATE'] .= '<div class="text-secondary small fst-italic py-2">#'.$value['ID'].'</div>';
				$data['DATE'] .= '<div class="text-secondary small fst-italic py-2">#'.$value['ID'].' от '.$value['DATE_CREATE'].'</div>';

				$data['NAME'] = '<div class="row g-0"><div class="col-6 col-sm-8 col-md-2 col-lg-auto">';
							if($value["FOTO"]){
								$data['NAME'] .= '<div class="user_photo" style="background-image: url('.$value["FOTO"].')"></div>';
							}
							else{
								$bgColor = self::stringToColorCode(mb_substr($value['USERNAME'],0,6));
								$data['NAME'] .= '<div class="user_photo d-flex justify-content-center align-items-center text-'.self::contrast_color($bgColor).' fs-5"
									style="background-color: #'.$bgColor.'">';
								$data['NAME'] .= mb_substr($value['USERNAME'],0,1).'</div>';
							}
				$data['NAME'] .= '</div>
						<a class="col" href="'.$value["DETAIL_PAGE_URL"].'" class="olimp_item-a">

							<div class="col">
								<div class="user_title h6">'.$value['USERNAME'].'</div>
							</div>
							<div class="col">
									<div class="text-secondary small"><span>'.$value['CITY'].'</span></div>
							</div>

					</a></div>';


				$data["COURSE"] = $value['COURSE'];

				//$streamReqID = $getLearning[$value['USER_ID']]['REQUESTS'][$value['REQUEST_ID']]['STREAM_ID'];
				//$streamReqInfo = $streamList[$streamReqID];

				$data["STREAM"] = $value["STREAM"]['NAME'].'<br>'.$value["STREAM"]['TEXT'];

				//$streamCourse = $streamList[ (key( $getLearningOld[$value['USER_ID']][$value['COURSE_ID']]) )];
				//$data["STREAM"] .= '<br>ОЛД_'.$streamCourse['NAME'].'<br>'.$streamCourse['TEXT'];


				// dump($value);
				$data["SNILS"] = $value["SNILS"];

				$data["NOTE"] = $value['NOTE'];
				?>
<?
				$this->arResult['GRID']['ROWS'][] = [
					'data' => $data
				];

			endforeach;*/
		}

		//return $componentPage;
		return $this->arResult;
	}


	// private function stringToColorCode($str)
	// {
	// 	$code = dechex(crc32($str));
	// 	$code = substr($code, 0, 6);
	// 	return $code;
	// }

	// private function contrast_color($hex)
	// {
	// 	$hex = trim($hex, ' #');

	// 	$size = strlen($hex);
	// 	if ($size == 3) {
	// 		$parts = str_split($hex, 1);
	// 		$hex = '';
	// 		foreach ($parts as $row) {
	// 			$hex .= $row . $row;
	// 		}
	// 	}

	// 	$dec = hexdec($hex);
	// 	$rgb = array(
	// 		0xFF & ($dec >> 0x10),
	// 		0xFF & ($dec >> 0x8),
	// 		0xFF & $dec
	// 	);

	// 	$contrast = (round($rgb[0] * 299) + round($rgb[1] * 587) + round($rgb[2] * 114)) / 1000;
	// 	return ($contrast >= 133) ? 'dark' : 'white';
	// }


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


	/*
    Список данных из HLblock c данными документИД/пользователь/статус
    */
	private function getListDocs($userID = [])
	{

		$classsDocs = \HLWrap::init(self::$_HL_Reference);

		// $getStatusList = self::getStatusList();

		$filter = [];
		// if($userID)
		// 	$filter['UF_USER_ID'] = $userID;


		$rsDocs = $classsDocs::getList(
			array(
				'select' => array('*'),
				// 'filter' => $filter,
			)
		);

		while ($arDoc = $rsDocs->Fetch()) {
			$arFields = [
				'ID' => $arDoc['ID'],
				'NUMBER' => $arDoc['UF_NUMBER'],
				'COMPANY' => $arDoc['UF_COMPANY'],
				'DATE' => $arDoc['UF_DATE'],
				'STATUS' => $arDoc['UF_STATUS'],
				// 'DATE' => ConvertDateTime($arDoc['UF_DATE'], "DD.MM.Y GG:MI:SS", "ru")
				//'UF_STATUS' => $getStatusList[$arStatus['UF_STATUS']],
			];
			$result[$arDoc['ID']] = $arFields;
		}
		// dump($result);
		return $result;
	}


	/**
	 * Получить имя элемента
	 *
	 * @param array $arElements - массив элементов
	 * @param int $id - идентификатор элемента
	 *
	 * @return string
	 */
	private function getElementName($arElements, $id)
	{
		foreach ($arElements as $value) {
			if ($value['ID'] == $id)
				return $value['NAME'];
		}
	}

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

		if ($this->isPost()) {
			$arRequest = $this->getRequest();

			$REQUEST_ID = $this->arResult['DETAIL']['ID'];

			$sendEmail = false;

			if ($arRequest['checked'] && check_bitrix_sessid()) {

				$typeStatus = 'Документы приняты';

				if ($arRequest['verification'] == 'Y') {
					$arLoadProductArray = array(
						"ACTIVE"         => "Y",
					);
					$el = new CIBlockElement;
					$res = $el->Update($REQUEST_ID, $arLoadProductArray);
					CIBlockElement::SetPropertyValues($REQUEST_ID, $this->getIblockId, 'N', 'CHECKED');
				} elseif ($arRequest['revision'] == 'Y') {
					$arLoadProductArray = array(
						"ACTIVE"         => "N",
					);
					if (strlen($arRequest['note']) > 0)
						$arLoadProductArray['PREVIEW_TEXT'] = $arRequest['note'];

					$el = new CIBlockElement;
					$res = $el->Update($REQUEST_ID, $arLoadProductArray);
					CIBlockElement::SetPropertyValues($REQUEST_ID, $this->getIblockId, 'Y', 'CHECKED');
					$typeStatus = 'Документ(ы) некорректные';
					$sendEmail = true;
				} else {
					$arLoadProductArray = array(
						"ACTIVE"         => "Y",
					);
					$el = new CIBlockElement;
					$res = $el->Update($REQUEST_ID, $arLoadProductArray);
					CIBlockElement::SetPropertyValues($REQUEST_ID, $this->getIblockId, 'Y', 'CHECKED');
					$sendEmail = true;
				}

				if ($sendEmail)
					self::sendMail($typeStatus, $arRequest['note']);

				global $APPLICATION;
				LocalRedirect($APPLICATION->GetCurDir());
				//LocalRedirect($this->arParams['SEF_FOLDER']);

			}
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
