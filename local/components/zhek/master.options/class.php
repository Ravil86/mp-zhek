<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock\Component\Tools,
	Bitrix\Iblock,
	Bitrix\Main\Application,
	Bitrix\Main\Web\Uri;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;


class MasterUsers extends CBitrixComponent implements Controllerable
{

	// protected static $_HL_Reference = "ReferenceCustomer"; // HL общий реестр
	// protected static $_HL_Objects = "Objects"; // HL общий реестр
	// protected static $_HL_Company = "Company"; // HL категории курсов


	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	public function executeComponent()
	{
		$this->run();
		// $this->prepareComponentResult();
		$this->includeComponentTemplate();
	}

	public function configureActions()
	{
		return [
			'request' => [
				'prefilters' => [],
			],
		];
	}

	public function requestAction()
	{
		$request = Application::getInstance()->getContext()->getRequest();
		// $arRequest = $request->toArray();
		$data = $request['DATA'];

		Bitrix\Main\Diag\Debug::dumpToFile(var_export($data, 1), 'Options Request', 'test.log');


		$saveStart = new DateTime($data['start'], 'd.m.Y');
		$saveEnd = new DateTime($data['end'], 'd.m.Y');

		$category = "cabinet";
		$name = "send";

		$value = array("date_start" => $saveStart->format('d'), "date_end" => $saveEnd->format('d'), 'edit_end' => '25');

		// return  $value;
		CUserOptions::SetOption($category, $name, $value, true);

		// return $saveStart->format('d');
	}


	private function run()
	{
		$this->arResult['ACCESS'] = $this->checkAccess();

		// $monthList = LKClass::getMonth();
		// $cabinetOption = LKClass::getOption();


		$LKClass = new LKClass();
		// gg($LKClass->getDataEnd());

		$curDay = date("d");

		// $dayStart = $cabinetOption['date_start'] ?: 25;	//Дата начала подачи
		// $dayEnd = $cabinetOption['date_end'] ?: date('t');	//конец месяца
		// $editEnd = $cabinetOption['edit_end'] ?: 5;

		$this->arResult['DATA_START'] = $LKClass->getDataStart() . '.' . date('m.Y');

		$this->arResult['DATA_END'] = $LKClass->getDataEnd() . '.' . ($LKClass->getDataEnd() < $LKClass->getDataStart() ? date("m", strtotime('+1 month')) : date('m')) . '.' . date('Y');
		// $this->arResult['DATA_END'] = $LKClass->getDataEnd() . '.' . date('m.Y');

		$this->arResult['EDIT_START'] = $LKClass->getEditEnd() . '.' . ($LKClass->getEditEnd() < date('d') ? date("m", strtotime('+1 month')) : date('m')) . '.' . date('Y');

		$this->arResult['EDIT_END'] = $LKClass->getEditEnd() + 1 . '.' . date('m.Y');

		// Форматирование с учетом формата сайта
		// $date = new DateTime();
		// echo $date->toString(); // Использует формат сайта из настроек

		// // Пользовательский формат
		// echo $date->format('d.m.Y H:i:s');

		// // Сравнение дат
		// $date1 = new DateTime('2025-12-15 10:00:00', 'Y-m-d H:i:s');
		// $date2 = new DateTime('2025-12-15 15:00:00', 'Y-m-d H:i:s');

		// if ($date1 < $date2) {
		// 	echo 'date1 раньше date2';
		// }

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

	// public function getRequest()
	// {

	// 	$instance = Application::getInstance();
	// 	$context = $instance->getContext();
	// 	$request = $context->getRequest();
	// 	$arRequest = $request->toArray();
	// 	return $arRequest;
	// }

	// public function isPost()
	// {
	// 	$instance = Application::getInstance();
	// 	$context = $instance->getContext();
	// 	$server = $context->getServer();
	// 	return $server->getRequestMethod() == 'POST';
	// }

	// public function prepareComponentResult()
	// {

	// 	$arRequest = $this->getRequest();

	// 	if ($this->isPost() && check_bitrix_sessid()) {

	// 		// dump($arRequest);

	// 		if (!isset($arRequest["AJAX_CALL"]))
	// 			LocalRedirect(Context::getCurrent()->getRequest()->getRequestUri());
	// 	}
	// }
}
