<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$module_id = 'bendersay.exportimport';

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight($module_id) < "S") {
	$APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

\Bitrix\Main\Loader::includeModule($module_id);

$request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

// Описание опций
$aTabs = array(
	array(
		'DIV' => 'edit1',
		'TAB' => Loc::getMessage('BENDERSAY_EXPORTIMPORT_TAB_SETTINGS'),
		"TITLE" => Loc::getMessage('BENDERSAY_EXPORTIMPORT_TAB_SETTINGS'),
		'OPTIONS' => array(
			array(
				'export_send_email',
				Loc::getMessage('BENDERSAY_EXPORTIMPORT_EXPORT_SEND_EMAIL'),
				Option::get("bendersay.exportimport", "export_send_email"),
				array('text', 30)
			),
			array(
				'',
				Loc::getMessage('BENDERSAY_EXPORTIMPORT_URL_DATA_FILE'),
				'<input type="text" maxlength="255" value="'
				. Option::get("bendersay.exportimport", "url_data_file")
				. '" name="url_data_file"> <input type="button" value="..." OnClick="BtnClick()">',
				array('statichtml')
			)
		)
	),
	array(
		'DIV' => 'edit2',
		'TAB' => Loc::getMessage('MAIN_TAB_RIGHTS'),
		"TITLE" => Loc::getMessage('MAIN_TAB_TITLE_RIGHTS')
	),
);

// Сохранение
if ($request->isPost() && $request['Update'] && check_bitrix_sessid()) {

	foreach ($aTabs as $aTab) {
		foreach ($aTab['OPTIONS'] as $arOption) {
			if (!is_array($arOption)) //Строка с подсветкой. Используется для разделения настроек в одной вкладке
				continue;
			if ($arOption['note']) //Уведомление с подсветкой
				continue;


			$optionName = $arOption[0];

			$optionValue = $request->getPost($optionName);

			Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
		}
	}
}

// Визуальный вывод
$tabControl = new CAdminTabControl('tabControl', $aTabs);
?>
<? $tabControl->Begin(); ?>
	<form method='post'
		  action='<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($request['mid']) ?>&amp;lang=<?= $request['lang'] ?>'
		  name='bendersay_exportimport_settings'>
			  <? foreach ($aTabs as $aTab) {
				  if ($aTab['OPTIONS']) {
					  $tabControl->BeginNextTab();
					  __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
				 }
			} 
			// Для работы с файлами
			CAdminFileDialog::ShowScript
			(
				Array(
					'event' => 'BtnClick',
					'arResultDest' => array('FORM_NAME' => 'bendersay_exportimport_settings', 'FORM_ELEMENT_NAME' => 'url_data_file'),
					'arPath' => array('SITE' => SITE_ID, 'PATH' =>''),
					'select' => 'D',// F - file only, D - folder only
					'operation' => 'S',// O - open, S - save
					'showUploadTab' => true,
					'showAddToMenuTab' => false,
					//'fileFilter' => 'csv',
					'allowAllFiles' => true,
					'SaveConfig' => true,
				)
			);
			
		// Доступ к модулю
		$tabControl->BeginNextTab();
		require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php');

		$tabControl->Buttons(); ?>

		<input type="submit" name="Update" class="adm-btn-save" value="<? echo GetMessage('MAIN_SAVE') ?>">
		<?= bitrix_sessid_post(); ?>
	</form>
<? $tabControl->End(); ?>
