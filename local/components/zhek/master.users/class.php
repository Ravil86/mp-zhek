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

		$allCompany = $LKClass->getCompany();
		//$this->arResult['ALL_COMPANY'] = $allCompany;
		$this->arResult['GRID']['COUNT'] = count($allCompany);

		$result = \Bitrix\Main\UserGroupTable::getList(array(
			'order' => array(
				'USER.NAME' => 'ASC'
				// 'USER.LAST_LOGIN' => 'DESC'
			),
			'filter' => array(
				// 'USER.ACTIVE' => 'Y',
				'GROUP_ID' => [8, 1],
			),
			'select' => array(
				'ID' => 'USER.ID',
				'LOGIN' => 'USER.LOGIN',
				'EMAIL' => 'USER.EMAIL',
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
			$user['ORG_ID'] = '';
			$userList[$user['ID']] = $user;
			$userItems[$user['ID']] = $user['SHORT_NAME'];
		}

		$this->arResult['USERS'] = $userList;

		foreach ($allCompany as $key => $org) {
			if ($org['UF_USER_ID'])
				$this->arResult['USERS'][$org['UF_USER_ID']]['ORG_ID'] = $org['ID'];
		}

		function my_sort($a, $b)
		{
			// if (isset($a['ORG_ID']) == isset($b['ORG_ID'])) return 0;
			return ($a['ORG_ID'] < $b['ORG_ID']) ? -1 : 1;
		}
		usort($this->arResult['USERS'], "my_sort");

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
			// $countObjects = 0;

			$column = $item;

			if ($item['UF_NAME'])
				$column['UF_NAME'] = $item['UF_NAME'];
			// $column['UF_NAME'] = '<a class="ui-link fs-6' . (!$item['UF_ACTIVE'] ? ' ui-link-secondary opacity-75' : '') . '" href="' . $item["ID"] . '/">' . $item['UF_NAME'] . '</a>';

			$column['UF_ACTIVE'] = $item['UF_ACTIVE'] ? 'да' : 'нет';
			$item['UF_ACTIVE'] = $item['UF_ACTIVE'] ? 'Y' : 'N';
			// $item['UF_ACTIVE'] = $item['UF_ACTIVE'] == 'Y' ?: false;

			if ($item['UF_USER_ID']) {

				$orgUser = $userList[$item['UF_USER_ID']];
				$item['OPERATOR'] = '<div class="row align-items-center! gx-1">
										<div class="col-auto mt-1">[' . $orgUser['ID'] . ']</div>
										<div class="col"><span class="fw-semibold">' . $orgUser['SHORT_NAME'] . '</span><div>' . $orgUser['EMAIL'] . '</div></div>
										</div>';
				// $item['UF_USER'] = '[' . $orgUser['ID'] . '] ' . $orgUser['SHORT_NAME'];
			} else {
				$item['OPERATOR'] = '<a class="ui-link fs-6' . (!$item['UF_ACTIVE'] ? ' ui-link-secondary opacity-75' : '') . '" 
										data-bs-toggle="modal" data-bs-target="#addUser" onclick="setUser(' . $item['ID'] . ')">
										добавить</a>' /*.
					'<a class="ui-link fs-6' . (!$item['UF_ACTIVE'] ? ' ui-link-secondary opacity-75' : '') . '" 
										data-bs-toggle="modal" data-bs-target="#selectUser">
										выбрать</a>'*/;
			}

			$column["COPY_INFO"] = $item['UF_USER_ID'] ? '[' . $orgUser['LOGIN'] . ' / *****] 
						<button onclick="changeTooltipText(event)" type="button"
								class="btn clipboard_text icon-link! color-grey py-0"
			data-clipboard-text="' .
				$orgUser['SHORT_NAME'] . '
' . $orgUser['EMAIL'] . '
логин: ' . $orgUser['LOGIN'] . '
пароль: ' . $orgUser['UF_PASSWORD'] . '"><i class="revicon-export pe-0" data-bs-toggle="tooltip"
						data-bs-title="Скопировать доступы"></i></button>' : '';

			//$item["DETAIL"] = $status;

			$this->arResult['GRID']['ROWS'][] = [
				'data' => $item,			//для редактирования
				'columns'	=> $column,		//отображение
				'actions' => [ //Действия над ними
					[
						'text'    => 'Сменить / выбрать',
						'onclick' => 'selectUser(' . $item['ID'] . ')'
					],
					[
						'text'    => 'Удалить',
						'onclick' => 'if(confirm("Вы уверены, что хотите удалить данного пользователя?"))clearUser(' . $item['ID'] . ')'
					]

				],
				'counters' => [
					'COLUMN_ID' => [
						'type' => \Bitrix\Main\Grid\Counter\Type::LEFT,
						'value' => 2,
						'color' => 'counter-color-css-class',
						'size' => 'counter-size-css-class',
						'class' => 'counter-custom-css-class',
					],
				],
			];
		}
		// dump($this->arResult['USERS']);
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

			if ($arRequest["ADD_USER"] == 'Y') {

				$EMAIL = $arRequest["EMAIL"];
				$LOGIN = strstr($EMAIL, '@', true);
				// $LOGIN = preg_replace('/\+(.)*@/', '@', $EMAIL);

				$PASS = randString(8, array(
					"abcdefghijklnmopqrstuvwxyz",
					"ABCDEFGHIJKLNMOPQRSTUVWXYZ",
					"0123456789",
					"!@#\$%^&*()",
				));

				$user = new CUser;
				$fields = [
					"NAME"      => $arRequest["NAME"],
					"LOGIN"     => $LOGIN,
					"ACTIVE"    => "Y",
					"PASSWORD"  => $PASS,
					"CONFIRM_PASSWORD" => $PASS,
					"UF_PASSWORD" => $PASS,
					"EMAIL"     => $EMAIL,
					"GROUP_ID" => [3, 4, 8],
				];

				$userId = $user->Add($fields);
				if (intval($userId) > 0) {
					Bitrix\Main\Diag\Debug::dumpToFile($userId, 'Пользователь успешно добавлен', 'test.log');

					$fields = [
						"UF_USER_ID" => $userId,
					];
					LKClass::saveCompany($arRequest["ORG_ID"], $fields);

					// echo "Пользователь успешно добавлен, ID: " . $userId;
				} else {
					Bitrix\Main\Diag\Debug::dumpToFile($user->LAST_ERROR, 'шибка при добавлении Пользователя', 'test.log');
					// echo "Ошибка при добавлении: " . $user->LAST_ERROR;
				}
			} elseif ($arRequest["SELECT_USER"] == 'Y') {

				Bitrix\Main\Diag\Debug::dumpToFile($arRequest, 'Пользователь сменен', 'test.log');
				$fields = [
					"UF_USER_ID" => $arRequest["USER_ID"],
				];
				LKClass::saveCompany($arRequest["ORG_ID"], $fields);
				// LKClass::addCounter($arRequest["FIELDS"]);

			} elseif ($arRequest["CLEAR_USER"] == 'Y') {

				$fields = [
					"UF_USER_ID" => '',
				];
				LKClass::saveCompany($arRequest["ORG_ID"], $fields);
				Bitrix\Main\Diag\Debug::dumpToFile($arRequest, 'Пользователь удален', 'test.log');

				// 	LKClass::addCompany($arRequest["FIELDS"]);
			}
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
