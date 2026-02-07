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

class MasterUsers extends CBitrixComponent
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
		$this->run();
		$this->prepareComponentResult();
		$this->includeComponentTemplate();
	}

	private function run()
	{

		$this->arResult['ACCESS'] = $this->checkAccess();
		$LKClass = new LKClass;

		$arItems = [];

		$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
		$arResult['GRID_ID'] = $this->arResult['GRID_ID'];

		$this->arResult['GRID']['COUNT'] = count($LKClass->getCompany());

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
		$nav_params = $grid_options->GetNavParams(array("nPageSize" => $this->arParams['PAGE_SIZE']));
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

		$filterOption = new Bitrix\Main\UI\Filter\Options("filter_" . $this->arResult["GRID_ID"]);
		$filter = $filterOption->GetFilter();
		$arItems = $LKClass->getCompany(null, $filter, $navParams, $order['sort']);

		foreach ($arItems as $key => &$item) {
			$countObjects = 0;

			$column = $item;

			if ($item['UF_NAME'])
				$column['UF_NAME'] = $item['UF_NAME'];
			// $column['UF_NAME'] = '<a class="ui-link fs-6' . (!$item['UF_ACTIVE'] ? ' ui-link-secondary opacity-75' : '') . '" href="' . $item["ID"] . '/">' . $item['UF_NAME'] . '</a>';

			$column['UF_ACTIVE'] = $item['UF_ACTIVE'] ? 'да' : 'нет';
			$item['UF_ACTIVE'] = $item['UF_ACTIVE'] ? 'Y' : 'N';
			// $item['UF_ACTIVE'] = $item['UF_ACTIVE'] == 'Y' ?: false;

			if ($item['UF_USER_ID']) {
				$orgUser = $userList[$item['UF_USER_ID']];
				// gg($orgUser);
				$item['OPERATOR'] = '[' . $orgUser['ID'] . '] ' . $orgUser['SHORT_NAME'];
				// $item['UF_USER'] = '[' . $orgUser['ID'] . '] ' . $orgUser['SHORT_NAME'];
			} else {
				$item['OPERATOR'] = '<a class="ui-link fs-6' . (!$item['UF_ACTIVE'] ? ' ui-link-secondary opacity-75' : '') . '" 
										data-bs-toggle="modal" data-bs-target="#addUser">
										добавить</a>';
			}

			$column["COPY_INFO"] = $item['UF_USER_ID'] ? '[' . $orgUser['LOGIN'] . ' / *****] <a class="ui-link fs-6' . (!$item['UF_ACTIVE'] ? ' ui-link-secondary opacity-75' : '') . '" 
						href="' . $item["ID"] . '/"><i class="revicon-export pe-0"></i></a>' : '';


			//$item["DETAIL"] = $status;


			$this->arResult['GRID']['ROWS'][] = [
				'data' => $item,			//для редактирования
				'columns'	=> $column		//отображение
			];
		}

		$this->arResult['GRID']['COLUMNS'] = [
			['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true, 'width' => 70],
			['id' => 'UF_NAME', 'name' => 'Наименование организации', 'sort' => 'UF_NAME', 'default' => true, 'width' => 500,  'editable' => false],
			['id' => 'UF_ACTIVE', 'name' => 'Активность', 'sort' => 'UF_ACTIVE', 'default' => true, 'width' => 105,  'editable' => false],
			['id' => 'OPERATOR', 'name' => 'Оператор', 'default' => true, "editable" => ['TYPE' => 'DROPDOWN', 'items' => $userItems]],
			['id' => 'COPY_INFO', 'name' => 'Скопировать инфу', 'default' => true],
			['id' => 'CHANGE_PASSWD', 'name' => 'Сменить пароль', 'default' => false],
			//['id' => 'UF_TYPE', 'name' => 'Тип организации', 'default' => false, "editable" => ['TYPE' => 'DROPDOWN', 'items' => $userItems]],
			// ['id' => 'DETAIL', 'name' => 'Объектов', 'default' => true],
		];

		// gg($this->arResult);

		return $this->arResult;

		/*$this->arResult['GRID_ID'] = str_replace('.', '_', str_replace(':', '_', $this->GetName()));
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

		//какую сортировку сохранил пользователь (передаем то, что по умолчанию)
		$arSort = $grid_options->GetSorting(array("sort" => array("timestamp_x" => "desc"), "vars" => array("by" => "by", "order" => "order")));
		$this->arResult['GRID']['COLUMNS'] = [
			['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => false, 'width' => 70],
			['id' => 'UF_NAME', 'name' => 'Организация',  'default' => true, 'editable' => true],
			['id' => 'UF_ADDRESS', 'name' => 'Адрес организации',  'default' => true, 'width' => 300, 'editable' => true],
			['id' => 'UF_INN', 'name' => 'ИНН', 'default' => true, 'width' => 200, 'editable' => true],
			['id' => 'USER', 'name' => 'Пользователь', 'default' => true],
			['id' => 'DETAIL', 'name' => '', 'default' => true],
		];

		$filterOption = new Bitrix\Main\UI\Filter\Options("filter_" . $this->arResult["GRID_ID"]);
		$filter = $filterOption->GetFilter();

		$navParams = [
			'offset' => $nav->getOffset(),
			'limit' => $nav->getLimit(),
		];

		$itemsCompany = LKClass::getCompany([], $filter, $navParams);

		foreach ($itemsCompany as $key => &$item) {
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
*/
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

			// if ($arRequest["ADD_OBJECT"] == 'Y') {
			// 	LKClass::addObject($arRequest["FIELDS"]);
			// } elseif ($arRequest["ADD_COUNTER"] == 'Y') {
			// 	LKClass::addCounter($arRequest["FIELDS"]);
			// } elseif ($arRequest["ADD_COMPANY"] == 'Y') {
			// 	LKClass::addCompany($arRequest["FIELDS"]);
			// } elseif ($arRequest["grid_id"] == 'zhek_master_objects') {
			// 	Bitrix\Main\Diag\Debug::dumpToFile(var_export($arRequest, 1), '$arRequest', 'test.log');
			// 	foreach ($arRequest["FIELDS"] as $companyID => $fields) {
			// 		LKClass::saveCompany($companyID, $fields);
			// 	}
			// } else {
			// 	foreach ($arRequest["FIELDS"] as $counterID => $fields) {
			// 		LKClass::saveCounter($counterID, $fields);
			// 	}
			// }

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
}
