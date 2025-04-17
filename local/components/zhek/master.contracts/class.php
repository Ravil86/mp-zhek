<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Uriit\Contest\Helper;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Iblock\Component\Tools,
	Bitrix\Main\Loader,
	Bitrix\Iblock,
	Bitrix\Main\Application,
	Bitrix\Main\Web\Uri;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class MasterContracts extends CBitrixComponent implements Controllerable
{

	protected static $_HL_Reference = "Contracts"; // HL Контракты


	var $serviceList = [];
	var $statusList = [];
	var $companyList = [];
	var $userInfo = [];
	var $yearList = [];

	/*public function onPrepareComponentParams($arParams) {

		$result = [
			'IBLOCK_ID' => intval($arParams['IBLOCK_ID']),
			'DETAIL_ID' => $arParams['DETAIL_ID'],
			'SEF_MODE' => $arParams['TASK_ID'],
			'CONTEST_ID' => intval($arParams['CONTEST_ID']),
			'CACHE_TYPE' => $arParams['CACHE_TYPE'],
			'CACHE_TIME' => isset($arParams['CACHE_TIME']) ? $arParams['CACHE_TIME'] : 36000000,
			'SEF_URL_TEMPLATES' => isset($arParams['SEF_URL_TEMPLATES']),
			'IBLOCK_CODES' => $arParams['IBLOCK_CODES'],
		];
		//$this->arParams = $arParams;
		//return $arParams;
		//return $result;
	}*/

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	public function configureActions()
	{
		return [
			'addContract' => [
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
		// gg($componentPage);
		if ($componentPage == 'month')
			$componentPage = 'detail';

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
		$this->arResult['PAGE_SIZE'] = 10;

		$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
		$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

		$this->serviceList = LKClass::getService();
		$this->statusList = LKClass::getStatus();
		$this->companyList = LKClass::getCompany();
		$this->userInfo = LKClass::curentUserFields();
		$this->yearList = LKClass::getYears(1);

		$this->arResult['SERVICE_LIST'] = $this->serviceList;
		$this->arResult['STATUS_LIST'] = $this->statusList;
		$this->arResult['COMPANY_LIST'] = $this->companyList;
		$this->arResult['YEAR_LIST'] = $this->yearList;
		$this->arResult['USER_INFO'] = $this->userInfo;

		// gg($this->arResult['YEAR_LIST']);

		foreach ($this->companyList as $key => $value) {
			$this->arResult['COMPANY_JSON'][] = [
				'value' => $value['ID'],
				'label' => $value['UF_NAME'],
			];
			$this->arResult['COMPANY1_JSON'][] = [
				'VALUE' => $value['ID'],
				'NAME' => $value['UF_NAME'],
			];
			$this->arResult['COMPANY_ITEMS'][$value['ID']] = $value['UF_NAME'];
		}

		foreach ($this->serviceList as $key => $value) {
			$this->arResult['SERVICE_JSON'][] = [
				'value' => $value['ID'],
				'label' => $value['NAME'],
			];
		}

		// $this->getIblockId = $this->getIblockId();

		if ($this->arResult['VARIABLES']) {

			$CONTRACT_ID = $this->arResult['VARIABLES']['DETAIL_ID'];
			$this->arResult['CONTRACT'] = $CONTRACT_ID;
			// $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
			// $uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());

			// $uri->addParams(array("foo"=>"bar"));

			// gg($uri);
			$template = $this->arResult['URL_TEMPLATES']['month'];

			$this->arResult['YEAR'] = $this->arResult['VARIABLES']['YEAR'];
			$this->arResult['MONTH'] = $this->arResult['VARIABLES']['MONTH'];

			if (!$this->arResult['YEAR'])
				$this->arResult['YEAR'] = date('Y');

			if (!$this->arResult['MONTH'])
				$this->arResult['MONTH'] = date('m');

			// gg($this->arResult['YEAR']);
			// gg($this->arResult['MONTH']);

			$template = preg_replace('(#DETAIL_ID#)', $CONTRACT_ID, $template);
			$template = preg_replace('(#YEAR#)', $this->arResult['YEAR'], $template);
			$template = preg_replace('(#MONTH#)', $this->arResult['MONTH'], $template);

			if (!$this->arResult['VARIABLES']['MONTH'])
				LocalRedirect($this->arResult['FOLDER'] . $template);

			$this->arResult['DETAIL'] = $this->getDocs()[$CONTRACT_ID];

			if ($this->arResult['DETAIL']['COMPANY'] && $this->arResult['DETAIL']['COMPANY']['USER_ID'])
				$this->arResult['DETAIL']['COMPANY']['RESPONIBLE'] = LKClass::curentUserFields($this->arResult['DETAIL']['COMPANY']['USER_ID']);

			// gg($this->arResult['DETAIL']);

			$this->arResult['COMPANY'] = $this->arResult['DETAIL']['COMPANY'];

			$orgServices = $this->arResult['DETAIL']['UF_SERVICE'];

			foreach ($orgServices as $service) {

				$this->arResult['SERVICE'][$service]['ID'] = $service;
				$this->arResult['SERVICE'][$service]['OBJECTS'] = [];
			}

			$arObjects = LKClass::getObjects($this->arResult['COMPANY']['ID']);


			$lossesList = LKClass::getLosses();
			// $lossObjects = [];
			foreach ($lossesList as $val) {
				$this->arResult['LOSSES'][$val['OBJECT']][$val['MONTH']] = $val['VALUE'];
			}

			$normativList = LKClass::getLosses(1);
			foreach ($normativList as $val) {
				$this->arResult['NORMATIV'][$val['OBJECT']][$val['MONTH']] = $val['VALUE'];
			}

			$this->arResult['MONTH_LIST'] = LKClass::getMonth();
			if ($this->arResult['MONTH_LIST'])
				foreach ($this->arResult['MONTH_LIST'] as $key => $month) {
					$this->arResult['MONTH_CODE'][$month['CODE']] = $key;
				}

			// gg($this->arResult['LOSSES']);

			$this->arResult['PREV_METERS'] = [];
			$this->arResult['LAST_METERS'] = [];
			foreach ($arObjects as $object) {

				$prevMetersObject = [];
				$lastMetersObject = [];

				// gg($this->arResult['MONTH']);
				// gg(LKClass::meters($object['ID'], true, $this->arResult['MONTH']));
				// gg(LKClass::meters($object['ID'], false, $this->arResult['MONTH']));

				$arPrevMeters = LKClass::meters($object['ID'], false, $this->arResult['MONTH'], $this->arResult['YEAR']);
				// gg($arPrevMeters);
				//$arPrevMeters = LKClass::meters($object['ID']);

				foreach ($arPrevMeters as $key => $meter) {
					$prevMetersObject[$meter['COUNTER']][] = $meter;
					// $metersObject[$meter['COUNTER']][$meter['ID']] = [
					// 	'ID' => $meter['ID'],
					// 	'METER' => $meter['METER'],
					// 	'DATE' => $meter['DATE'],
					// ];
				}
				// if ($prevMetersObject)
				// 	array_shift($prevMetersObject);

				// gg($prevMetersObject);
				$this->arResult['PREV_METERS'][$object['ID']] = $prevMetersObject;

				$arLastMeters = LKClass::meters($object['ID'], true, $this->arResult['MONTH'], $this->arResult['YEAR']);
				// $arLastMeters = LKClass::meters($object['ID'], true);

				// gg($arLastMeters);

				foreach ($arLastMeters as $key => $lastMeter) {
					$lastMetersObject[$lastMeter['COUNTER']][$lastMeter['ID']] = $lastMeter;
				}
				$this->arResult['LAST_METERS'][$object['ID']] = $lastMetersObject;

				// gg($object);
				// gg($this->arResult['LOSSES'][$object['ID']]);

				$counterObjects = LKClass::getCounters($object['ID']);

				foreach ($counterObjects as $counter) {

					// $this->arResult['METERS'][$object['ID']] = $object;
					// $this->arResult['METERS'][$object['ID']]['COUNTER'] = [];


					//$this->arResult['METERS'][$object['ID']]['COUNTERS'][$counter['ID']][] = $meter;

					foreach ($counter['UF_TYPE'] as $type) {

						$this->arResult['SERVICE'][$type]['OBJECTS'][$object['ID']]['INFO'] = $object;
						$this->arResult['SERVICE'][$type]['OBJECTS'][$object['ID']]['COUNTER'] = $counter;


						// $this->arResult['SERVICE'][$type]['OBJECTS'][$object['ID']]['METERS'] = $metersObject[$counter['ID']];
						// $this->arResult['SERVICE'][$type]['OBJECTS'][$object['ID']]['LAST_METERS'] = $metersLastObject[$counter['ID']];
					}
				}
			}
			// $this->arResult['DETAIL'] = $this->getAllDocs($arVariables['DETAIL_ID']);
			// $this->arResult['DOCSLIST'] = $this->getAllDocs($this->arResult['DETAIL']['ID'], ['DATE_CREATE'	=> 'DESC'], [], false, $this->arResult['DETAIL']['USER_ID'])['ITEMS'];

		} else {




			// $this->arResult['GRID_ID'] = 'grid_meter';

			// $arResult['GRID_ID'] = $this->arResult['GRID_ID'];

			//инициализируем объект с настройками пользователя для нашего грида
			$grid_options = new CGridOptions($this->arResult["GRID_ID"]);

			//размер страницы в постраничке (передаем умолчания)
			$nav_params = $grid_options->GetNavParams(array("nPageSize" => $this->arResult['PAGE_SIZE']));
			// $nav_params = $grid_options->GetNavParams();

			$nav = new Bitrix\Main\UI\PageNavigation($this->arResult["GRID_ID"]);
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
			// gg($nav_params);
			// gg($navParams);

			$arSort = $grid_options->GetSorting(array("sort" => array("UF_DATE" => "desc"), "vars" => array("by" => "by", "order" => "order")));



			// dump($this->serviceList);
			foreach ($this->serviceList as $serv) {
				$arService[$serv['ID']] = $serv['NAME'];
			}

			// dump(\Bitrix\Main\Grid\Editor\Types::getList());
			// dump($this->statusList);
			foreach ($this->statusList as $key => $stat) {
				$arStatus[$key] = $stat['VALUE'];
			}

			$arYears = LKClass::getYears();

			// gg($arYears);
			// gg($this->arResult);

			$this->arResult['GRID']['COLUMNS'] = [
				['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true, 'width' => 60],
				['id' => 'DATE', 'name' => 'Дата', 'sort' => 'UF_DATE', 'width' => 100, 'default' => true,  "editable" => ['TYPE' => 'CUSTOM']],
				// ['id' => 'DATE', 'name' => 'Дата', /*'sort' => 'TIMESTAMP_X',*/ 'default' => true, 'width' => 100, "editable" => ['TYPE' => 'DATE']],
				['id' => 'NUMBER', 'name' => '№', 'sort' => 'UF_NUMBER', 'default' => true, 'width' => 80, 'editable' => ['TYPE' => 'NUMBER']],
				['id' => 'YEAR', 'name' => 'Год', 'default' => true, 'sort' => 'UF_YEAR', 'width' => 80, 'editable' => ['TYPE' => 'DROPDOWN', 'items' => $arYears]],
				['id' => 'COMPANY', 'name' => 'Наименование организации', 'default' => true, 'editable' => ['TYPE' => 'DROPDOWN', 'items' => $this->arResult['COMPANY_ITEMS']]],
				['id' => 'FULL_NUMBER', 'name' => 'Номер', 'sort' => '', 'default' => true, 'width' => 160],
				['id' => 'SERVICE', 'name' => 'Услуги', 'default' => true, 'width' => 220, "editable" => ['TYPE' => 'MULTISELECT', 'items' => $arService]],
				['id' => 'STATUS', 'name' => 'Статус', 'default' => true, 'sort' => 'UF_STATUS', 'editable' => ['TYPE' => 'DROPDOWN', 'items' => $arStatus]],
				// ['id' => 'DETAIL', 'name' => '', 'default' => true, 'width' => '130'],
			];

			// if ($arSort['sort']['TIMESTAMP_X'])
			// 	$arSort['sort']['DATE_CREATE'] = $arSort['sort']['TIMESTAMP_X'];

			$filterOption = new Bitrix\Main\UI\Filter\Options("filter_" . $this->arResult["GRID_ID"]);
			$filterData = $filterOption->GetFilter();

			$userFilter = [];

			global $USER;
			if ($this->arResult['OPERATOR']) {
				$userFilter['USER_ID'] = $USER->GetID();
			}

			// if (isset($filterData["DATE_MODIFY_from"])) {
			// 	$userFilter["DATE_MODIFY_FROM"] = $filterData["DATE_MODIFY_from"];
			// 	$userFilter["DATE_MODIFY_TO"] = $filterData["DATE_MODIFY_to"];
			// }

			// if (isset($filterData["FIND"])) {
			// 	if (isset($filterData["NAME"]))
			// 		$userFilter["NAME"] = "%" . $filterData["NAME"] . "%";
			// 	else
			// 		$userFilter["NAME"] = "%" . $filterData["FIND"] . "%";
			// }

			if (isset($filterData["DATE_CREATE_from"])) {
				$userFilter[">=UF_DATE"] = $filterData["DATE_CREATE_from"];
			}
			if (isset($filterData["DATE_CREATE_to"])) {
				$userFilter["<=UF_DATE"] = $filterData["DATE_CREATE_to"];
			}

			$arAllItems = $this->getDocs(false, [], [], $userFilter);
			if (is_array($arAllItems))
				$this->arResult['GRID']['COUNT'] = count($arAllItems);
			// $this->arResult['GRID']["COUNT"] = $arItems['COUNT'];

			$arItems = $this->getDocs(false, $arSort['sort'], $navParams, $userFilter);

			foreach ($arItems as $key => $item) {

				$data = $item;
				$column = $item;

				$column['ID'] = '<a class="ui-btn ui-btn-xs ui-btn-success-light" href="' . $item["ID"] . '">' . $item['ID'] . '</a>';

				$column['SERVICE'] = '';
				$arService = [];
				foreach ($item['PROVIDER'] as $pid => $value) {
					$arService[$pid] = '<span class="text-' . $value['COLOR'] . '">' . $value['VALUE'] . '</span>';
					// $arService['SERVICE'][$pid] = $value['VALUE'];
				}
				if ($arService)
					$column['SERVICE'] = implode('<br>', $arService);

				$data['SERVICE'] = array_values($item['UF_SERVICE']);
				$data['STATUS'] = $item['UF_STATUS'];

				$data['YEAR'] = $item['UF_YEAR'];

				// $number = '№ ' . $item['NUMBER'] . '-' . $item['YEAR'] . ' от ' . $item['DATE'];
				// $item["FULL_NUMBER"] = $number;
				$data['COMPANY'] = $item['COMPANY']['ID'];
				$column['COMPANY'] = $item['COMPANY']['NAME'];

				$status = '<a class="d-flex!" href="' . $item["ID"] . '/">';
				$status .= '<div class="btn btn-' . $item['STATUS']['CODE'] . ' px-3 py-1 text-center opacity-75 text-nowrap"><small>' . $item['STATUS']['VALUE'] . '</small></div>';
				$status .= '</a>';
				$column["STATUS"] = $status;

				// <input value="01.01.2025" name="DATE" class="main-grid-editor main-grid-editor-text main-grid-editor-date" id="DATE_control">
				$data['DATE'] = '<div class="ui-ctl ui-ctl-after-icon ui-ctl-date">
									<a class="ui-ctl-after ui-ctl-icon-calendar"></a>
									<!--<div class="ui-ctl-element">14.10.2014</div>-->
								<input type="text" id="" class= "ui-ctl-element" name="DATE" value="' . $item['DATE'] . '">
								</div>
									<script>
										(function() {
											const input = document.querySelector(`input[name="DATE"]`);

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
				// gg($data);
				$this->arResult['GRID']['ROWS'][] = [
					'columns' => $column,
					'data' => $data			//Данные для инлайн-редактирования
				];
			}
			// gg($arItems['ITEMS']);
			// $this->arResult['LIST'] = $arItems['ITEMS'];

			// $this->arResult['AREA'] = $this->getArea($arParams['IBLOCK_CODES']['CITY']);
			// foreach ($this->arResult['AREA'] as $key => $city) {
			// 	$arCity[$city['ID']] = $city['NAME'];
			// }

			$this->arResult['GRID']["FILTER"] = [
				['id' => 'DATE_CREATE', 'name' => 'Дата контракта', 'type' => 'date', 'default' => true],
				// ['id' => 'NAME', 'name' => 'ФИО', 'type' => 'text', 'default' => true],
				// ['id' => 'MO', 'name' => 'Муниципалитет', 'type' => 'list', 'items' => $arCity, 'params' => ['multiple' => 'N'], 'default' => true],
				// ['id' => 'COURSE', 'name' => 'Курс', 'type' => 'list', 'items' => $this->courses, 'params' => ['multiple' => 'Y'], 'default' => true],
			];

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

	/**
	 * Получаем все документы пользователей
	 *
	 * @param string $group - группа модератора для которой находить участников
	 */
	private function getDocs($detailID = null, $arSort = [], $arNav = [], $userFilter = [], $userDetail = null)
	{
		global $USER;
		$arParams = $this->arParams;

		$getCompany = [];
		$arCompany = [];

		// $getContracts = LKClass::getContracts($arCompany['ID']);

		// $filter = [];
		// gg($userFilter);
		if (isset($userFilter['USER_ID'])) {
			$arCompany = LKClass::getCompany($userFilter['USER_ID']);
			if ($arCompany && isset($arCompany['ID']))
				unset($userFilter['USER_ID']);	// В HL контрактов нет фильтра по USER_ID
			$itemList = LKClass::getContracts($arCompany['ID'], $userFilter, $arSort, $arNav);
			// $itemList = $this->getContracts($arCompany['ID']);
		} else {
			$getCompany = $this->companyList;
			$itemList = LKClass::getContracts(false, $userFilter, $arSort, $arNav);
			// $getCompany = LKClass::getCompany();
			// $itemList = $this->getContracts();
		}

		// $serviceList = LKClass::getService();


		// $statusList = LKClass::getStatus();

		foreach ($itemList as &$value) {

			$companyID = $value['COMPANY'];
			// $value['NUMBER'] = $value['UF_NUMBER'] < 10 ? '0' . $value['UF_NUMBER'] : $value['UF_NUMBER'];

			unset($value['COMPANY']);
			// $value['COMPANY']['ID'] = $value['COMPANY'];
			$value['COMPANY']['ID'] = $companyID;

			// gg($value);

			if ($getCompany)
				$arCompany = $getCompany[$companyID];

			// if ($value['UF_YEAR']) {
			// 	$value['YEAR'] = $yearList[$value['UF_YEAR']];
			// }
			// dump($arCompany);

			// $value['STATUS'] = $this->statusList[$value['UF_STATUS']];

			if ($arCompany) {
				// $value['COMPANY']['NAME'] = $arCompany['UF_NAME'];

				foreach ($arCompany as $key => $item) {
					preg_match('/^UF_(\D*)/', $key, $match);
					if ($match[0]) {
						$value['COMPANY'][$match[1]] = $item;
						// $value['COMPANY_INFO'][$match[1]] = $item;
					}
				}
			}

			//$value['COMPANY'] = $getCompany[$value['COMPANY']];

			if ($value['UF_SERVICE'] && is_array($value['UF_SERVICE'])) {

				foreach ($value['UF_SERVICE'] as $service) {
					// dump($service);
					$arService = $this->serviceList[$service];
					$arService['VALUE'] = $arService['LITERA'] . ' - ' . $arService['NAME'];
					// $arService['VALUE'] = $arService['NAME'] . ' - ' . $arService['LITERA'];

					$value['PROVIDER'][] = $arService;
				}
			}


			// dump($value['PROVIDER']);
		}
		return $itemList;

		// $filter['IBLOCK_ID'] = $this->getIblockId;

		// foreach ($getUsersDocs as $key => $value) {
		// 	$result['ITEMS'][$arData['ID']] = $arData;
		// }



		// foreach ($result['ITEMS'] as $k => &$item) {

		// 	$userID = $item['USER_ID'];
		// 	$userDocStat = $getDocsStatus[$userID];

		// 	$item['USER'] = $arUserFields[$userID];
		// 	$item['USERNAME'] = $arUserFields[$userID]['LAST_NAME'].' '.$arUserFields[$userID]['NAME'].' '.$arUserFields[$userID]['SECOND_NAME'];
		// 	$item['SNILS'] = $arUserFields[$userID]['UF_SNILS'];
		// 	$item['FOTO'] = CFile::GetPath($arUserFields[$userID]['PERSONAL_PHOTO']);
		// }

		// if($detailID && !$userDetail)
		// 	return array_shift($result['ITEMS']);
		// else
		// 	return $result;

		//return $this->arResult['ITEMS'];
	}


	/* Получаем ID инфоблока по коду
	*
	* @return int
	*/
	// private function getIblockIdByCode(string $code)
	// {
	// 	if (\CModule::IncludeModule("iblock")) {

	// 		$iblock = \Bitrix\Iblock\IblockTable::getList(array(
	// 			'order' => array('SORT' => 'asc'),
	// 			'select' => array('*'),
	// 			'filter' => array('ACTIVE' => 'Y', 'CODE' => $code),
	// 			"cache" => ["ttl" => 3600]
	// 		))->fetch();

	// 		return $iblock['ID'];
	// 	} else {
	// 		return false;
	// 	}
	// }


	// private function getIblockId()
	// {
	// 	$arParams = $this->arParams;

	// 	$this->arResult['ID_DOCUMENTS'] = self::getIblockIdByCode($arParams['IBLOCK_CODES']['REQUEST']);

	// 	return $this->arResult['ID_DOCUMENTS'];
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

				// gg(LKClass::isOperator());
				if ($arGroup['GROUP_ID'] == 1) {
					$this->arResult['ADMIN'] = true;
					return true;
				} elseif (
					$arGroup['STRING_ID'] === $arParams['GROUP_CODES']['ADMINISTRATOR']
				) {
					$this->arResult['MODERATOR'] = true;
					return true;
				}
				if ($arGroup['STRING_ID'] === $arParams['GROUP_CODES']['OPERATOR']) {
					$this->arResult['OPERATOR'] = true;
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

		$arRequest = $this->getRequest();

		if ($this->isPost() && check_bitrix_sessid()) {

			Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), '$arRequest', 'test.log');

			if ($arRequest["ADD_CONTRACT"] == 'Y') {
				// Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), '$data', 'test.log');
				LKClass::addContract($arRequest["FIELDS"]);
			} else {
				if (isset($arRequest["ID"])) {
					foreach ($arRequest["ID"] as $contract) {
						LKClass::deleteContract($contract);
					}
				} else {
					$data = [];
					foreach ($arRequest["FIELDS"] as $contractID => $fields) {

						foreach ($fields as $key => $value) {
							// Bitrix\Main\Diag\Debug::dumpToFile(var_export($value, 1), '$$value', 'test.log');

							if ($key == 'SERVICE' && is_array($value)) {
								foreach ($value as $val) {
									$arVal[] = $val['VALUE'];
								}
								$data['UF_' . $key] = $arVal;
							} else {
								$data['UF_' . $key] = $value;
							}
						}
						// Bitrix\Main\Diag\Debug::dumpToFile(var_export($data, 1), '$data', 'test.log');

						LKClass::saveContract($contractID, $data);
					}
				}
			}

			if (!isset($arRequest["AJAX_CALL"]))
				LocalRedirect(Context::getCurrent()->getRequest()->getRequestUri());
		}
	}

	public function addContractAction()
	{

		$arRequest = $this->getRequest();
		Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), 'addContractAction $arRequest', 'test.log');

		return LKClass::addContract($arRequest["FIELDS"]);
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

		//if (empty($this->arParams["SEF_FOLDER"])) {
		// получаем данные из настроек инфоблока
		// $dbResult = CIBlock::GetByID($this->arParams["IBLOCK_ID"])->GetNext();
		// if (!empty($dbResult)) {
		//     // перетираем данные в $arParams["SEF_URL_TEMPLATES"]
		//     $this->arParams["SEF_URL_TEMPLATES"]["detail"] = $dbResult["DETAIL_PAGE_URL"];
		//     $this->arParams["SEF_URL_TEMPLATES"]["section"] = $dbResult["SECTION_PAGE_URL"];
		//     $this->arParams["SEF_FOLDER"] = $dbResult["LIST_PAGE_URL"];
		// }
		//}

		$arDefaultUrlTemplates404 = [
			"detail" => "#DETAIL_ID#/",
			"month" => "#DETAIL_ID#/#YEAR#/#MONTH#",
			// "month" => "#DETAIL_ID#/#DATE#/",
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
